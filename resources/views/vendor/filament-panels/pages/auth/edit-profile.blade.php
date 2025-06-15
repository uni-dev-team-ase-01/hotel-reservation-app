<x-dynamic-component
    :component="static::isSimple() ? 'filament-panels::page.simple' : 'filament-panels::page'"
>
    <x-filament::button
        icon="heroicon-m-arrow-left"
        color="secondary"
        size="sm"
        class="mb-4"
        :href="filament()->getCurrentPanel()->getId() === 'customer' ? route('filament.customer.pages.dashboard') : route('filament.dashboard.pages.dashboard')"
        tag="a"
    >
        Back to Dashboard
    </x-filament::button>

    <x-filament-panels::form id="form" wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <div>
        @if (filament()->getCurrentPanel()->getId() === "customer")
            <x-filament::section
                title="Payment Methods"
                description="Manage your payment methods securely through Stripe."
                icon="heroicon-o-credit-card"
                class="mt-6"
            >
                <div class="mt-4">
                    @if ($this->getCustomerPortalUrl())
                        <x-filament::link
                            :href="$this->getCustomerPortalUrl()"
                            {{-- target="_blank" --}}
                            icon="heroicon-o-arrow-top-right-on-square"
                            color="primary"
                            class="inline-flex items-center"
                        >
                            Manage Payment Methods in Stripe Portal
                        </x-filament::link>
                    @else
                        <x-filament::badge
                            color="danger"
                            class="inline-flex items-center gap-1"
                        >
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            Unable to load Stripe Customer Portal. Please try
                            again later.
                        </x-filament::badge>
                    @endif
                </div>
            </x-filament::section>
        @endif
    </div>
</x-dynamic-component>
