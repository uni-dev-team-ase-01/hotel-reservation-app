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
                <div
                    class="tiny-slider arrow-round arrow-blur"
                    id="hotel-slider"
                >
                    <div class="tiny-slider-inner">
                        @foreach ($hotel->gallery_images ?? [] as $img)
                            <div class="tns-item">
                                <a
                                    class="w-100 h-100"
                                    data-glightbox
                                    data-gallery="gallery"
                                    href="{{ $img }}"
                                >
                                    <div
                                        class="card card-element-hover card-overlay-hover overflow-hidden"
                                    >
                                        <img
                                            src="{{ $img }}"
                                            class="rounded-3"
                                            alt=""
                                        />
                                        <div class="hover-element w-100 h-100">
                                            <i
                                                class="bi bi-fullscreen fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-1 p-2 lh-1"
                                            ></i>
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
                                class="card-header bg-transparent border-bottom d-sm-flex justify-content-sm-between align-items-center p-0 pb-3"
                            >
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
                            <div
                                class="card-header bg-transparent border-bottom"
                            >
                                <h4 class="card-title mb-0">Price Summary</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <div
                                            class="bg-light py-3 px-4 rounded-3"
                                        >
                                            <h6 class="fw-light small mb-1">
                                                Check-in
                                            </h6>
                                            <h6
                                                class="mb-0"
                                                id="checkin-display"
                                            >
                                                -
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div
                                            class="bg-light py-3 px-4 rounded-3"
                                        >
                                            <h6 class="fw-light small mb-1">
                                                Check out
                                            </h6>
                                            <h6
                                                class="mb-0"
                                                id="checkout-display"
                                            >
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
                                    <a
                                        href="#"
                                        class="btn btn-dark mb-0"
                                        id="continue-booking-btn"
                                        style="display: none"
                                    >
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
    </main>
@endsection

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hotelId = @json($hotel->id);
            const urlParams = new URLSearchParams(window.location.search);
            const checkIn = urlParams.get('check_in');
            const checkOut = urlParams.get('check_out');
            const adults = urlParams.get('adults') || 2;
            const children = urlParams.get('children') || 0;

            document.getElementById('checkin-display').textContent =
                checkIn ?? '-';
            document.getElementById('checkout-display').textContent =
                checkOut ?? '-';

            const roomsList = document.getElementById('rooms-list');
            const roomsLoading = document.getElementById('rooms-loading');
            const priceSummary = document.getElementById('price-summary');
            const continueBtn = document.getElementById('continue-booking-btn');

            let selectedRooms = [];

            async function fetchAvailableRooms() {
                roomsLoading.style.display = '';
                roomsList.innerHTML = '';

                let url = `/hotel/${hotelId}/available-rooms`;
                const queryParams = [];
                if (checkIn) queryParams.push(`check_in=${checkIn}`);
                if (checkOut) queryParams.push(`check_out=${checkOut}`);
                if (adults) queryParams.push(`adults=${adults}`);
                if (children) queryParams.push(`children=${children}`);
                if (queryParams.length) url += `?${queryParams.join('&')}`;

                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    roomsLoading.style.display = 'none';

                    const roomData = data.data || [];
                    if (roomData.length === 0) {
                        roomsList.innerHTML = `<div class="alert alert-warning">No rooms available.</div>`;
                        return;
                    }

                    selectedRooms = [];
                    updatePriceSummary();

                    roomData.forEach((room) => {
                        const card = document.createElement('div');
                        card.className =
                            'card border bg-transparent p-3 mb-3 room-selection-card';
                        card.dataset.roomId = room.id;
                        card.dataset.roomRate = room.daily_rate ?? 0;

                        card.innerHTML = `
                    <div class="row g-3 g-md-4">
                        <div class="col-md-4">
                            <img src="${room.image || '/assets/images/category/hotel/4by3/02.jpg'}" class="card-img" alt="">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column p-0 h-100">
                                <h5 class="card-title">Room No. ${room.id}</h5>
                                <ul class="nav small nav-divider mb-0">
                                    <li class="nav-item mb-0"><i class="fa-solid fa-bed me-1"></i>${room.room_type || '---'}</li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mt-2 mt-md-auto">
                                    <div class="d-flex text-success">
                                        <h6 class="h5 mb-0 text-success">$${room.daily_rate ?? 'N/A'}</h6>
                                        <span class="fw-light">/per night</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-dark mb-0 select-room-toggle-btn"
                                        data-room-id="${room.id}"
                                        data-room-rate="${room.daily_rate ?? 0}">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                        roomsList.appendChild(card);
                    });

                    document
                        .querySelectorAll('.select-room-toggle-btn')
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
                if (selectedRooms.length === 0) {
                    priceSummary.innerHTML = `<div class="alert alert-secondary">Select rooms to view price summary.</div>`;
                    continueBtn.style.display = 'none';
                    return;
                }

                let total = selectedRooms.reduce((sum, r) => sum + r.rate, 0);
                let roomDetails = selectedRooms
                    .map(
                        (r, i) => `
            <li class="list-group-item px-2 d-flex justify-content-between">
                <span class="h6 fw-light mb-0">Room ${i + 1} (ID: ${r.id})</span>
                <span class="h6 fw-light mb-0">$${r.rate}</span>
            </li>
        `,
                    )
                    .join('');

                priceSummary.innerHTML = `
            <ul class="list-group list-group-borderless mb-3">
                ${roomDetails}
                <li class="list-group-item px-2 d-flex justify-content-between">
                    <span class="h6 fw-light mb-0">Guests</span>
                    <span class="h6 fw-light mb-0">${adults} Adults, ${children} Children</span>
                </li>
                <li class="list-group-item bg-light d-flex justify-content-between rounded-2 px-2 mt-2">
                    <span class="h5 fw-normal mb-0 ps-1">Total</span>
                    <span class="h5 fw-normal mb-0">$${total}</span>
                </li>
            </ul>
        `;

                continueBtn.style.display = '';
                const selectedRoomIds = selectedRooms
                    .map((r) => r.id)
                    .join(',');
                continueBtn.href = `/hotel/${hotelId}/rooms/${selectedRoomIds}/book?check_in=${checkIn}&check_out=${checkOut}&adults=${adults}&children=${children}`;
            }

            fetchAvailableRooms();

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
