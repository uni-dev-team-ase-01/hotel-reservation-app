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
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary" id="room-type-display">Standard Room</span>
                                    <button class="btn btn-sm btn-outline-secondary" id="change-selection-btn">
                                        Change Selection
                                    </button>
                                </div>
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

        <!-- Room Type Selection Modal START -->
        <div class="modal fade" id="roomTypeModal" tabindex="-1" aria-labelledby="roomTypeModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="roomTypeModalLabel">Select Room Type & Dates</h5>
                    </div>
                    <div class="modal-body">
                        <!-- Room Type Selection Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card room-type-card border-2 cursor-pointer" data-room-type="standard">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-house fs-1 text-primary"></i>
                                        </div>
                                        <h5 class="card-title mb-1">Standard Room</h5>
                                        <p class="text-muted small mb-0">Daily booking available</p>
                                        <div class="room-type-indicator mt-2" style="display: none;">
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card room-type-card border-2 cursor-pointer" data-room-type="suite">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-building fs-1 text-warning"></i>
                                        </div>
                                        <h5 class="card-title mb-1">Suite Room</h5>
                                        <p class="text-muted small mb-0">Monthly booking with discounts</p>
                                        <div class="room-type-indicator mt-2" style="display: none;">
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Selection Form -->
                        <form id="room-selection-form">
                            <!-- Standard Room Date Selection -->
                            <div id="standard-dates-section">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="standard_checkin" class="form-label">Check-in Date</label>
                                        <input type="text" class="form-control flatpickr-standard" id="standard_checkin"
                                            name="standard_checkin" placeholder="Select check-in date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="standard_checkout" class="form-label">Check-out Date</label>
                                        <input type="text" class="form-control flatpickr-standard" id="standard_checkout"
                                            name="standard_checkout" placeholder="Select check-out date" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Suite Room Date Selection -->
                            <div id="suite-dates-section" style="display: none;">
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    For suite bookings, only month and year selection is allowed.
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="suite_month" class="form-label">Month</label>
                                        <select class="form-select" id="suite_month" name="suite_month">
                                            <option value="">Select month</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="suite_year" class="form-label">Year</label>
                                        <select class="form-select" id="suite_year" name="suite_year">
                                            <option value="">Select year</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="suite-discount-info" class="alert alert-success" style="display: none;">
                                    <i class="bi bi-percent me-2"></i>
                                    <strong>Special Discount Applied!</strong> Monthly suite bookings get 15% off standard rates.
                                </div>
                            </div>

                            <!-- Guest Information -->
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="modal-submit-button">
                            <span class="spinner-border spinner-border-sm me-2" style="display: none;" id="submit-spinner"></span>
                            View Available Rooms
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Room Type Selection Modal END -->

        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="validation-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    <strong class="me-auto">Validation Error</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toast-message">
                    <!-- Dynamic message will be inserted here -->
                </div>
            </div>
        </div>
    </main>
