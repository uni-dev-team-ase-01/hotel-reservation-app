<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\UserRoleType;
use App\Filament\Resources\UserResource;
use App\Services\UserService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $userService = new UserService();
            return $userService->createUser($data, $data['role']);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Customer Creation Failed')
                ->danger()
                ->body('An error occurred while creating the customer: ' . $e->getMessage())
                ->send();

            throw $e;
        }
    }
}
