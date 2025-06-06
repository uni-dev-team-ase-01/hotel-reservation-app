<?php

namespace App\Filament\Customer\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Stripe\StripeClient;

class EditProfile extends BaseEditProfile
{
    protected ?string $customerPortalUrl = null;

    public function mount(): void
    {
        parent::mount();
        $this->ensureStripeCustomer();
        $this->generateCustomerPortalUrl();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                // $this->getUsernameFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Full Name')
            ->maxLength(255)
            ->required();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->maxLength(255)
            ->required()
            ->disabled()
            ->unique(ignoreRecord: true);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->maxLength(255)
            ->unique(ignoreRecord: true);
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone Number')
            ->tel()
            ->maxLength(20);
    }

    protected function ensureStripeCustomer()
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $user = auth()->user();

        if (! $user->stripe_customer_id) {
            try {
                $customer = $stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Failed to create Stripe customer')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }

    protected function generateCustomerPortalUrl()
    {
        if ($this->customerPortalUrl) {
            return;
        }

        $stripe = new StripeClient(config('services.stripe.secret'));
        $user = auth()->user();
        $customerId = $user->stripe_customer_id;

        if ($customerId) {
            try {
                $session = $stripe->billingPortal->sessions->create([
                    'customer' => $customerId,
                    'return_url' => url('/customer/profile'),
                ]);
                $this->customerPortalUrl = $session->url;
            } catch (\Exception $e) {
                $this->customerPortalUrl = null;
                Notification::make()
                    ->title('Failed to generate Stripe Customer Portal link')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
        }
    }

    public function getCustomerPortalUrl()
    {
        if (! $this->customerPortalUrl) {
            $this->generateCustomerPortalUrl();
        }

        return $this->customerPortalUrl;
    }

    public function save(): void
    {
        $user = auth()->user();
        $originalEmail = $user->email;
        $originalName = $user->name;

        parent::save();

        $user->refresh();
        $newEmail = $user->email;
        $newName = $user->name;

        if ($originalEmail !== $newEmail || $originalName !== $newName) {
            $this->updateStripeCustomer($newEmail, $newName);
        }

        $this->customerPortalUrl = null;
        $this->generateCustomerPortalUrl();
    }

    protected function updateStripeCustomer(string $email, string $name): void
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $user = auth()->user();
        $customerId = $user->stripe_customer_id;

        if ($customerId) {
            try {
                $stripe->customers->update($customerId, [
                    'email' => $email,
                    'name' => $name,
                ]);
            } catch (\Exception $e) {

                Notification::make()
                    ->title('Failed to update Stripe customer')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
}