@endsection

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .room-type-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .room-type-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .room-type-card.selected {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hotelId = @json($hotel->id);
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1;
            
            // Global variables
            let selectedRoomType = 'standard';
            let checkIn = null;
            let checkOut = null;
            let adults = '2';
            let children = '0';
            let num_rooms = '1';
            let selectedRooms = [];

            // DOM elements
            const roomTypeModal = new bootstrap.Modal(document.getElementById('roomTypeModal'));
            const validationToast = new bootstrap.Toast(document.getElementById('validation-toast'));
            const toastMessage = document.getElementById('toast-message');
            const submitSpinner = document.getElementById('submit-spinner');
            const modalSubmitButton = document.getElementById('modal-submit-button');
            const roomTypeDisplay = document.getElementById('room-type-display');
            const changeSelectionBtn = document.getElementById('change-selection-btn');
            
            // Form elements
            const roomTypeCards = document.querySelectorAll('.room-type-card');
            const standardDatesSection = document.getElementById('standard-dates-section');
            const suiteDatesSection = document.getElementById('suite-dates-section');
            const suiteDiscountInfo = document.getElementById('suite-discount-info');
            
            // Date inputs
            const standardCheckinInput = document.getElementById('standard_checkin');
            const standardCheckoutInput = document.getElementById('standard_checkout');
            const suiteMonthSelect = document.getElementById('suite_month');
            const suiteYearSelect = document.getElementById('suite_year');
            
            // Other inputs
            const modalAdultsInput = document.getElementById('modal_adults');
            const modalChildrenInput = document.getElementById('modal_children');
            
            // Display elements
            const checkinDisplay = document.getElementById('checkin-display');
            const checkoutDisplay = document.getElementById('checkout-display');
            const roomsList = document.getElementById('rooms-list');
            const roomsLoading = document.getElementById('rooms-loading');
            const priceSummary = document.getElementById('price-summary');
            const continueBtn = document.getElementById('continue-booking-btn');

            // Initialize Flatpickr for standard rooms
            const standardFlatpickr = flatpickr('.flatpickr-standard', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'D, M j, Y',
                minDate: 'today',
                maxDate: new Date(currentYear, 11, 31), // End of current year
                mode: 'single'
            });

            // Populate suite year options
            function populateSuiteYears() {
                suiteYearSelect.innerHTML = '<option value="">Select year</option>';
                const nextYear = currentYear + 1;
                suiteYearSelect.innerHTML += `<option value="${currentYear}">${currentYear}</option>`;
                suiteYearSelect.innerHTML += `<option value="${nextYear}">${nextYear}</option>`;
            }

            // Show validation toast
            function showValidationToast(message) {
                toastMessage.textContent = message;
                validationToast.show();
            }

            // Room type selection handler
            roomTypeCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selection from all cards
                    roomTypeCards.forEach(c => {
                        c.classList.remove('selected');
                        c.querySelector('.room-type-indicator').style.display = 'none';
                    });
                    
                    // Select current card
                    this.classList.add('selected');
                    this.querySelector('.room-type-indicator').style.display = 'block';
                    
                    // Update selected room type
                    selectedRoomType = this.dataset.roomType;
                    
                    // Toggle date sections
                    if (selectedRoomType === 'standard') {
                        standardDatesSection.style.display = 'block';
                        suiteDatesSection.style.display = 'none';
                        suiteDiscountInfo.style.display = 'none';
                    } else {
                        standardDatesSection.style.display = 'none';
                        suiteDatesSection.style.display = 'block';
                        suiteDiscountInfo.style.display = 'block';
                    }
                    
                    // Clear previous date selections
                    resetDateInputs();
                });
            });

            // Reset date inputs
            function resetDateInputs() {
                standardFlatpickr.forEach(fp => fp.clear());
                suiteMonthSelect.value = '';
                suiteYearSelect.value = '';
            }

            // Validate standard room dates
            function validateStandardDates() {
                const checkinValue = standardCheckinInput.value;
                const checkoutValue = standardCheckoutInput.value;
                
                if (!checkinValue || !checkoutValue) {
                    showValidationToast('Check-in and check-out dates are required.');
                    return false;
                }
                
                const checkinDate = new Date(checkinValue);
                const checkoutDate = new Date(checkoutValue);
                const startOfYear = new Date(currentYear, 0, 1);
                const endOfYear = new Date(currentYear, 11, 31);
                
                if (checkinDate < startOfYear || checkinDate > endOfYear || 
                    checkoutDate < startOfYear || checkoutDate > endOfYear) {
                    showValidationToast('Please select a date within the current year.');
                    return false;
                }
                
                if (checkoutDate <= checkinDate) {
                    showValidationToast('Check-out date must be after check-in date.');
                    return false;
                }
                
                return true;
            }

            // Validate suite room dates
            function validateSuiteDates() {
                const monthValue = suiteMonthSelect.value;
                const yearValue = suiteYearSelect.value;
                
                if (!monthValue || !yearValue) {
                    showValidationToast('Please select a valid month and year for suite bookings.');
                    return false;
                }
                
                const selectedMonth = parseInt(monthValue);
                const selectedYear = parseInt(yearValue);
                
                // Check if it's a future month
                if (selectedYear === currentYear && selectedMonth <= currentMonth) {
                    showValidationToast('Please select a future month within the current or next year.');
                    return false;
                }
                
                return true;
            }

            // Validate adults count
            function validateGuestCount() {
                const adultsValue = parseInt(modalAdultsInput.value);
                if (adultsValue < 1) {
                    showValidationToast('At least one adult is required.');
                    return false;
                }
                return true;
            }

            // Update displays after successful validation
            function updateDisplaysAndFetchRooms() {
                if (selectedRoomType === 'standard') {
                    checkIn = standardCheckinInput.value;
                    checkOut = standardCheckoutInput.value;
                    roomTypeDisplay.textContent = 'Standard Room';
                } else {
                    const monthName = suiteMonthSelect.options[suiteMonthSelect.selectedIndex].text;
                    const year = suiteYearSelect.value;
                    checkIn = `${year}-${suiteMonthSelect.value.padStart(2, '0')}-01`;
                    checkOut = new Date(year, suiteMonthSelect.value, 0).toISOString().split('T')[0]; // Last day of month
                    roomTypeDisplay.textContent = `Suite Room (${monthName} ${year})`;
                }
                
                adults = modalAdultsInput.value;
                children = modalChildrenInput.value;
                num_rooms = document.getElementById('modal_num_rooms').value;
                
                // Update displays
                checkinDisplay.textContent = checkIn;
                checkoutDisplay.textContent = checkOut;
                
                // Update URL
                const urlParams = new URLSearchParams();
                urlParams.set('check_in', checkIn);
                urlParams.set('check_out', checkOut);
                urlParams.set('adults', adults);
                urlParams.set('children', children);
                urlParams.set('num_rooms', num_rooms);
                urlParams.set('room_type', selectedRoomType);
                
                history.pushState(null, '', window.location.pathname + '?' + urlParams.toString());
                
                // Close modal and fetch rooms
                roomTypeModal.hide();
                fetchAvailableRooms();
            }

            // Modal submit handler
            modalSubmitButton.addEventListener('click', function() {
                submitSpinner.style.display = 'inline-block';
                modalSubmitButton.disabled = true;
                
                setTimeout(() => {
                    let isValid = true;
                    
                    // Validate based on room type
                    if (selectedRoomType === 'standard') {
                        isValid = validateStandardDates();
                    } else {
                        isValid = validateSuiteDates();
                    }
                    
                    // Validate guest count
                    if (isValid) {
                        isValid = validateGuestCount();
                    }
                    
                    if (isValid) {
                        updateDisplaysAndFetchRooms();
                    }
                    
                    submitSpinner.style.display = 'none';
                    modalSubmitButton.disabled = false;
                }, 500);
            });

            // Change selection button handler
            changeSelectionBtn.addEventListener('click', function() {
                roomTypeModal.show();
            });

            // Fetch available rooms function
            async function fetchAvailableRooms() {
                roomsLoading.style.display = 'block';
                roomsList.innerHTML = '';
                priceSummary.innerHTML = `<div class="alert alert-secondary">Select rooms to view price summary.</div>`;
                continueBtn.style.display = 'none';

                let url = `/hotel/${hotelId}/available-rooms`;
                const currentParams = new URLSearchParams();
                if (checkIn) currentParams.set('check_in', checkIn);
                if (checkOut) currentParams.set('check_out', checkOut);
                if (adults) currentParams.set('adults', adults);
                if (children) currentParams.set('children', children);
                currentParams.set('room_type', selectedRoomType);

                const queryString = currentParams.toString();
                if (queryString) url += `?${queryString}`;

                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    roomsLoading.style.display = 'none';

                    const roomData = data.data || [];
                    if (roomData.length === 0) {
                        roomsList.innerHTML = `<div class="alert alert-warning">No rooms available for the selected criteria.</div>`;
                        return;
                    }

                    selectedRooms = [];
                    updatePriceSummary();

                    roomData.forEach((room) => {
                        const card = document.createElement('div');
                        card.className = 'card border bg-transparent p-3 mb-3 room-selection-card';
                        card.dataset.roomId = room.id;
                        card.dataset.roomRate = room.daily_rate || 0;
                        card.dataset.roomType = room.room_type || 'N/A';
                        card.dataset.roomOccupancy = room.occupancy || 1;

                        const imageUrl = room.image ? `/${room.image}` : '/assets/images/category/hotel/4by3/0default.jpg';
                        
                        // Apply suite discount if applicable
                        let displayRate = parseFloat(room.daily_rate || 0);
                        let discountText = '';
                        if (selectedRoomType === 'suite') {
                            displayRate = displayRate * 0.85; // 15% discount
                            discountText = `<span class="badge bg-success ms-2">15% OFF</span>`;
                        }

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
                                            <div class="d-flex align-items-center text-success">
                                                <div>
                                                    <h6 class="h5 mb-0 text-success">${displayRate.toFixed(2)}</h6>
                                                    <span class="fw-light">/per ${selectedRoomType === 'suite' ? 'month' : 'night'}</span>
                                                </div>
                                                ${discountText}
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-dark mb-0 select-room-toggle-btn"
                                                data-room-id="${room.id}"
                                                data-room-rate="${displayRate}">
                                                Select
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        roomsList.appendChild(card);
                    });

                    // Add event listeners to select buttons
                    document.querySelectorAll('.select-room-toggle-btn').forEach((btn) => {
                        btn.onclick = function () {
                            const roomId = parseInt(this.dataset.roomId);
                            const roomRate = parseFloat(this.dataset.roomRate);
                            const card = this.closest('.room-selection-card');

                            const index = selectedRooms.findIndex((r) => r.id === roomId);
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
                    roomsList.innerHTML = `<div class="alert alert-danger">Error loading rooms. Please try again.</div>`;
                }
            }

            // Update price summary
            function updatePriceSummary() {
                if (!checkIn || !checkOut) {
                    priceSummary.innerHTML = `<div class="alert alert-warning">Please select dates to view pricing.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                if (selectedRooms.length === 0) {
                    priceSummary.innerHTML = `<div class="alert alert-secondary">Select rooms to view price summary.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                let duration, durationText;
                if (selectedRoomType === 'standard') {
                    duration = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
                    durationText = `${duration} night${duration > 1 ? 's' : ''}`;
                } else {
                    duration = 1; // Suite is monthly rate
                    durationText = '1 month';
                }

                if (duration <= 0) {
                    priceSummary.innerHTML = `<div class="alert alert-danger">Invalid date selection.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                let total = selectedRooms.reduce((sum, r) => sum + (r.rate * duration), 0);
                let roomDetails = selectedRooms.map((r, i) => `
                    <li class="list-group-item px-2 d-flex justify-content-between">
                        <span class="h6 fw-light mb-0">Room ${i + 1}</span>
                        <span class="h6 fw-light mb-0">${(r.rate * duration).toFixed(2)}</span>
                    </li>
                `).join('');

                priceSummary.innerHTML = `
                    <ul class="list-group list-group-borderless mb-3">
                        ${roomDetails}
                        <li class="list-group-item px-2 d-flex justify-content-between">
                            <span class="h6 fw-light mb-0">Duration</span>
                            <span class="h6 fw-light mb-0">${durationText}</span>
                        </li>
                        <li class="list-group-item px-2 d-flex justify-content-between">
                            <span class="h6 fw-light mb-0">Guests</span>
                            <span class="h6 fw-light mb-0">${adults} Adult${adults > 1 ? 's' : ''}${children > 0 ? `, ${children} Child${children > 1 ? 'ren' : ''}` : ''}</span>
                        </li>
                        <li class="list-group-item px-2 d-flex justify-content-between">
                            <span class="h6 fw-light mb-0">Room Type</span>
                            <span class="h6 fw-light mb-0">${selectedRoomType === 'suite' ? 'Suite' : 'Standard'}</span>
                        </li>
                        ${selectedRoomType === 'suite' ? `
                        <li class="list-group-item px-2 d-flex justify-content-between text-success">
                            <span class="h6 fw-light mb-0">Monthly Discount</span>
                            <span class="h6 fw-light mb-0">-15%</span>
                        </li>
                        ` : ''}
                        <li class="list-group-item bg-light d-flex justify-content-between rounded-2 px-2 mt-2">
                            <span class="h5 fw-normal mb-0 ps-1">Total</span>
                            <span class="h5 fw-normal mb-0">${total.toFixed(2)}</span>
                        </li>
                    </ul>
                `;

                continueBtn.style.display = 'block';
                const selectedRoomIds = selectedRooms.map((r) => r.id).join(',');
                const bookingParams = new URLSearchParams({
                    check_in: checkIn,
                    check_out: checkOut,
                    adults: adults,
                    children: children,
                    num_rooms: selectedRooms.length,
                    room_type: selectedRoomType
                });
                continueBtn.href = `/hotel/${hotelId}/rooms/${selectedRoomIds}/book?${bookingParams.toString()}`;
            }

            // Initialize the application
            function initializeApp() {
                // Populate suite years
                populateSuiteYears();
                
                // Check URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const urlCheckIn = urlParams.get('check_in');
                const urlCheckOut = urlParams.get('check_out');
                const urlRoomType = urlParams.get('room_type');
                
                // Pre-fill form if URL parameters exist
                if (urlCheckIn && urlCheckOut && urlRoomType) {
                    if (urlRoomType === 'suite') {
                        selectedRoomType = 'suite';
                        const date = new Date(urlCheckIn);
                        suiteMonthSelect.value = (date.getMonth() + 1).toString();
                        suiteYearSelect.value = date.getFullYear().toString();
                    } else {
                        selectedRoomType = 'standard';
                        standardCheckinInput.value = urlCheckIn;
                        standardCheckoutInput.value = urlCheckOut;
                    }
                    
                    adults = urlParams.get('adults') || '2';
                    children = urlParams.get('children') || '0';
                    num_rooms = urlParams.get('num_rooms') || '1';
                    
                    modalAdultsInput.value = adults;
                    modalChildrenInput.value = children;
                    
                    // Update displays immediately
                    checkIn = urlCheckIn;
                    checkOut = urlCheckOut;
                    checkinDisplay.textContent = checkIn;
                    checkoutDisplay.textContent = checkOut;
                    roomTypeDisplay.textContent = selectedRoomType === 'suite' ? 'Suite Room' : 'Standard Room';
                    
                    // Fetch rooms without showing modal
                    fetchAvailableRooms();
                } else {
                    // Show modal for first-time visitors
                    // Select standard room by default
                    document.querySelector('[data-room-type="standard"]').click();
                    roomTypeModal.show();
                }
            }

            // Hotel image slider initialization
            function initializeSlider() {
                if (window.tns && document.querySelector('#hotel-slider .tiny-slider-inner')) {
                    tns({
                        container: '#hotel-slider .tiny-slider-inner',
                        items: 1,
                        slideBy: 1,
                        autoplay: false,
                        controls: true,
                        nav: false,
                    });
                }
            }

            // Initialize everything
            initializeApp();
            initializeSlider();
        });
    </script>
@endpush