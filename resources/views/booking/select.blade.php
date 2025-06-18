@extends('layouts.app')

@section('title', 'Book Your Stay - Select Hotel, Dates & Rooms')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl"> {{-- Tailwind: Centered container with padding --}}

    {{-- Display validation errors if any --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Oops! Something went wrong.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display session error messages if any (e.g., from backend room availability check) --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form id="bookingSelectionForm" action="{{ route('booking.start') }}" method="POST">
        @csrf

        <!-- Hotel Dropdown -->
        <div class="mb-6">
            <label for="hotelSelect" class="font-semibold block mb-1 text-sm text-gray-700">Choose Hotel</label>
            <select id="hotelSelect" name="hotel_id" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></select>
            {{-- This select will be populated by Choices.js --}}
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="check_in" class="font-semibold block mb-1 text-sm text-gray-700">Check-In Date</label>
                <input type="date" id="check_in" name="check_in" class="w-full border border-gray-300 p-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
            <div>
                <label for="check_out" class="font-semibold block mb-1 text-sm text-gray-700">Check-Out Date</label>
                <input type="date" id="check_out" name="check_out" class="w-full border border-gray-300 p-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
        </div>

        <!-- Rooms Section Header -->
        <div class="mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Available Rooms</h3>
            <p class="text-sm text-gray-600" id="roomsStatusMessage">Please select a hotel and dates to see available rooms.</p>
        </div>

        <!-- Rooms List -->
        {{-- The div for room list from user feedback was: class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mb-6" --}}
        {{-- Applying similar Tailwind classes --}}
        <div id="roomList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Room cards will be dynamically injected here by JavaScript -->
            {{-- Placeholder for when JS is loading or no rooms --}}
            {{-- <p id="roomListPlaceholder" class="text-gray-500 col-span-full">Select hotel and dates to view rooms.</p> --}}
        </div>
        {{-- Hidden input to store selected room IDs --}}
        <div id="selectedRoomsInputContainer">
            {{-- JS will add hidden inputs like <input type="hidden" name="rooms[]" value="roomId"> here --}}
        </div>


        <!-- Price Summary -->
        <div class="bg-gray-100 rounded-lg p-6 shadow mb-6">
            <h3 class="font-bold text-xl text-gray-800 mb-4">Price Summary</h3>
            <ul id="summaryList" class="space-y-2 text-sm text-gray-700">
                {{-- Summary items will be dynamically injected here by JavaScript --}}
                <li id="summaryPlaceholder" class="text-gray-500">Select rooms and dates to see price summary.</li>
            </ul>
            <div class="mt-4 pt-4 border-t border-gray-300">
                <div class="flex justify-between items-center font-bold text-lg text-gray-900">
                    <span>Grand Total:</span>
                    <span>Rs. <span id="grandTotal">0</span></span>
                </div>
            </div>
            <input type="hidden" name="total_price" id="total_price_input" value="0">
        </div>

        <!-- Continue Button -->
        <button id="continueBooking"
            type="submit" {{-- Changed to type submit to submit the form --}}
            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-md disabled:opacity-50 transition duration-150 ease-in-out"
            disabled>
            Continue to Booking
        </button>
    </form>
</div>
@endsection

@push('styles')
{{-- Choices.js CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
    /* Custom styles for selected room card if needed, or use Tailwind classes directly in JS */
    .room-card.selected { /* Example if not using Tailwind classes directly in JS for selection */
        background-color: #ebf8ff; /* Tailwind's blue-50 */
        border-color: #90cdf4;    /* Tailwind's blue-300 */
    }
    /* Ensure date inputs don't look too small with p-3 if base font is small */
    input[type="date"].form-control-lg { /* Example if you want larger date inputs */
        padding: 0.75rem 1rem;
    }
</style>
@endpush

@push('scripts')
{{-- Choices.js JS --}}
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const hotelSelectEl = document.getElementById('hotelSelect');
    const checkInEl = document.getElementById('check_in');
    const checkOutEl = document.getElementById('check_out');
    const roomListEl = document.getElementById('roomList');
    const summaryListEl = document.getElementById('summaryList');
    const grandTotalEl = document.getElementById('grandTotal');
    const totalPriceInputEl = document.getElementById('total_price_input');
    const continueBookingBtn = document.getElementById('continueBooking');
    const roomsStatusMessageEl = document.getElementById('roomsStatusMessage');
    const summaryPlaceholderEl = document.getElementById('summaryPlaceholder');
    const selectedRoomsInputContainerEl = document.getElementById('selectedRoomsInputContainer');

    let selectedRoomsData = []; // Stores full room objects that are selected
    let choicesHotel = null; // To store Choices.js instance for hotel select
    let availableRoomsData = []; // Stores currently available rooms for the selected hotel/dates

    // --- Initialize Hotel Dropdown with Choices.js ---
    if (window.Choices && hotelSelectEl) {
        choicesHotel = new Choices(hotelSelectEl, {
            searchEnabled: true,
            itemSelectText: '',
            placeholderValue: 'Select a hotel',
            allowHTML: false, // Keep false for security unless HTML in options is intended & sanitized
        });
        fetchHotels();
    } else {
        console.error('Choices.js or hotelSelect element not found.');
        if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Error initializing hotel selection.';
    }

    function fetchHotels() {
        if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Loading hotels...';
        fetch("{{ route('hotels.selectOptions') }}") // Using existing endpoint
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok for hotels.');
                return response.json();
            })
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const hotelOptions = data.data.map(hotel => ({
                        value: hotel.id.toString(), // Ensure value is string for Choices.js
                        label: `${hotel.name} - ${hotel.address}`
                    }));
                    choicesHotel.setChoices(
                        [{ value: '', label: 'Select a hotel', placeholder: true, disabled: true }, ...hotelOptions],
                        'value',
                        'label',
                        true // Replace choices
                    );
                    if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Please select a hotel and dates to see available rooms.';
                } else {
                    if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'No hotels available or failed to load hotels.';
                    choicesHotel.setChoices([{ value: '', label: 'No hotels found', disabled: true }], 'value', 'label', true);
                }
            })
            .catch(error => {
                console.error('Error fetching hotels:', error);
                if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Error loading hotels. Please try again.';
                if(choicesHotel) choicesHotel.setChoices([{ value: '', label: 'Error loading hotels', disabled: true }], 'value', 'label', true);
            });
    }

    // --- Event Listeners for Hotel and Dates ---
    if (hotelSelectEl) hotelSelectEl.addEventListener('change', fetchAndRenderRooms);
    if (checkInEl) checkInEl.addEventListener('change', fetchAndRenderRooms);
    if (checkOutEl) checkOutEl.addEventListener('change', fetchAndRenderRooms);

    // --- Fetch and Render Rooms ---
    async function fetchAndRenderRooms() {
        const hotelId = hotelSelectEl.value;
        const checkInDate = checkInEl.value;
        const checkOutDate = checkOutEl.value;

        selectedRoomsData = []; // Clear selected rooms when hotel/dates change
        updateSelectedRoomsInput();
        updateSummary(); // Update summary which will also check button state

        if (!hotelId || !checkInDate || !checkOutDate) {
            roomListEl.innerHTML = ''; // Clear existing rooms
            if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Please select a hotel and both check-in/check-out dates.';
            return;
        }

        const nights = getNights();
        if (nights <= 0) {
            roomListEl.innerHTML = '';
             if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Check-out date must be after check-in date.';
            return;
        }

        if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'Fetching available rooms...';
        roomListEl.innerHTML = '<div class="col-span-full text-center text-gray-500">Loading rooms...</div>'; // Loading state

        try {
            // Construct URL carefully, ensuring hotelId is part of path
            const apiUrl = `{{ url('/api/hotels') }}/${hotelId}/rooms?check_in=${checkInDate}&check_out=${checkOutDate}`;
            const response = await fetch(apiUrl);
            if (!response.ok) {
                 const errorData = await response.json().catch(() => ({message: `Failed to fetch rooms. Status: ${response.status}`}));
                 throw new Error(errorData.message || `HTTP error ${response.status}`);
            }
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                availableRoomsData = data.data;
                renderRoomCards();
                if(roomsStatusMessageEl) roomsStatusMessageEl.style.display = 'none'; // Hide status message
            } else {
                roomListEl.innerHTML = '<div class="col-span-full text-center text-gray-500">No rooms available for the selected criteria.</div>';
                availableRoomsData = [];
                if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = 'No rooms available for the selected criteria.';
                if(roomsStatusMessageEl) roomsStatusMessageEl.style.display = 'block';

            }
        } catch (error) {
            console.error('Error fetching rooms:', error);
            roomListEl.innerHTML = `<div class="col-span-full text-center text-red-500">Error loading rooms: ${error.message}. Please try again.</div>`;
            availableRoomsData = [];
            if(roomsStatusMessageEl) roomsStatusMessageEl.textContent = `Error loading rooms: ${error.message}`;
            if(roomsStatusMessageEl) roomsStatusMessageEl.style.display = 'block';
        }
        updateSummary(); // Recalculate summary (will be 0 if no rooms or error)
    }

    function renderRoomCards() {
        roomListEl.innerHTML = ''; // Clear previous
        if (availableRoomsData.length === 0) {
             roomListEl.innerHTML = '<div class="col-span-full text-center text-gray-500">No rooms to display.</div>';
             return;
        }
        availableRoomsData.forEach(room => {
            const card = document.createElement('div');
            // Using Tailwind classes as per the new HTML structure
            card.className = 'room-card border border-gray-300 rounded-lg p-4 shadow-sm cursor-pointer hover:bg-blue-50 transition duration-150 ease-in-out';
            card.dataset.id = room.id;
            card.dataset.price = room.price_per_night; // API returns price_per_night
            card.dataset.name = room.name; // Store name for summary

            card.innerHTML = `
                <div class="font-semibold text-lg text-gray-800">${room.name}</div>
                <div class="text-sm text-gray-600 mb-1">${room.description || ''}</div>
                <div class="text-md font-medium text-gray-700">Max Guests: ${room.max_guests}</div>
                <div class="text-lg font-semibold text-indigo-600 mt-2">Rs. ${parseFloat(room.price_per_night).toLocaleString()} / night</div>
            `;
            card.addEventListener('click', () => toggleRoomSelection(room, card));
            roomListEl.appendChild(card);
        });
    }

    function toggleRoomSelection(room, cardEl) {
        const roomId = room.id.toString();
        const index = selectedRoomsData.findIndex(r => r.id.toString() === roomId);

        if (index > -1) { // Room is selected, so deselect
            selectedRoomsData.splice(index, 1);
            cardEl.classList.remove('bg-blue-100', 'border-blue-500', 'ring-2', 'ring-blue-500'); // Tailwind for selection
            cardEl.classList.add('border-gray-300');
        } else { // Room is not selected, so select
            selectedRoomsData.push(room); // Store full room object
            cardEl.classList.add('bg-blue-100', 'border-blue-500', 'ring-2', 'ring-blue-500');
            cardEl.classList.remove('border-gray-300');
        }
        updateSelectedRoomsInput();
        updateSummary();
    }

    function updateSelectedRoomsInput() {
        selectedRoomsInputContainerEl.innerHTML = ''; // Clear previous hidden inputs
        selectedRoomsData.forEach(room => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'rooms[]';
            input.value = room.id;
            selectedRoomsInputContainerEl.appendChild(input);
        });
    }


    function getNights() {
        if (!checkInEl.value || !checkOutEl.value) return 0;
        const inDate = new Date(checkInEl.value);
        const outDate = new Date(checkOutEl.value);
        if (outDate <= inDate) return 0; // Invalid date range
        const diff = (outDate - inDate) / (1000 * 60 * 60 * 24);
        return isNaN(diff) || diff < 0 ? 0 : Math.round(diff); // Ensure positive integer
    }

    function updateSummary() {
        const nights = getNights();
        summaryListEl.innerHTML = ''; // Clear previous summary items
        let currentGrandTotal = 0;

        if (nights > 0 && selectedRoomsData.length > 0) {
            if(summaryPlaceholderEl) summaryPlaceholderEl.style.display = 'none';
            selectedRoomsData.forEach(room => {
                const price = parseFloat(room.price_per_night); // API provides price_per_night
                const subtotal = price * nights;
                currentGrandTotal += subtotal;
                const li = document.createElement('li');
                li.className = 'flex justify-between';
                li.innerHTML = `<span>${room.name} (× ${nights} night${nights > 1 ? 's' : ''}):</span><span>Rs. ${subtotal.toLocaleString()}</span>`;
                summaryListEl.appendChild(li);
            });
        } else {
            if(summaryPlaceholderEl) summaryPlaceholderEl.style.display = 'block';
            if (nights === 0 && selectedRoomsData.length > 0) {
                 const li = document.createElement('li');
                 li.className = 'text-red-500';
                 li.textContent = 'Please select valid check-in and check-out dates.';
                 summaryListEl.appendChild(li);
            } else if (selectedRoomsData.length === 0 && nights > 0) {
                 const li = document.createElement('li');
                 li.textContent = 'Please select one or more rooms.';
                 summaryListEl.appendChild(li);
            }
        }

        grandTotalEl.textContent = currentGrandTotal.toLocaleString();
        if(totalPriceInputEl) totalPriceInputEl.value = currentGrandTotal;

        // Enable/disable continue button
        if (continueBookingBtn) {
            continueBookingBtn.disabled = !(hotelSelectEl.value && nights > 0 && selectedRoomsData.length > 0);
        }
    }

    // Initial call to set button state and summary (though nothing is selected)
    updateSummary();
});
</script>
@endpush
