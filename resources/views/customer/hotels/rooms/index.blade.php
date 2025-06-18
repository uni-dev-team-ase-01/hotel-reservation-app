@extends("layouts.app")
@section("title", $hotel->name . " - Booking Landing Page")

@section("content")
    <main>
        <section class="pt-4">
            <div class="container">
                <!-- Title -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <h1 class="fs-3">{{ $hotel->name }}</h1>
                        <!-- Location -->
                        <p class="fw-bold mb-0">
                            <i class="bi bi-geo-alt me-2"></i>
                            {{ $hotel->address }}
                        </p>
                    </div>
                </div>

                <!-- Slider START -->
                <div class="tiny-slider arrow-round arrow-blur" id="hotel-slider">
                    <div class="tiny-slider-inner">
                        @foreach ($hotel->gallery_images ?? [] as $img)
                            <div class="tns-item">
                                <a class="w-100 h-100" data-glightbox data-gallery="gallery" href="{{ $img }}">
                                    <div class="card card-element-hover card-overlay-hover overflow-hidden">
                                        <img src="{{ asset($img) }}" class="rounded-3" alt="" />
                                        <div class="hover-element w-100 h-100">
                                            <i
                                                class="bi bi-fullscreen fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-1 p-2 lh-1"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Slider END -->
            </div>
        </section>

        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <!-- Detail START -->
                    <div class="col-xl-7">
                        <div class="card bg-transparent p-0">
                            <div
                                class="card-header bg-transparent border-bottom d-sm-flex justify-content-sm-between align-items-center p-0 pb-3">
                                <h4 class="mb-2 mb-sm-0">Select Rooms</h4>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <!-- Dynamic Loading -->
                                <div id="rooms-loading" style="display: none">
                                    <div class="alert alert-info mb-0">
                                        Loading available rooms...
                                    </div>
                                </div>
                                <div id="rooms-list" class="vstack gap-5"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Detail END -->

                    <!-- Right side content START -->
                    <aside class="col-xl-5 d-none d-xl-block">
                        <div class="card bg-transparent border">
                            <div class="card-header bg-transparent border-bottom">
                                <h4 class="card-title mb-0">Price Summary</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <div class="bg-light py-3 px-4 rounded-3">
                                            <h6 class="fw-light small mb-1">
                                                Check-in
                                            </h6>
                                            <h6 class="mb-0" id="checkin-display">
                                                -
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-light py-3 px-4 rounded-3">
                                            <h6 class="fw-light small mb-1">
                                                Check out
                                            </h6>
                                            <h6 class="mb-0" id="checkout-display">
                                                -
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div id="price-summary">
                                    <div class="alert alert-secondary mb-0">
                                        Please select a room to see the price
                                        summary.
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="#" class="btn btn-dark mb-0" id="continue-booking-btn" style="display: none">
                                        Continue To Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <!-- Right side content END -->
                </div>
            </div>
        </section>

        <!-- Room Details Modal START -->
        <div class="modal fade" id="roomDetailsModal" tabindex="-1" aria-labelledby="roomDetailsModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roomDetailsModalLabel">Please Provide Your Stay Details</h5>
                        {{-- No close button to make it mandatory initially --}}
                    </div>
                    <div class="modal-body">
                        <form id="room-details-modal-form">
                            <div class="mb-3">
                                <label for="modal_date_range" class="form-label">Check-in - Check-out Dates</label>
                                <input type="text" class="form-control flatpickr-modal" id="modal_date_range"
                                    name="modal_date_range" placeholder="Select dates" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modal_adults" class="form-label">Adults (13+)</label>
                                    <input type="number" class="form-control" id="modal_adults" name="modal_adults"
                                        value="2" min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modal_children" class="form-label">Children (0-12)</label>
                                    <input type="number" class="form-control" id="modal_children" name="modal_children"
                                        value="0" min="0" required>
                                </div>
                            </div>
                            <input type="hidden" id="modal_num_rooms" name="modal_num_rooms" value="1">
                            <div class="alert alert-danger d-none mt-2" id="modal-validation-error"></div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="modal-submit-button">View Available Rooms</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Room Details Modal END -->
    </main>
