<?php

namespace App\Filament\Pages;

use App\Enum\RateType;
use App\Enum\ReservationStatus;
use App\Enum\RoomType;
use App\Enum\UserRoleType;
use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoomAvailabilitySearch extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static string $view = 'filament.pages.room-availability-search';

    protected static ?string $title = 'Search Available Rooms';

    protected static ?string $navigationGroup = 'Reservations';

    public $checkInDate;

    public $checkOutDate;

    public $numberOfGuests = 1;

    public $hotelId;

    public $roomType;

    public $selectedRooms = [];

    public $showResults = false;

    public $data = [];

    public function mount(): void
    {
        $this->checkInDate = now()->format('Y-m-d');
        $this->checkOutDate = now()->addDay()->format('Y-m-d');

        $user = auth()->guard('admin')->user();
        $isHotelStaff = $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);

        if ($isHotelStaff) {
            $userHotels = $user->userHotels->pluck('hotel_id');
            if ($userHotels->count() === 1) {
                $this->data['hotelId'] = $userHotels->first();
            }
        }

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        $user = auth()->guard('admin')->user();
        $isHotelStaff = $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
        $userHotels = $isHotelStaff ? $user->userHotels->pluck('hotel_id') : collect();

        return $form
            ->schema([
                Select::make('hotelId')
                    ->label('Hotel')
                    ->options(function () use ($isHotelStaff, $userHotels) {
                        if ($isHotelStaff) {
                            return Hotel::whereIn('id', $userHotels)->pluck('name', 'id');
                        }

                        return Hotel::pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->disabled($isHotelStaff && $userHotels->count() === 1),

                DateTimePicker::make('checkInDate')
                    ->label('Check-in Date')
                    ->required()
                    ->minDate(now())
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->native(false)
                    ->afterStateUpdated(fn () => $this->showResults = false),

                DateTimePicker::make('checkOutDate')
                    ->label('Check-out Date')
                    ->required()
                    ->after('checkInDate')
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->native(false)
                    ->afterStateUpdated(fn () => $this->showResults = false),

                TextInput::make('numberOfGuests')
                    ->label('Number of Guests')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(20),

                Select::make('roomType')
                    ->label('Room Type')
                    ->options(collect(RoomType::cases())->mapWithKeys(fn ($case) => [
                        $case->value => $case->getLabel(),
                    ]))
                    ->placeholder('Any room type'),
            ])
            ->columns(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getAvailableRoomsQuery())
            ->columns([
                TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room_type')
                    ->label('Room Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'deluxe' => 'info',
                        'suite' => 'success',
                        'presidential' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('occupancy')
                    ->label('Max Occupancy')
                    ->alignCenter(),

                TextColumn::make('location')
                    ->label('Location')
                    ->limit(30),

                TextColumn::make('current_rate')
                    ->label('Rate/Night')
                    ->money('USD')
                    ->getStateUsing(fn (Room $record) => $record->getCurrentRate()),

                TextColumn::make('total_nights')
                    ->label('Total Nights')
                    ->getStateUsing(fn () => $this->getTotalNights())
                    ->alignCenter(),

                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->money('USD')
                    ->getStateUsing(fn (Room $record) => $record->getCurrentRate() * $this->getTotalNights()),
            ])
            ->actions([
                Action::make('select')
                    ->label('Select Room')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->action(function (Room $record) {
                        if (! in_array($record->id, $this->selectedRooms)) {
                            $this->selectedRooms[] = $record->id;

                            Notification::make()
                                ->title('Room Selected')
                                ->body("Room {$record->room_number} has been added to your selection.")
                                ->success()
                                ->send();
                        }
                    }),

                Action::make('remove')
                    ->label('Remove')
                    ->icon('heroicon-o-minus')
                    ->color('danger')
                    ->visible(fn (Room $record) => in_array($record->id, $this->selectedRooms))
                    ->action(function (Room $record) {
                        $this->selectedRooms = array_filter(
                            $this->selectedRooms,
                            fn ($id) => $id !== $record->id
                        );

                        Notification::make()
                            ->title('Room Removed')
                            ->body("Room {$record->room_number} has been removed from your selection.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('selectMultiple')
                    ->label('Reserve Selected Rooms')
                    ->icon('heroicon-o-check')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if (! in_array($record->id, $this->selectedRooms)) {
                                $this->selectedRooms[] = $record->id;
                            }
                        }

                        Notification::make()
                            ->title('Rooms Selected')
                            ->body(count($records).' rooms have been added to your selection.')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('createReservation')
                    ->label('Create Reservation')
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->visible(fn () => ! empty($this->selectedRooms))
                    ->url(fn () => route('filament.dashboard.resources.reservations.create', [
                        'rooms' => $this->selectedRooms,
                        'check_in' => $this->checkInDate,
                        'check_out' => $this->checkOutDate,
                        'guests' => $this->numberOfGuests,
                        'hotel_id' => $this->hotelId,
                    ])),
            ])
            ->emptyStateHeading('No Available Rooms')
            ->emptyStateDescription('No rooms are available for the selected dates and criteria.')
            ->emptyStateIcon('heroicon-o-home');
    }

    public static function canAccess(): bool
    {
        $user = auth()->guard('admin')->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value, UserRoleType::TRAVEL_COMPANY->value]);
    }

    protected function getAvailableRoomsQuery()
    {
        $query = Room::query();

        if (! $this->showResults) {
            return $query->whereRaw('1 = 0');
        }

        if (! $this->checkInDate || ! $this->checkOutDate) {
            return $query->whereRaw('1 = 0');
        }

        $query->with(['hotel', 'rates']);

        if ($this->hotelId) {
            $query->where('hotel_id', $this->hotelId);
        }

        // TODO: need test this logic
        if ($this->numberOfGuests && $this->numberOfGuests > 0) {
            $query->where('occupancy', '>=', $this->numberOfGuests);
        }

        if ($this->roomType) {
            $query->where('room_type', $this->roomType);
        }

        // TODO: need test this logic
        $query->whereDoesntHave('reservations', function ($reservationQuery) {
            $reservationQuery->whereNotIn('status', [ReservationStatus::CANCELLED, ReservationStatus::NO_SHOW, ReservationStatus::CHECKED_OUT])
                ->where(function ($dateQuery) {
                    $dateQuery->where('check_in_date', '<', $this->checkOutDate)
                        ->where('check_out_date', '>', $this->checkInDate);
                });
        });

        return $query;
    }

    public function searchRooms()
    {
        $this->validate([
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'numberOfGuests' => 'required|integer|min:1',
        ]);

        $this->showResults = true;
        $this->selectedRooms = [];

        $totalRooms = Room::count();
        $availableRooms = $this->getAvailableRoomsQuery()->count();

        $this->resetTable();

        \Filament\Notifications\Notification::make()
            ->title('Search Completed')
            ->body("Found {$availableRooms} available rooms out of {$totalRooms} total rooms.")
            ->success()
            ->send();
    }

    protected function getTotalNights(): int
    {
        if (! $this->checkInDate || ! $this->checkOutDate) {
            return 0;
        }

        return Carbon::parse($this->checkInDate)
            ->diffInDays(Carbon::parse($this->checkOutDate));
    }

    public function getSelectedRoomsCount(): int
    {
        return count($this->selectedRooms);
    }

    public function getTotalSelectedCost(): float
    {
        if (empty($this->selectedRooms)) {
            return 0;
        }

        return Room::whereIn('id', $this->selectedRooms)
            ->get()
            ->sum(fn ($room) => $room->getCurrentRate() * $this->getTotalNights());
    }

    // TODO: remove this method if not needed
    // public function getCurrentRate($rateType = RateType::DAILY->value)
    // {
    //     return $this->rates()
    //         ->where('rate_type', $rateType)
    //         ->first()?->amount ?? 0;
    // }
}
