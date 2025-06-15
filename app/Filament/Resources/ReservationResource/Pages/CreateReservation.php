<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Enum\UserRoleType;
use App\Filament\Resources\ReservationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole(UserRoleType::TRAVEL_COMPANY->value)) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $roomIds = $data['room_ids'] ?? [];
        unset($data['room_ids']);

        $record = static::getModel()::create($data);

        if (! empty($roomIds)) {
            $record->rooms()->attach($roomIds);
        }

        return $record;
    }
}
