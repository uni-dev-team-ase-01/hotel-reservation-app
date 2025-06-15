<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Enum\UserRoleType;
use App\Filament\Resources\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBill extends EditRecord
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('User Payment Methods')
                ->icon('heroicon-o-credit-card')
                ->action(function ($record) {
                    try {
                        $stripeService = app(\App\Services\StripeService::class);
                        $user = $record->reservation->user;

                        $portalUrl = $stripeService->generateCustomerPortalUrl(
                            $user,
                            route('filament.dashboard.resources.bills.view' , ['record' => $record->id])
                        );

                        if ($portalUrl) {
                            $this->js("window.open('{$portalUrl}', '_blank')");

                            \Filament\Notifications\Notification::make()
                                ->title('Payment Methods')
                                ->body('Opening Stripe Customer Portal for ' . $user->name . '...')
                                ->success()
                                ->send();
                        } else {
                            throw new \Exception('Failed to generate portal URL');
                        }
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Unable to access Payment Methods')
                            ->body('Please try again later or contact support.')
                            ->danger()
                            ->send();

                        logger()->error('Stripe Customer Portal error', [
                            'bill_id' => $record->id ?? null,
                            'user_id' => $record->reservation->user->id ?? null,
                            'error' => $e->getMessage()
                        ]);
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Access Payment Methods')
                ->modalDescription(function ($record) {
                    $userName = $record->reservation->user->name ?? 'Customer';
                    return "You will open Stripe Customer Portal for {$userName} to manage their payment methods and billing information.";
                })
                ->modalSubmitActionLabel('Continue')
                ->visible(function ($record) {
                    return $record->reservation && $record->reservation->user && auth()->user()->hasAnyRole([UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
                }),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
