@extends("layouts.app")

@section("title", "Hotels - Booking Landing Page")

@section("content")
    <main>
        <!-- =======================
                        Main Banner START -->
        <section class="pt-0">
            <div class="container">
                <div class="rounded-3 p-3 p-sm-5" style="
                                background-image: url(assets/images/bg/05.jpg);
                                background-position: center center;
                                background-repeat: no-repeat;
                                background-size: cover;
                            ">
                    <div class="row my-2 my-xl-5">
                        <div class="col-md-8 mx-auto">
                            <h1 class="text-center text-white">
                                150 Hotels in Sri Lanka
                            </h1>
                        </div>
                    </div>
                    <form class="bg-mode shadow rounded-3 position-relative p-4 pe-md-5 pb-5 pb-md-4 mb-4" id="search-form">
                        <div class="row g-4 align-items-center">
                            <!-- Location -->
                            <div class="col-lg-4">
                                <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                    <i class="bi bi-geo-alt fs-3 me-2 mt-2"></i>
                                    <div class="flex-grow-1">
                                        <label class="form-label">
                                            Location
                                        </label>
                                        <select class="form-select js-choice" data-search-enabled="true"
                                            id="location-select" name="location">
                                            <option value="">
                                                Select location
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Check in -->
                            <div class="col-lg-4">
                                <div class="d-flex">
                                    <i class="bi bi-calendar fs-3 me-2 mt-2"></i>
                                    <div class="form-control-border form-control-transparent form-fs-md">
                                        <label class="form-label">
                                            Check in - out
                                        </label>
                                        <input type="text" class="form-control flatpickr" data-mode="range"
                                            placeholder="Select date" id="checkinout" name="date_range" />
                                    </div>
                                </div>
                            </div>
                            <!-- Guests & rooms -->
                            <div class="col-lg-4">
                                <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                    <i class="bi bi-person fs-3 me-2 mt-2"></i>
                                    <div class="w-100">
                                        <label class="form-label">
                                            Guests & rooms
                                        </label>
                                        <div class="dropdown guest-selector me-2">
                                            <input type="text" class="form-guest-selector form-control selection-result"
                                                value="2 Guests 1 Room" id="dropdownGuest" data-bs-auto-close="outside"
                                                data-bs-toggle="dropdown" readonly />
                                            <ul class="dropdown-menu guest-selector-dropdown"
                                                aria-labelledby="dropdownGuest">
                                                <li class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            Adults
                                                        </h6>
                                                        <small>
                                                            Ages 13 or above
                                                        </small>
                                                    </div>
                                                    <div class="hstack gap-1 align-items-center">
                                                        <button type="button" class="btn btn-link adult-remove p-0 mb-0">
                                                            <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                        </button>
                                                        <h6 class="guest-selector-count mb-0 adults">
                                                            2
                                                        </h6>
                                                        <button type="button" class="btn btn-link adult-add p-0 mb-0">
                                                            <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <li class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            Child
                                                        </h6>
                                                        <small>
                                                            Ages 13 below
                                                        </small>
                                                    </div>
                                                    <div class="hstack gap-1 align-items-center">
                                                        <button type="button" class="btn btn-link child-remove p-0 mb-0">
                                                            <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                        </button>
                                                        <h6 class="guest-selector-count mb-0 child">
                                                            0
                                                        </h6>
                                                        <button type="button" class="btn btn-link child-add p-0 mb-0">
                                                            <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <li class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            Rooms
                                                        </h6>
                                                        <small>
                                                            Max room 8
                                                        </small>
                                                    </div>
                                                    <div class="hstack gap-1 align-items-center">
                                                        <button type="button" class="btn btn-link room-remove p-0 mb-0">
                                                            <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                        </button>
                                                        <h6 class="guest-selector-count mb-0 rooms">
                                                            1
                                                        </h6>
                                                        <button type="button" class="btn btn-link room-add p-0 mb-0">
                                                            <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <input type="hidden" name="adults" id="adults-input" value="2" />
                                            <input type="hidden" name="children" id="children-input" value="0" />
                                            <input type="hidden" name="rooms" id="rooms-input" value="1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-position-md-middle">
                            <button type="submit" class="icon-lg btn btn-round btn-primary mb-0" id="search-hotels">
                                <i class="bi bi-search fa-fw"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- =======================
                        Main Banner END -->

        <!-- =======================
                        Hotel list START -->
        <section class="pt-0">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="hstack gap-3 justify-content-between justify-content-md-end">
                            <button class="btn btn-primary-soft btn-primary-check mb-0 d-xl-none" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar"
                                aria-controls="offcanvasSidebar">
                                <i class="fa-solid fa-sliders-h me-1"></i>
                                Show filters
                            </button>
                            <ul class="nav nav-pills nav-pills-dark" id="tour-pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link rounded-start rounded-0 mb-0 active" href="#">
                                        <i class="bi fa-fw bi-list-ul"></i>
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                                    <a class="nav-link rounded-end rounded-0 mb-0" href="#"><i
                                                            class="bi fa-fw bi-grid-fill"></i></a>
                                                </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <aside class="col-xl-4 col-xxl-3">
                        <div class="offcanvas-xl offcanvas-end" tabindex="-1" id="offcanvasSidebar"
                            aria-labelledby="offcanvasSidebarLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasSidebarLabel">
                                    Advance Filters
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body flex-column p-3 p-xl-0">
                                <form class="rounded-3 shadow" id="filter-form">
                                    <div class="card card-body rounded-0 rounded-top p-4">
                                        <h6 class="mb-2">Hotel Type</h6>
                                        <div class="col-12" id="hotel-type-group">
                                            @foreach (["All", "Hotel", "Apartment", "Resort", "Villa", "Lodge", "Guest House", "Cottage", "Beach Hut", "Farm house", "Luxury", "Budget"] as $i => $type)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $type }}"
                                                        id="hotelType{{ $i + 1 }}" name="hotel_type[]" />
                                                    <label class="form-check-label" for="hotelType{{ $i + 1 }}">
                                                        {{ $type }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <hr class="my-0" />
                                    <div class="card card-body rounded-0 p-4">
                                        <h6 class="mb-2">Rating Star</h6>
                                        <ul class="list-inline mb-0 g-3">
                                            @foreach ([1, 2, 3, 4, 5] as $s)
                                                <li class="list-inline-item mb-0">
                                                    <input type="checkbox" class="btn-check" id="btn-check-star{{ $s }}"
                                                        name="star_rating[]" value="{{ $s }}" />
                                                    <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                        for="btn-check-star{{ $s }}">
                                                        {{ $s }}
                                                        <i class="bi bi-star-fill"></i>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex justify-content-between p-2 p-xl-0 mt-xl-4">
                                <button class="btn btn-link p-0 mb-0" id="clear-filters">
                                    Clear all
                                </button>
                                <button class="btn btn-primary mb-0" id="filter-hotels">
                                    Filter Result
                                </button>
                            </div>
                        </div>
                    </aside>
                    <div class="col-xl-8 col-xxl-9">
                        <div class="vstack gap-4" id="hotel-list-container"></div>
                        <nav class="d-flex justify-content-center mt-4" aria-label="navigation">
                            <ul class="pagination pagination-primary-soft d-inline-block d-md-flex rounded mb-0"
                                id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />

    <script>
        const PAGE_SIZE = 5;
        let allHotels = [];
        let filteredHotels = [];
        let currentPage = 1;
        let allLocations = [];

        // Fetch all hotels for select box (best practice: lightweight endpoint)
        async function populateLocationsDropdownFromApi(selectedValue = null) {
            const locationSelect = document.getElementById('location-select');
            if (!locationSelect) return;
            // This endpoint might need to be adjusted if locations are derived from the new /hotel/search results
            // For now, assuming it's a general list of all possible locations.
            const response = await fetch('/hotels/select-options');
            if (!response.ok) return;
            const result = await response.json();
            const prev = locationSelect.value;
            locationSelect.innerHTML = `<option value="">Select location</option>`;
            allLocations = [];
            result.data.forEach((hotel) => { // Assuming this endpoint returns a flat list of locations or hotels with addresses
                const location = hotel.address || hotel.location; // Adjust if structure is different
                if (location && !allLocations.includes(location)) {
                    allLocations.push(location);
                    locationSelect.innerHTML += `<option value="${location}">${location}</option>`;
                }
            });
            if (selectedValue !== null) {
                locationSelect.value = selectedValue;
            } else if (prev) {
                locationSelect.value = prev;
            }
        }

        // Vanilla JS typeahead for location search
        function setupLocationTypeahead() {
            const input = document.getElementsByClassName('location-typeahead');
            const select = document.getElementById('location-select');
            if (!input || !select) return;
            input.addEventListener('input', function () {
                const val = input.value.trim().toLowerCase();
                select.innerHTML = `<option value="">Select location</option>`;
                let found = false;
                allLocations.forEach((addr) => {
                    if (addr.toLowerCase().indexOf(val) !== -1) {
                        select.innerHTML += `<option value="${addr}">${addr}</option>`;
                        if (!found) {
                            select.value = addr;
                            found = true;
                        }
                    }
                });
                if (!val) {
                    allLocations.forEach((addr) => {
                        select.innerHTML += `<option value="${addr}">${addr}</option>`;
                    });
                    select.value = '';
                }
            });
            select.addEventListener('change', function () {
                if (select.value) input.value = select.value;
            });
        }

        function renderPagination(current, total) {
            window.scrollTo(0, 0);

            const pagination = document.getElementById('pagination');
            if (!pagination) return;
            pagination.innerHTML = '';
            if (total < 1) return;

            let pages = [];
            if (total <= 5) {
                for (let i = 1; i <= total; i++) pages.push(i);
            } else {
                pages = [1];
                if (current > 3) pages.push('...');
                let start = Math.max(2, current - 1);
                let end = Math.min(total - 1, current + 1);
                for (let i = start; i <= end; i++) pages.push(i);
                if (end < total - 1) pages.push('...');
                pages.push(total);
            }

            const addPage = (p, active = false) => {
                if (p === '...') {
                    const li = document.createElement('li');
                    li.className = 'page-item mb-0 disabled';
                    li.innerHTML = `<a class="page-link" href="#">..</a>`;
                    pagination.appendChild(li);
                } else {
                    const li = document.createElement('li');
                    li.className = `page-item mb-0${p === current ? ' active' : ''}`;
                    const a = document.createElement('a');
                    a.className = 'page-link';
                    a.href = '#';
                    a.textContent = p;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        renderHotels(filteredHotels, p);
                    });
                    li.appendChild(a);
                    pagination.appendChild(li);
                }
            };

            const prevLi = document.createElement('li');
            prevLi.className = `page-item mb-0${current === 1 ? ' disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#"><i class="fa-solid fa-angle-left"></i></a>`;
            prevLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (current > 1) renderHotels(filteredHotels, current - 1);
            });
            pagination.appendChild(prevLi);

            pages.forEach((p) => addPage(p, current === p));

            const nextLi = document.createElement('li');
            nextLi.className = `page-item mb-0${current === total ? ' disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#"><i class="fa-solid fa-angle-right"></i></a>`;
            nextLi.addEventListener('click', (e) => {
                e.preventDefault();
                if (current < total) renderHotels(filteredHotels, current + 1);
            });
            pagination.appendChild(nextLi);
        }

        function renderHotels(hotels, page = 1) {
            currentPage = page;
            const container = document.getElementById('hotel-list-container');
            container.innerHTML = '';
            if (hotels.length === 0) {
                container.innerHTML = `
                            <div class="card shadow p-2 mb-3">
                                <div class="row g-0">
                                    <div class="col-md-5 position-relative"></div>
                                    <div class="col-md-7">
                                        <div class="card-body py-md-2 d-flex flex-column h-100 position-relative">
                                            <h5 class="card-title">No hotels available</h5>
                                            <p class="card-text">Sorry, we couldn't find any hotels at the moment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                renderPagination(1, 1);
                return;
            }

            const start = (page - 1) * PAGE_SIZE;
            const end = start + PAGE_SIZE;
            const hotelsPage = hotels.slice(start, end);

            hotelsPage.forEach((item, index) => { // item is now {hotel: ..., rooms: ...}
                const hotel = item.hotel; // Extract hotel object
                const rating = hotel.star_rating || 0;
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;
                let starsHTML = '';
                for (let i = 0; i < fullStars; i++) {
                    starsHTML += `<li class="list-inline-item me-0 small"><i class="fa-solid fa-star text-warning"></i></li>`;
                }
                if (halfStar) {
                    starsHTML += `<li class="list-inline-item me-0 small"><i class="fa-solid fa-star-half-alt text-warning"></i></li>`;
                }
                for (let i = 0; i < emptyStars; i++) {
                    starsHTML += `<li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i></li>`;
                }

                let imgs = Array.isArray(hotel.images) && hotel.images.length > 0
                    ? hotel.images
                    : (typeof hotel.images === 'string' && hotel.images ? [hotel.images] : ['assets/images/category/hotel/4by3/04.jpg']); // Default image
                let sliderId = `tiny-slider-${hotel.id}`;
                let imgSliderHTML = '';
                if (imgs.length > 1) {
                    imgSliderHTML = `<div class="tiny-slider arrow-round arrow-xs arrow-dark overflow-hidden rounded-2" id="${sliderId}">
                                <div class="tiny-slider-inner">
                                    ${imgs.map((img) => `<div><img src="${img.image_path || img}" class="img-fluid rounded-start" alt="${hotel.name}"></div>`).join('')}
                                </div>
                                </div>`;
                } else {
                    imgSliderHTML = `<img src="${imgs[0].image_path || imgs[0]}" class="img-fluid rounded-start" alt="${hotel.name}">`;
                }

                const discountBadge = hotel.discount_percentage // Assuming discount_percentage from HotelResource
                    ? `
                            <div class="position-absolute top-0 start-0 z-index-1 m-2">
                                <div class="badge text-bg-danger">${hotel.discount_percentage}% Off</div>
                            </div>`
                    : '';

                // Amenities: Assuming hotel.amenities is an array of strings from HotelResource
                let amenitiesHTML = '';
                if(hotel.amenities && Array.isArray(hotel.amenities)) {
                    hotel.amenities.slice(0, 4).forEach(amenity => { // Show max 4 amenities
                        amenitiesHTML += `<li class="nav-item">${amenity}</li>`;
                    });
                } else {
                     amenitiesHTML = `
                            <li class="nav-item">Air Conditioning</li>
                            <li class="nav-item">Wifi</li>
                            <li class="nav-item">Kitchen</li>
                            <li class="nav-item">Pool</li>
                            `; // Fallback
                }


                let listGroup = '';
                // Assuming these properties exist in item.hotel after HotelResource transformation
                if (hotel.is_free_cancellation) // Example property name
                    listGroup += `<li class="list-group-item d-flex text-success p-0"><i class="bi bi-patch-check-fill me-2"></i>Free Cancellation</li>`;
                else if (hotel.is_non_refundable) // Example property name
                    listGroup += `<li class="list-group-item d-flex text-danger p-0"><i class="bi bi-patch-check-fill me-2"></i>Non Refundable</li>`;
                if (hotel.has_free_breakfast) // Example property name
                    listGroup += `<li class="list-group-item d-flex text-success p-0"><i class="bi bi-patch-check-fill me-2"></i>Free Breakfast</li>`;

                const priceHTML = `
                            <div class="d-flex align-items-center">
                                ${hotel.price_per_night ? `<h5 class="fw-bold mb-0 me-1">$${hotel.price_per_night.toFixed(2)}</h5>` : ''}
                                ${hotel.original_price_per_night ? `<span class="text-decoration-line-through mb-0">$${hotel.original_price_per_night.toFixed(2)}</span>` : ''}
                            </div>
                            `;

                const numAvailableRooms = item.rooms ? item.rooms.length : 0;

                // --- Select Room Button logic ---
                const searchFormEl = document.getElementById('search-form');
                const mainSearchParams = new FormData(searchFormEl);
                const checkinoutValue = document.getElementById('checkinout')?.value || '';
                let queryParams = new URLSearchParams();

                if (checkinoutValue && checkinoutValue.includes(' to ')) {
                    const [cin, cout] = checkinoutValue.split(' to ');
                    queryParams.set('check_in', cin);
                    queryParams.set('check_out', cout);
                }
                queryParams.set('adults', mainSearchParams.get('adults') || '1');
                queryParams.set('children', mainSearchParams.get('children') || '0');
                queryParams.set('num_rooms', mainSearchParams.get('rooms') || '1'); // Number of rooms requested

                const cardHTML = `
                            <div class="card shadow p-2 mb-3">
                                <div class="row g-0">
                                    <div class="col-md-5 position-relative">
                                        ${discountBadge}
                                        ${imgSliderHTML}
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card-body py-md-2 d-flex flex-column h-100 position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <ul class="list-inline mb-0">${starsHTML}</ul>
                                                <ul class="list-inline mb-0 z-index-2">
                                                    <li class="list-inline-item">
                                                        <a href="#" class="btn btn-sm btn-round btn-light">
                                                            <i class="fa-solid fa-fw fa-heart"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item dropdown">
                                                        <a href="#" class="btn btn-sm btn-round btn-light" role="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-solid fa-fw fa-share-alt"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow rounded">
                                                            <li><a class="dropdown-item" href="#"><i class="fab fa-twitter-square me-2"></i>Twitter</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="fab fa-facebook-square me-2"></i>Facebook</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-copy me-2"></i>Copy link</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                            <h5 class="card-title mb-1">
                                                <a href="/hotel/${hotel.id}" target="_blank">${hotel.name}</a>
                                            </h5>
                                            <small><i class="bi bi-geo-alt me-2"></i>${hotel.address || 'Location not available'}</small>
                                            ${numAvailableRooms > 0 ? `<p class="mb-0 small mt-1"><strong>${numAvailableRooms} room type(s) available for your search.</strong></p>` : '<p class="mb-0 small mt-1 text-danger">No rooms available for these criteria.</p>'}
                                            <ul class="nav nav-divider mt-3">
                                                ${amenitiesHTML}
                                            </ul>
                                            <ul class="list-group list-group-borderless small mb-0 mt-2">
                                                ${listGroup}
                                            </ul>
                                            <div class="d-sm-flex justify-content-sm-between align-items-center mt-3 mt-md-auto">
                                                ${priceHTML}
                                                <div class="mt-3 mt-sm-0">
                                                    <button class="btn btn-sm btn-dark mb-0 w-100 select-room-btn"
                                                            data-hotel-id="${hotel.id}"
                                                            data-query="${queryParams.toString()}">View Rooms</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                container.insertAdjacentHTML('beforeend', cardHTML);

                if (imgs.length > 1 && window.tns) {
                    setTimeout(() => {
                        tns({
                            container: `#${sliderId} .tiny-slider-inner`,
                            items: 1,
                            slideBy: 1,
                            autoplay: false,
                            controls: true,
                            nav: false,
                        });
                    }, 0);
                }
            });

            const totalPages = Math.ceil(hotels.length / PAGE_SIZE);
            renderPagination(page, totalPages);

            // Attach click listeners to Select Room buttons
            document.querySelectorAll('.select-room-btn').forEach((btn) => {
                btn.onclick = function (e) {
                    e.preventDefault();
                    const hotelId = this.getAttribute('data-hotel-id');
                    const queryString = this.getAttribute('data-query');

                    const params = new URLSearchParams(queryString);
                    if (!params.has('check_in') || !params.has('check_out') || !params.get('check_in') || !params.get('check_out')) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: 'Please select valid check-in and check-out dates in the main search form first!',
                                duration: 3500,
                                close: true,
                                gravity: 'top',
                                position: 'right',
                                backgroundColor: '#d9534f',
                            }).showToast();
                        } else {
                            alert('Please select valid check-in and check-out dates in the main search form first!');
                        }
                        return;
                    }
                    window.location.href = `/hotel/${hotelId}/rooms?${queryString}`;
                };
            });
        }

        function applyFilters() {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            let selectedTypes = formData
                .getAll('hotel_type[]')
                .filter((v) => v !== 'All');
            let selectedStars = formData.getAll('star_rating[]').map(Number);

            // Ensure allHotels contains the new structure {hotel, rooms}
            if (allHotels.length > 0 && allHotels[0].hotel === undefined) {
                console.warn("allHotels does not have the expected structure for filtering. Old data might be present.");
                // Potentially re-fetch or handle error
                filteredHotels = []; // Or keep old filter logic if necessary as a fallback
            } else {
                 filteredHotels = allHotels.filter((item) => { // item is {hotel, rooms}
                    let hotel = item.hotel;
                    let pass = true;
                    if (
                        selectedTypes.length > 0 &&
                        !selectedTypes.includes(hotel.type || hotel.hotel_type) // Ensure 'type' or 'hotel_type' exists on hotel object
                    )
                        pass = false;
                    if (
                        selectedStars.length > 0 &&
                        !selectedStars.includes(Math.floor(hotel.star_rating))
                    )
                        pass = false;
                    return pass;
                });
            }
            renderHotels(filteredHotels, 1);
            // populateLocationsDropdownFromApi(); // This might not be needed here or could repopulate from filtered results
        }

        async function fetchHotels(params = {}) {
            // Validate that check_in and check_out are present if it's a search, not initial load
            if (params.location || params.check_in || params.check_out) { // Heuristic: if any search param is there, dates are mandatory
                 if (!params.check_in || !params.check_out) {
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: 'Please select check-in and check-out dates for the search.',
                            duration: 3000,
                            close: true,
                            gravity: 'top',
                            position: 'right',
                            backgroundColor: '#d9534f',
                        }).showToast();
                    } else {
                        alert('Please select check-in and check-out dates for the search.');
                    }
                    // Do not proceed with fetch if essential dates are missing for a search action
                    // render empty or current state? For now, just return.
                    document.getElementById('search-hotels').disabled = false; // Re-enable button
                    return;
                }
            }

            try {
                document.getElementById('search-hotels').disabled = true; // Disable search button
                let url = '/hotel/search'; // Changed URL
                if (Object.keys(params).length) {
                    const query = new URLSearchParams(params).toString();
                    url += '?' + query;
                }
                const response = await fetch(url);
                if (!response.ok) {
                    const errorResult = await response.json();
                    throw new Error(errorResult.message || 'Failed to fetch hotels');
                }
                const result = await response.json();

                // result.data is expected to be an array of {"hotel": {...}, "rooms": [...]}
                allHotels = result.data || [];
                filteredHotels = [...allHotels];
                renderHotels(filteredHotels, 1);
                // populateLocationsDropdownFromApi(); // Might not be needed or could be smarter
            } catch (e) {
                console.error("FetchHotels Error:", e);
                const container = document.getElementById(
                    'hotel-list-container',
                );
                container.innerHTML = `<div class="alert alert-danger">Failed to load hotels: ${e.message}</div>`;
                renderPagination(1, 1); // Render empty pagination
                allHotels = []; // Clear data
                filteredHotels = [];
            } finally {
                 if (document.getElementById('search-hotels')) {
                    document.getElementById('search-hotels').disabled = false; // Re-enable search button
                 }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Function to update guest input display and hidden fields
            function updateGuestInput() {
                const adultsEl = document.querySelector(
                    '.guest-selector-count.adults',
                );
                const childEl = document.querySelector(
                    '.guest-selector-count.child',
                );
                const roomsEl = document.querySelector(
                    '.guest-selector-count.rooms',
                );
                const dropdownGuest = document.getElementById('dropdownGuest');
                const adultsInput = document.getElementById('adults-input');
                const childrenInput = document.getElementById('children-input');
                const roomsInput = document.getElementById('rooms-input');
                if (
                    adultsEl &&
                    childEl &&
                    roomsEl &&
                    dropdownGuest &&
                    adultsInput &&
                    childrenInput &&
                    roomsInput
                ) {
                    const adults = adultsEl.textContent;
                    const child = childEl.textContent;
                    const rooms = roomsEl.textContent;
                    dropdownGuest.value = `${parseInt(adults) + parseInt(child)} Guests ${rooms} Room`;
                    adultsInput.value = adults;
                    childrenInput.value = child;
                    roomsInput.value = rooms;
                }
            }

            // Helper for increment/decrement handlers
            function handleGuestRoomChange(selector, min, max, increment) {
                const el = document.querySelector(selector);
                if (!el) return;
                let val = parseInt(el.textContent);
                val = increment
                    ? Math.min(val + 1, max)
                    : Math.max(val - 1, min);
                el.textContent = val;
                updateGuestInput();
            }

            // Attach event listeners if elements exist
            (document.querySelectorAll('.adult-add') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.adults',
                        1,
                        8,
                        true,
                    )),
            );
            (document.querySelectorAll('.adult-remove') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.adults',
                        1,
                        8,
                        false,
                    )),
            );
            (document.querySelectorAll('.child-add') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.child',
                        0,
                        8,
                        true,
                    )),
            );
            (document.querySelectorAll('.child-remove') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.child',
                        0,
                        8,
                        false,
                    )),
            );
            (document.querySelectorAll('.room-add') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.rooms',
                        1,
                        8,
                        true,
                    )),
            );
            (document.querySelectorAll('.room-remove') || []).forEach(
                (btn) =>
                (btn.onclick = () =>
                    handleGuestRoomChange(
                        '.guest-selector-count.rooms',
                        1,
                        8,
                        false,
                    )),
            );

            // Clear filters
            const clearFiltersBtn = document.getElementById('clear-filters');
            if (clearFiltersBtn) {
                clearFiltersBtn.onclick = function (e) {
                    e.preventDefault();
                    const filterForm = document.getElementById('filter-form');
                    if (filterForm) filterForm.reset();
                    if (
                        typeof allHotels !== 'undefined' &&
                        typeof filteredHotels !== 'undefined' &&
                        typeof renderHotels !== 'undefined'
                    ) {
                        filteredHotels = [...allHotels];
                        renderHotels(filteredHotels, 1);
                    }
                    if (typeof populateLocationsDropdownFromApi !== 'undefined')
                        populateLocationsDropdownFromApi();
                };
            }


            const filterHotelsBtn = document.getElementById('filter-hotels');
            if (filterHotelsBtn) {
                filterHotelsBtn.onclick = function (e) {
                    e.preventDefault();
                    if (typeof applyFilters !== 'undefined') applyFilters();
                    if (typeof populateLocationsDropdownFromApi !== 'undefined')
                        populateLocationsDropdownFromApi();
                };
            }

            const searchForm = document.getElementById('search-form');

            function handleSearchFormSubmit(e) {
                if (e) e.preventDefault();

                const locationSelect = document.getElementById('location-select');
                const locationValue = locationSelect ? locationSelect.value : '';

                const dateRangeEl = document.getElementById('checkinout');
                const dateRange = dateRangeEl ? dateRangeEl.value : '';
                let check_in = '', check_out = '';

                if (!locationValue) {
                     if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: 'Please select a location.',
                            duration: 3000, close: true, gravity: 'top', position: 'right', backgroundColor: '#d9534f',
                        }).showToast();
                    } else { alert('Please select a location.'); }
                    return false;
                }

                if (dateRange && dateRange.includes(' to ')) {
                    [check_in, check_out] = dateRange.split(' to ');
                } else {
                    // Allow search without dates initially, or enforce date selection here
                    // For now, let's enforce date selection as per controller validation
                     if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: 'Please select both check-in and check-out dates.',
                            duration: 3000, close: true, gravity: 'top', position: 'right', backgroundColor: '#d9534f',
                        }).showToast();
                    } else { alert('Please select both check-in and check-out dates.'); }
                    return false; // Stop if dates are not a range or not selected
                }

                const formData = new FormData(searchForm);
                const params = {
                    location: formData.get('location'), // Ensure location is from the select
                    adults: formData.get('adults'),
                    children: formData.get('children'),
                    rooms: formData.get('rooms'),
                    check_in: check_in,
                    check_out: check_out
                };

                // Remove date_range if it was part of formData to avoid confusion
                delete params.date_range;

                if (typeof fetchHotels !== 'undefined') fetchHotels(params);
                return false;
            }

            if (searchForm) {
                searchForm.onsubmit = handleSearchFormSubmit;

                // Remove automatic search on every field change to prevent excessive API calls.
                // Search will be triggered by the submit button or specific explicit actions.
                // Array.from(searchForm.elements).forEach(el => {
                //     if (el.tagName === "INPUT" || el.tagName === "SELECT") {
                //         // el.removeEventListener('change', handleSearchFormSubmit); // If previously added
                //     }
                // });
            }

            // Location select does not need to auto-submit anymore, user clicks search button.
            // const locationSelect = document.getElementById('location-select');
            // if (locationSelect) {
            //     locationSelect.onchange = null; // Remove previous handler if any
            // }

            if (typeof populateLocationsDropdownFromApi !== 'undefined') {
                 populateLocationsDropdownFromApi().then(() => {
                    if (typeof setupLocationTypeahead !== 'undefined') {
                        setupLocationTypeahead();
                    }
                 });
            }

            // Initial flatpickr options (will be potentially overridden by URL params)
            let flatpickrOptions = {
                mode: 'range',
                dateFormat: 'Y-m-d',
            };

            const urlParams = new URLSearchParams(window.location.search);
            const paramLocation = urlParams.get('location');
            const paramCheckIn = urlParams.get('check_in');
            const paramCheckOut = urlParams.get('check_out');
            const paramAdults = urlParams.get('adults');
            const paramChildren = urlParams.get('children');
            const paramRooms = urlParams.get('rooms');

            let performAutoSearch = false;

            if (paramCheckIn && paramCheckOut) {
                const checkInOutEl = document.getElementById('checkinout');
                if (checkInOutEl) {
                    // Set value for display, though flatpickr defaultDate is better for initialization
                    checkInOutEl.value = `${paramCheckIn} to ${paramCheckOut}`;
                    flatpickrOptions.defaultDate = [paramCheckIn, paramCheckOut];
                }
                performAutoSearch = true;
            }

            // Initialize flatpickr with potentially modified options
            if (window.flatpickr) {
                flatpickr('#checkinout', flatpickrOptions);
            }

            if (paramAdults) {
                const adultsEl = document.querySelector('.guest-selector-count.adults');
                if (adultsEl) adultsEl.textContent = paramAdults;
                const adultsInputEl = document.getElementById('adults-input');
                if (adultsInputEl) adultsInputEl.value = paramAdults;
            }
            if (paramChildren) {
                const childEl = document.querySelector('.guest-selector-count.child');
                if (childEl) childEl.textContent = paramChildren;
                const childrenInputEl = document.getElementById('children-input');
                if(childrenInputEl) childrenInputEl.value = paramChildren;
            }
            if (paramRooms) {
                const roomsEl = document.querySelector('.guest-selector-count.rooms');
                if (roomsEl) roomsEl.textContent = paramRooms;
                const roomsInputEl = document.getElementById('rooms-input');
                if(roomsInputEl) roomsInputEl.value = paramRooms;
            }

            if (paramAdults || paramChildren || paramRooms) { // only call if any guest param is present
                updateGuestInput();
            }


            populateLocationsDropdownFromApi(paramLocation).then(() => {
                if (paramLocation) {
                    const locationSelect = document.getElementById('location-select');
                    if (locationSelect) {
                        // For Choices.js, setting value after options are populated is key.
                        // Check if Choices instance exists on the element.
                         if (window.Choices && locationSelect.choices) {
                             locationSelect.choices.setValue([{value: paramLocation, label: paramLocation}]); // More robust way for choices
                         } else {
                            locationSelect.value = paramLocation; // Fallback
                         }
                    }
                }
                // if (typeof setupLocationTypeahead !== 'undefined') setupLocationTypeahead();
            });

            if (performAutoSearch) {
                // Location is also required for a meaningful auto-search by handleSearchFormSubmit's validation
                if (paramLocation || (document.getElementById('location-select') && document.getElementById('location-select').value)) {
                    handleSearchFormSubmit();
                } else {
                    // If location is considered essential for auto-search and not present, render empty.
                    // Or, if backend can handle no location, this check might be removed.
                    // For now, if dates are there but location is not, don't auto-search.
                    console.log("Auto-search skipped: Location parameter missing or not set.");
                    renderHotels([], 1);
                }
            } else {
                renderHotels([], 1);
            }
        });
    </script>
@endpush