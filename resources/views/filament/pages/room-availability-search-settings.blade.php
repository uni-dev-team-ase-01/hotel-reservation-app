<x-filament-panels::page>
    <div class="w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>&nbsp;
                    Search Information
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Select your search type and criteria to find available rooms.
                </p>
            </div>

            <div class="flex flex-col p-6 gap-3">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Type
                    </label>
                    <select wire:model.live="searchType"
                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                        <option value="">Select search type</option>
                        <option value="customer">Customer</option>
                        <option value="travel_company">Travel Company</option>
                    </select>
                </div>

                @if ($searchType === 'customer')
                    <div
                        class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>&nbsp;
                            Customer Booking
                        </h3>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mb-4">
                            Search for available rooms for individual customers. Choose between standard or residential
                            room types.
                        </p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Room Type
                            </label>
                            <select wire:model.live="bookingRoomType"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                                <option value="">Select room type</option>
                                <option value="standard">Standard Rooms</option>
                                <option value="residential">Residential Rooms</option>
                            </select>
                        </div>
                    </div>
                @endif

                @if ($searchType === 'travel_company')
                    <div
                        class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <h3 class="text-lg font-medium text-green-900 dark:text-green-100 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>&nbsp;
                            Travel Company Booking
                        </h3>
                        <p class="text-sm text-green-700 dark:text-green-300 mb-4">
                            Search for available rooms for travel companies. Manage block bookings and group
                            reservations.
                        </p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Booking Type
                            </label>
                            <select wire:model.live="bookingRoomType"
                                class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                                <option value="">Select booking type</option>
                                <option value="block_booking">Block Booking</option>
                            </select>
                        </div>
                    </div>
                @endif

                @if ($bookingRoomType === 'residential')
                    <div
                        class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <h3 class="text-lg font-medium text-purple-900 dark:text-purple-100 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>&nbsp;
                            Residential Booking Period
                        </h3>
                        <p class="text-sm text-purple-700 dark:text-purple-300 mb-4">
                            Select your preferred booking duration for residential rooms. Only weekly or monthly
                            bookings are available.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Booking Duration
                                </label>
                                <select wire:model.live="bookingDuration"
                                    class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                                    <option value="">Select duration</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Booking Duration Units
                                </label>
                                <select wire:model.live="bookingDurationUnits"
                                    class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                                    @if ($bookingDuration === 'weekly')
                                        <option value="">Select Weeks</option>
                                        <option value="1">1 Week</option>
                                        <option value="2">2 Weeks</option>
                                        <option value="3">3 Weeks</option>
                                    @endif

                                    @if ($bookingDuration === 'monthly')
                                        <option value="">Select Months</option>
                                        <option value="1">1 Month</option>
                                        <option value="2">2 Months</option>
                                        <option value="3">3 Months</option>
                                        <option value="4">4 Months</option>
                                        <option value="5">5 Months</option>
                                        <option value="6">6 Months</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Date
                                </label>
                                <input type="date" wire:model.live="startDate"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-white">
                            </div>
                        </div>

                        @if ($bookingDuration && $startDate)
                            <div
                                class="mt-4 p-3 bg-white dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Booking Period:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $startDate }} -
                                        @if ($bookingDuration === 'weekly')
                                            {{ date('Y-m-d', strtotime($startDate . ' +' . (int) $bookingDurationUnits . ' week')) }}
                                        @else
                                            {{ date('Y-m-d', strtotime($startDate . ' +' . (int) $bookingDurationUnits . ' month')) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($this->canSearch())
                    <div class="flex justify-center">
                        <x-filament::button wire:click="search" size="lg">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-2" />
                                Next
                            </div>
                        </x-filament::button>

                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
