<?php

namespace App\Filament\Resources\TravelCompanyResource\Pages;

use App\Enum\UserRoleType;
use App\Filament\Resources\TravelCompanyResource;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTravelCompany extends EditRecord
{
    protected static string $resource = TravelCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Create user')
                ->icon('heroicon-o-user-plus')
                ->action(function ($record) {
                    try {
                        $data = [
                            'name' => $record->company_name,
                            'email' => $record->email,
                            'phone' => $record->phone,
                        ];
                        $userService = new UserService();
                        $user = $userService->createUser($data, UserRoleType::TRAVEL_COMPANY->value);
                        $record->user()->associate($user);
                        $record->save();
                        $this->form->fill([
                            'user_id' => $user->id,
                        ]);
                        $record->user_id = $user->id;
                        return $user->id;
                    } catch (\Throwable $th) {
                        \Filament\Notifications\Notification::make()
                            ->title('Error Creating User')
                            ->body('There was an error creating the user for this travel company. Please try again later.')
                            ->danger()
                            ->send();

                        logger()->error('Error creating user for travel company', [
                            'travel_company_id' => $record->id,
                            'error' => $th->getMessage(),
                        ]);
                    }
                })
                ->visible(!$this->record->user_id)
                ->requiresConfirmation()
                ->modalHeading('Create User for Travel Company')
                ->modalDescription('You will create a user account for this travel company. This user will be able to manage the travel company details.')
                ->modalSubmitActionLabel('Continue'),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
