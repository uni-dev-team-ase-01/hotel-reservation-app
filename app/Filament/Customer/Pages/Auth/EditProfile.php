<?php

namespace App\Filament\Customer\Pages\Auth;

use App\Services\StripeService;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected ?string $customerPortalUrl = null;
    protected StripeService $stripeService;

    public function boot(): void
    {
        $this->stripeService = app(StripeService::class);
    }

    public function mount(): void
    {
        parent::mount();
        $this->initializeStripeData();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
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

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone Number')
            ->tel()
            ->maxLength(20);
    }

    protected function initializeStripeData(): void
    {
        $user = auth()->user();

        // Ensure user has Stripe customer ID
        $this->stripeService->ensureCustomer($user);

        // Generate customer portal URL
        $this->generateCustomerPortalUrl();
    }

    protected function generateCustomerPortalUrl(): void
    {
        if ($this->customerPortalUrl) {
            return;
        }

        $user = auth()->user();
        $this->customerPortalUrl = $this->stripeService->generateCustomerPortalUrl(
            $user,
            url('/customer/profile')
        );
    }

    public function getCustomerPortalUrl(): ?string
    {
        if (!$this->customerPortalUrl) {
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

        // Update Stripe customer if email or name changed
        if ($originalEmail !== $user->email || $originalName !== $user->name) {
            $this->stripeService->updateCustomer($user);
        }

        // Refresh customer portal URL
        $this->customerPortalUrl = null;
        $this->generateCustomerPortalUrl();
    }
}
