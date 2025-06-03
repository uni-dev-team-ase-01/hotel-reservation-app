<?php

namespace App\Filament\Resources;

use App\Enum\ReservationStatus;
use App\Enum\UserRoleType;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers\BillsRelationManager;
use App\Filament\Resources\ReservationResource\RelationManagers\ReservationRoomsRelationManager;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->relationship(
                        'user',
                        'name',
                        fn ($query) => $query->whereHas('roles', function ($q) {
                            $q->where('name', 'customer');
                        })
                    )
                    ->searchable(),
                Forms\Components\Select::make('hotel_id')
                    ->label('Hotel')
                    ->required()
                    ->relationship(
                        'hotel',
                        'name',
                        // fn ($query) => $query->whereHas('roles', function ($q) {
                        //     $q->where('name', 'customer');
                        // })
                    )
                    ->searchable(),
                Forms\Components\DatePicker::make('check_in_date')
                    ->required(),
                Forms\Components\DatePicker::make('check_out_date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(collect(ReservationStatus::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ])),
                Forms\Components\TextInput::make('number_of_guests')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cancellation_reason')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('cancellation_date'),
                Forms\Components\TextInput::make('confirmation_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('auto_cancelled')
                    ->required(),
                Forms\Components\Toggle::make('no_show_billed')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hotel.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('number_of_guests')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancellation_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cancellation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('confirmation_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('auto_cancelled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('no_show_billed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        }

        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
