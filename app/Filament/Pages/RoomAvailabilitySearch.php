<?php

namespace App\Filament\Pages;

use App\Enum\RateType;
use App\Enum\ReservationStatus;
use App\Enum\RoomType;
use App\Enum\UserRoleType;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\DurationService;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
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

    public $roomCategory;

    public $selectedRooms = [];

    public $showResults = false;

    public $data = [];

    public $searchType; // customer or travel company

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

        $this->roomCategory = request()->get('booking_room_type') === 'block_booking' ? 'standard' : request()->get('booking_room_type');
        $this->searchType = request()->get('search_type');

        if (request()->has('checkin_date')) {
            $this->data['checkInDate'] = request()->get('checkin_date');
        }

        if (request()->has('checkout_date')) {
            $this->data['checkOutDate'] = request()->get('checkout_date');
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

                DatePicker::make('checkInDate')
                    ->label('Check-in Date')
                    ->required()
                    ->minDate(now())
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y')
                    ->format('Y-m-d')
                    ->native(false)
                    ->afterStateUpdated(fn() => $this->showResults = false),

                DatePicker::make('checkOutDate')
                    ->label('Check-out Date')
                    ->required()
                    ->after('checkInDate')
                    ->live()
                    ->seconds(false)
                    ->displayFormat('M j, Y')
                    ->format('Y-m-d')
                    ->native(false)
                    ->afterStateUpdated(fn() => $this->showResults = false),

                TextInput::make('numberOfGuests')
                    ->label('Number of Guests')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(20),

                Select::make('roomCategory')
                    ->label('Room Category')
                    ->options([
                        'standard' => 'Standard Rooms',
                        'residential' => 'Residential Rooms',
                    ])
                    ->required()
                    ->default('standard')
                    ->placeholder('Any room Category')
                    ->afterStateHydrated(function ($state, $set) {
                        if (is_null($state)) {
                            $set('roomCategory', 'standard');
                        }

                        if ($this->roomCategory) {
                            $set('roomCategory', $this->roomCategory);
                        }
                    }),

                Select::make('roomType')
                    ->label('Room Type')
                    ->options(collect(RoomType::cases())->mapWithKeys(fn($case) => [
                        $case->value => $case->getLabel(),
                    ]))
                    ->placeholder('Any room type'),
            ])
            ->columns(6);
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
                    ->color(fn(string $state): string => match ($state) {
                        RoomType::SINGLE->value => 'gray',
                        RoomType::DOUBLE->value => 'info',
                        RoomType::FAMILY->value => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('room_category')
                    ->label('Room Category')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'standard' => 'info',
                        'residential' => 'success',
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
                    ->getStateUsing(function (Room $record) {
                        $durationInDays = DurationService::getDurationInDays(
                            Carbon::parse($this->checkInDate),
                            Carbon::parse($this->checkOutDate)
                        );
                        $rateType = DurationService::getRateTypeByDuration($durationInDays);
                        return $record->getCurrentRate($rateType);
                    }),

                TextColumn::make('total_nights')
                    ->label('Total Nights')
                    ->getStateUsing(fn() => $this->getTotalNights())
                    ->alignCenter(),

                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->money('USD')
                    ->getStateUsing(function (Room $record) {
                        $durationInDays = DurationService::getDurationInDays(
                            Carbon::parse($this->checkInDate),
                            Carbon::parse($this->checkOutDate)
                        );
                        $rateType = DurationService::getRateTypeByDuration($durationInDays);
                        return $record->getCurrentRate($rateType) * $this->getTotalNights();
                    }),
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
                    ->visible(fn(Room $record) => in_array($record->id, $this->selectedRooms))
                    ->action(function (Room $record) {
                        $this->selectedRooms = array_filter(
                            $this->selectedRooms,
                            fn($id) => $id !== $record->id
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
                            ->body(count($records) . ' rooms have been added to your selection.')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('createReservation')
                    ->label('Create Reservation')
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->visible(fn() => ! empty($this->selectedRooms))
                    ->url(function () {
                        $durationInDays = DurationService::getDurationInDays(
                            Carbon::parse($this->checkInDate),
                            Carbon::parse($this->checkOutDate)
                        );

                        $rateType = DurationService::getRateTypeByDuration($durationInDays);
                        $this->data['rate_type'] = $rateType;

                        return route('filament.dashboard.resources.reservations.create', [
                            'rooms' => $this->selectedRooms,
                            'check_in' => $this->checkInDate,
                            'check_out' => $this->checkOutDate,
                            'guests' => $this->numberOfGuests,
                            'hotel_id' => $this->hotelId,
                            'rate_type' => $rateType,
                        ]);
                    }),
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

        if ($this->roomCategory) {
            $query->where('room_category', $this->roomCategory);
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

        $this->checkInDate = Carbon::parse($this->checkInDate)->setTime(14, 0, 0)->format('Y-m-d H:i:s');
        $this->checkOutDate = Carbon::parse($this->checkOutDate)->setTime(12, 0, 0)->format('Y-m-d H:i:s');

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

        return round(Carbon::parse($this->checkInDate)
            ->diffInDays(Carbon::parse($this->checkOutDate)));
    }

    public function getSelectedRoomsCount(): int
    {
        return count($this->selectedRooms);
    }

    public function getInfoMessage()
    {

        $message = null;
        if ($this->searchType === 'travel_company') {
            $message = 'You will received 10% discount for selecting three or more rooms (Special for Travel Company).';
        }

        if ($this->searchType === 'customer' && $this->roomCategory === 'standard') {
            $message = 'For Standard Room, you will charge for daily rates.';
        }

        if ($this->searchType === 'customer' && $this->roomCategory === 'residential') {
            $message = 'For Residential Room, you will charge for weekly and monthly rates.';
        }

        return $message;
    }

    public function getTotalSelectedCost(): array | float
    {
        if (empty($this->selectedRooms)) {
            return 0;
        }

        $durationInDays =  $this->getTotalNights();

        $rateType = RateType::DAILY->value;

        if ($this->roomCategory === 'residential') {
            $rateType = DurationService::getRateTypeByDuration($durationInDays);
        }

        $total =  Room::whereIn('id', $this->selectedRooms)
            ->get()
            ->sum(fn($room) => $room->getCurrentRate($rateType) * $durationInDays);

        $discount = 0;

        if ($this->searchType === 'travel_company' && $this->getSelectedRoomsCount() >= 2) {
            $discount = $total * 0.1;
        }

        return [
            'netTotal' => number_format($total, 0),
            'discount' => number_format($discount, 2),
            'total' => number_format($total - $discount, 2),
        ];
    }

    // TODO: remove this method if not needed
    // public function getCurrentRate($rateType = RateType::DAILY->value)
    // {
    //     return $this->rates()
    //         ->where('rate_type', $rateType)
    //         ->first()?->amount ?? 0;
    // }
}
