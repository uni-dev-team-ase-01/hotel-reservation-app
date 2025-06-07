<?php

namespace App\Filament\Resources;

use App\Enum\ReservationStatus;
use App\Enum\UserRoleType;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers\BillsRelationManager;
use App\Filament\Resources\ReservationResource\RelationManagers\ReservationRoomsRelationManager;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isHotelStaff = $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
        $userHotels = $isHotelStaff ? $user->userHotels->pluck('hotel_id') : collect();

        return $form
            ->schema([
                Forms\Components\Select::make('hotel_id')
                    ->label('Hotel')
                    ->options(function () use ($isHotelStaff, $userHotels) {
                        if ($isHotelStaff) {
                            return Hotel::whereIn('id', $userHotels)->pluck('name', 'id');
                        }

                        return Hotel::pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->live()
                    ->default(function () {
                        $hotelId = request()->query('hotel_id');
                        if ($hotelId && Hotel::where('id', $hotelId)->exists()) {
                            return $hotelId;
                        }

                        return null;
                    })
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('rooms', [])),

                Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name', function (Builder $query) {
                        $query->whereHas('roles', function (Builder $roleQuery) {
                            $roleQuery->whereIn('name', [
                                UserRoleType::CUSTOMER->value,
                                UserRoleType::TRAVEL_COMPANY->value,
                            ]);
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        $user_id = request()->query('user_id');
                        if (
                            $user_id &&
                            User::where('id', $user_id)
                                ->whereHas('roles', function (Builder $roleQuery) {
                                    $roleQuery->whereIn('name', [
                                        UserRoleType::CUSTOMER->value,
                                        UserRoleType::TRAVEL_COMPANY->value,
                                    ]);
                                })
                                ->exists()
                        ) {
                            return $user_id;
                        }

                        return null;
                    })
                    ->required(),

                Forms\Components\DateTimePicker::make('check_in_date')
                    ->label('Check-in Date')
                    ->required()
                    ->minDate(now())
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->native(false)
                    ->default(function () {
                        $check_in = request()->query('check_in');
                        if ($check_in) {
                            return $check_in;
                        }

                        return null;
                    }),

                Forms\Components\DateTimePicker::make('check_out_date')
                    ->label('Check-out Date')
                    ->required()
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y H:i')
                    ->format('Y-m-d H:i:s')
                    // ->timezone('UTC')
                    ->native(false)
                    ->default(function () {
                        $check_out = request()->query('check_out');
                        if ($check_out) {
                            return $check_out;
                        }

                        return null;
                    })
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('rooms', [])),

                // Forms\Components\DatePicker::make('check_out_date')
                //     ->label('Check-out Date')
                //     ->required()
                //     ->after('check_in_date')
                //     ->live()
                //     ->default(function () {
                //         $check_out = request()->query('check_out');
                //         if ($check_out) {
                //             return $check_out;
                //         }
                //         return null;
                //     })
                //     ->afterStateUpdated(fn(Forms\Set $set) => $set('rooms', [])),

                Forms\Components\TextInput::make('number_of_guests')
                    ->label('Number of Guests')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->default(function () {
                        $guests = request()->query('guests');
                        if ($guests) {
                            return $guests;
                        }

                        return null;
                    })
                    ->required(),

                Forms\Components\Select::make('rooms')
                    ->label('Rooms')
                    ->multiple()
                    ->relationship('rooms', 'room_number')
                    ->options(function (Forms\Get $get) {
                        $hotelId = $get('hotel_id');
                        $checkIn = $get('check_in_date');
                        $checkOut = $get('check_out_date');

                        if (! $hotelId || ! $checkIn || ! $checkOut) {
                            return [];
                        }

                        return Room::availableForDates($checkIn, $checkOut, $hotelId)
                            ->pluck('room_number', 'id');
                    })
                    ->default(function () {
                        $rooms = request()->query('rooms');
                        if (is_array($rooms)) {
                            $roomIds = array_map('intval', $rooms);

                            $validRoomIds = Room::whereIn('id', $roomIds)->pluck('id')->toArray();

                            return $validRoomIds;
                        }

                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\TextInput::make('confirmation_number')
                    ->label('Confirmation Number')
                    ->default(fn () => 'RES-'.strtoupper(uniqid()))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('confirmation_number')
                    ->label('Confirmation #')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Rooms')
                    ->counts('rooms')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('check_in_date')
                    ->label('Check-in')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_out_date')
                    ->label('Check-out')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('number_of_guests')
                    ->label('Guests')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => ReservationStatus::PENDING->value,
                        'success' => ReservationStatus::CONFIRMED->value,
                        'info' => ReservationStatus::CHECKED_IN->value,
                        'success' => ReservationStatus::CHECKED_OUT->value,
                        'danger' => ReservationStatus::CANCELLED->value,
                        'danger' => ReservationStatus::NO_SHOW->value,
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(ReservationStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),

                Tables\Filters\SelectFilter::make('hotel_id')
                    ->label('Hotel')
                    ->relationship('hotel', 'name'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ReservationRoomsRelationManager::class,
            BillsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value])) {
            $assignedHotelIds = $user->userHotels->pluck('hotel_id');

            return parent::getEloquentQuery()
                ->whereIn('hotel_id', $assignedHotelIds);
        } elseif ($user->hasRole(UserRoleType::SUPER_ADMIN->value)) {
            return parent::getEloquentQuery();
        } elseif ($user->hasRole(UserRoleType::TRAVEL_COMPANY->value)) {
            return parent::getEloquentQuery()
                ->where('user_id', $user->id);
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
