<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Search Criteria Section -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    Search Criteria
                </div>
            </x-slot>

            <form wire:submit="searchRooms" class="space-y-4">
                {{ $this->form }}

                <div class="flex gap-4 mt-6">
                    <x-filament::button type="submit" size="lg">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-magnifying-glass
                                class="w-4 h-4 mr-2"
                            />
                            Search Available Rooms
                        </div>
                    </x-filament::button>

                    @if ($this->showResults)
                        <x-filament::button
                            type="button"
                            color="gray"
                            wire:click="$set('showResults', false)"
                        >
                            Clear Results
                        </x-filament::button>
                    @endif
                </div>
            </form>
        </x-filament::section>

        <!-- Selected Rooms Summary -->
        @if ($this->getSelectedRoomsCount() > 0)
            <x-filament::section>
                <x-slot name="heading">Selected Rooms Summary</x-slot>

                <div
                    class="bg-success-50 dark:bg-success-900/20 p-4 rounded-lg"
                >
                    <div class="flex justify-between items-center">
                        <div>
                            <p
                                class="text-sm font-medium text-success-800 dark:text-success-200"
                            >
                                {{ $this->getSelectedRoomsCount() }} room(s)
                                selected
                            </p>
                            <p
                                class="text-sm text-success-600 dark:text-success-400"
                            >
                                {{ $this->getTotalNights() }} nights •
                                {{ $this->numberOfGuests }} guest(s)
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-lg font-bold text-success-800 dark:text-success-200"
                            >
                                ${{ number_format($this->getTotalSelectedCost(), 2) }}
                            </p>
                            <p
                                class="text-sm text-success-600 dark:text-success-400"
                            >
                                Total Cost
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        <!-- Available Rooms Section -->
        @if ($this->showResults)
            <x-filament::section>
                <x-slot name="heading">Available Rooms</x-slot>

                {{ $this->table }}
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
