<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class RoomAvailabilitySearchSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $title = 'Search Available Rooms';

    protected static ?string $navigationGroup = 'Reservations';

    protected static string $view = 'filament.pages.room-availability-search-settings';

    public $searchType = '';
    public $bookingRoomType = '';

    public function updatedSearchType()
    {
        $this->bookingRoomType = '';
    }

    public function search()
    {
        if (empty($this->searchType)) {
            Notification::make()
                ->title('Error')
                ->body('Please select a search type')
                ->danger()
                ->send();
            return;
        }

        if ($this->searchType === 'customer' && empty($this->bookingRoomType)) {
            Notification::make()
                ->title('Error')
                ->body('Please select a room type')
                ->danger()
                ->send();
            return;
        }

        $searchParams = [
            'search_type' => $this->searchType,
            'booking_room_type' => $this->bookingRoomType,
        ];


        $queryParams = http_build_query($searchParams);
        $this->redirect(RoomAvailabilitySearch::getUrl() . '?' . $queryParams);
    }

    public function canSearch()
    {
        if ($this->searchType === 'customer') {
            return !empty($this->bookingRoomType);
        } elseif ($this->searchType === 'travel_company') {
            return true;
        }
        return false;
    }
}