@endsection

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> {{-- Added Flatpickr for modal --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> {{-- Added Flatpickr CSS
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hotelId = @json($hotel->id);
            let urlParams = new URLSearchParams(window.location.search); // Use let for potential reassignment

            // Make these variables updatable from modal
            let checkIn = urlParams.get('check_in');
            let checkOut = urlParams.get('check_out');
            let adults = urlParams.get('adults') || '2'; // Default to string for consistency with form values
            let children = urlParams.get('children') || '0';
            let num_rooms = urlParams.get('num_rooms') || '1';


            const checkinDisplay = document.getElementById('checkin-display');
            const checkoutDisplay = document.getElementById('checkout-display');

            checkinDisplay.textContent = checkIn ?? '-';
            checkoutDisplay.textContent = checkOut ?? '-';

            const roomsList = document.getElementById('rooms-list');
            const roomsLoading = document.getElementById('rooms-loading');
            const priceSummary = document.getElementById('price-summary');
            const continueBtn = document.getElementById('continue-booking-btn');

            const roomDetailsModalElement = document.getElementById('roomDetailsModal');
            const roomDetailsModal = new bootstrap.Modal(roomDetailsModalElement);
            const modalForm = document.getElementById('room-details-modal-form');
            const modalDateRangeInput = document.getElementById('modal_date_range');
            const modalAdultsInput = document.getElementById('modal_adults');
            const modalChildrenInput = document.getElementById('modal_children');
            const modalNumRoomsInput = document.getElementById('modal_num_rooms');
            const modalValidationError = document.getElementById('modal-validation-error');
            const modalSubmitButton = document.getElementById('modal-submit-button');

            let modalFlatpickr = flatpickr(".flatpickr-modal", { // Initialize modal flatpickr
                mode: "range",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                minDate: "today"
            });

            let selectedRooms = [];

            async function fetchAvailableRooms() {
                console.log("Fetching available rooms with:", { checkIn, checkOut, adults, children, num_rooms });
                roomsLoading.style.display = 'block'; // Use block for visibility
                roomsList.innerHTML = '';
                priceSummary.innerHTML = `<div class="alert alert-secondary">Select rooms to view price summary.</div>`; // Reset summary
                continueBtn.style.display = 'none'; // Hide button initially

                let url = `/hotel/${hotelId}/available-rooms`;
                const currentParams = new URLSearchParams(); // Use URLSearchParams for easier construction
                if (checkIn) currentParams.set('check_in', checkIn);
                if (checkOut) currentParams.set('check_out', checkOut);
                if (adults) currentParams.set('adults', adults);
                if (children) currentParams.set('children', children);
                // num_rooms is not directly used by RoomController@availableRooms but can be passed for consistency
                // if (num_rooms) currentParams.set('num_rooms', num_rooms);

                const queryString = currentParams.toString();
                if (queryString) url += `?${queryString}`;

                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    roomsLoading.style.display = 'none';

                    const roomData = data.data || [];
                    if (roomData.length === 0) {
                        roomsList.innerHTML = `<div class="alert alert-warning">No rooms available.</div>`;
                        return;
                    }

                    selectedRooms = []; // Reset selected rooms
                    updatePriceSummary(); // Update summary (will show "select rooms")

                    roomData.forEach((room) => {
                        const card = document.createElement('div');
                        card.className = 'card border bg-transparent p-3 mb-3 room-selection-card';
                        card.dataset.roomId = room.id;
                        card.dataset.roomRate = room.daily_rate || 0;
                        card.dataset.roomType = room.room_type || 'N/A';
                        card.dataset.roomOccupancy = room.occupancy || 1;

                        // Fallback image path
                        const imageUrl = room.image ? `/${room.image}` : '/assets/images/category/hotel/4by3/0default.jpg';

                        card.innerHTML = `
            <div class="row g-3 g-md-4">
                <div class="col-md-4">
                    <img src="${imageUrl}" class="card-img" alt="${room.room_type || 'Room'}">
                </div>
                <div class="col-md-8">
                    <div class="card-body d-flex flex-column p-0 h-100">
                        <h5 class="card-title">${room.room_type || `Room #${room.id}`}</h5>
                        <p class="small mb-0">Max Occupancy: ${room.occupancy}</p>
                        <p class="small mb-2">
                            Beds: ${room.beds_configuration
                                ? JSON.parse(room.beds_configuration).map(b => `${b.count} ${b.type}`).join(', ')
                                : 'N/A'}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="d-flex text-success">
                                <h6 class="h5 mb-0 text-success">$${parseFloat(room.daily_rate || 0).toFixed(2)}</h6>
                                <span class="fw-light ms-1">/per night</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-dark mb-0 select-room-toggle-btn"
                                data-room-id="${room.id}"
                                data-room-rate="${room.daily_rate || 0}">
                                Select
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                        roomsList.appendChild(card);
                    });

                    document.querySelectorAll('.select-room-toggle-btn')
                        .forEach((btn) => {
                            btn.onclick = function () {
                                const roomId = parseInt(this.dataset.roomId);
                                const roomRate = parseFloat(
                                    this.dataset.roomRate,
                                );
                                const card = this.closest(
                                    '.room-selection-card',
                                );

                                const index = selectedRooms.findIndex(
                                    (r) => r.id === roomId,
                                );
                                if (index > -1) {
                                    selectedRooms.splice(index, 1);
                                    card.classList.remove('border-primary');
                                    this.classList.remove('btn-dark');
                                    this.classList.add('btn-outline-dark');
                                    this.textContent = 'Select';
                                } else {
                                    selectedRooms.push({
                                        id: roomId,
                                        rate: roomRate,
                                    });
                                    card.classList.add('border-primary');
                                    this.classList.remove('btn-outline-dark');
                                    this.classList.add('btn-dark');
                                    this.textContent = 'Selected';
                                }

                                updatePriceSummary();
                            };
                        });
                } catch (error) {
                    roomsLoading.style.display = 'none';
                    roomsList.innerHTML = `<div class="alert alert-danger">Error loading rooms.</div>`;
                }
            }

            function updatePriceSummary() {
                if (!checkIn || !checkOut) { // Don't calculate price if dates are missing
                    priceSummary.innerHTML = `<div class="alert alert-warning">Please select check-in and check-out dates.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                if (selectedRooms.length === 0) {
                    priceSummary.innerHTML = `<div class="alert alert-secondary">Select rooms to view price summary.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                const nights = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
                if (nights <= 0) {
                    priceSummary.innerHTML = `<div class="alert alert-danger">Check-out date must be after check-in date.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                let total = selectedRooms.reduce((sum, r) => sum + (r.rate * nights), 0);
                let roomDetails = selectedRooms.map((r, i) => `
                            <li class="list-group-item px-2 d-flex justify-content-between">
                                <span class="h6 fw-light mb-0">Room ${i + 1} (${r.type || 'N/A'})</span>
                                <span class="h6 fw-light mb-0">$${(r.rate * nights).toFixed(2)} (${nights} night${nights > 1 ? 's' : ''})</span>
                            </li>
                        `).join('');

                priceSummary.innerHTML = `
                            <ul class="list-group list-group-borderless mb-3">
                                ${roomDetails}
                                <li class="list-group-item px-2 d-flex justify-content-between">
                                    <span class="h6 fw-light mb-0">Guests</span>
                                    <span class="h6 fw-light mb-0">${adults} Adult${adults > 1 ? 's' : ''}${children > 0 ? `, ${children} Child${children > 1 ? 'ren' : ''}` : ''}</span>
                                </li>
                                 <li class="list-group-item px-2 d-flex justify-content-between">
                                    <span class="h6 fw-light mb-0">Rooms</span>
                                    <span class="h6 fw-light mb-0">${selectedRooms.length}</span>
                                </li>
                                <li class="list-group-item bg-light d-flex justify-content-between rounded-2 px-2 mt-2">
                                    <span class="h5 fw-normal mb-0 ps-1">Total</span>
                                    <span class="h5 fw-normal mb-0">$${total.toFixed(2)}</span>
                                </li>
                            </ul>
                        `;

                continueBtn.style.display = 'block'; // Use block for visibility
                const selectedRoomIds = selectedRooms.map((r) => r.id).join(',');
                const bookingParams = new URLSearchParams({
                    check_in: checkIn,
                    check_out: checkOut,
                    adults: adults,
                    children: children,
                    num_rooms: selectedRooms.length // Pass actual number of selected rooms
                });
                continueBtn.href = `/hotel/${hotelId}/rooms/${selectedRoomIds}/book?${bookingParams.toString()}`;
            }

            modalSubmitButton.addEventListener('click', function () {
                modalValidationError.classList.add('d-none');
                modalValidationError.textContent = '';

                const dateRangeValue = modalDateRangeInput.value;
                const adultsValue = modalAdultsInput.value;
                const childrenValue = modalChildrenInput.value;
                const numRoomsValue = modalNumRoomsInput.value;

                if (!dateRangeValue || !dateRangeValue.includes('to')) {
                    modalValidationError.textContent = 'Please select valid check-in and check-out dates.';
                    modalValidationError.classList.remove('d-none');
                    return;
                }
                if (parseInt(adultsValue) < 1) {
                    modalValidationError.textContent = 'At least one adult is required.';
                    modalValidationError.classList.remove('d-none');
                    return;
                }

                const [newCheckIn, newCheckOut] = dateRangeValue.split(' to ');

                // Update global variables
                checkIn = newCheckIn;
                checkOut = newCheckOut;
                adults = adultsValue;
                children = childrenValue;
                num_rooms = numRoomsValue; // Update num_rooms as well

                // Update main page displays
                checkinDisplay.textContent = checkIn;
                checkoutDisplay.textContent = checkOut;
                // Potentially update guest display if there's one on the main page

                // Update browser URL
                const newUrlParams = new URLSearchParams(window.location.search);
                newUrlParams.set('check_in', checkIn);
                newUrlParams.set('check_out', checkOut);
                newUrlParams.set('adults', adults);
                newUrlParams.set('children', children);
                newUrlParams.set('num_rooms', num_rooms);
                history.pushState(null, '', window.location.pathname + '?' + newUrlParams.toString());

                roomDetailsModal.hide();
                fetchAvailableRooms();
            });


            if (!checkIn || !checkOut) {
                // Pre-fill modal if some data is available from URL, or use defaults
                if (modalFlatpickr[0]) { // Flatpickr might return an array of instances
                    modalFlatpickr[0].setDate(checkIn && checkOut ? [checkIn, checkOut] : []);
                }
                modalAdultsInput.value = adults;
                modalChildrenInput.value = children;
                modalNumRoomsInput.value = num_rooms;
                roomDetailsModal.show();
            } else {
                fetchAvailableRooms();
            }

            // Hotel image slider
            if (
                window.tns &&
                document.querySelector('#hotel-slider .tiny-slider-inner')
            ) {
                tns({
                    container: '#hotel-slider .tiny-slider-inner',
                    items: 1,
                    slideBy: 1,
                    autoplay: false,
                    controls: true,
                    nav: false,
                });
            }
        });
    </script>
@endpush