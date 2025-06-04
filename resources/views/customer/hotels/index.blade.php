@extends('layouts.app')

@section('title', 'About Us - Booking Landing Page')

@section('content')
<main>
	<!-- =======================
	Main Banner START -->
	<section class="pt-0">
		<div class="container">
			<div class="rounded-3 p-3 p-sm-5"
				style="background-image: url(assets/images/bg/05.jpg); background-position: center center; background-repeat: no-repeat; background-size: cover;">
				<div class="row my-2 my-xl-5">
					<div class="col-md-8 mx-auto">
						<h1 class="text-center text-white">150 Hotels in New York</h1>
					</div>
				</div>
				<form class="bg-mode shadow rounded-3 position-relative p-4 pe-md-5 pb-5 pb-md-4 mb-4">
					<div class="row g-4 align-items-center">
						<!-- Location -->
						<div class="col-lg-4">
							<div class="form-control-border form-control-transparent form-fs-md d-flex">
								<i class="bi bi-geo-alt fs-3 me-2 mt-2"></i>
								<div class="flex-grow-1">
									<label class="form-label">Location</label>
									<select class="form-select js-choice" data-search-enabled="true"
										id="location-select">
										<option value="">Select location</option>
										<option>San Jacinto, USA</option>
										<option>North Dakota, Canada</option>
										<option>West Virginia, Paris</option>
									</select>
								</div>
							</div>
						</div>
						<!-- Check in -->
						<div class="col-lg-4">
							<div class="d-flex">
								<i class="bi bi-calendar fs-3 me-2 mt-2"></i>
								<div class="form-control-border form-control-transparent form-fs-md">
									<label class="form-label">Check in - out</label>
									<input type="text" class="form-control flatpickr" data-mode="range"
										placeholder="Select date" id="checkinout">
								</div>
							</div>
						</div>
						<!-- Guests & rooms -->
						<div class="col-lg-4">
							<div class="form-control-border form-control-transparent form-fs-md d-flex">
								<i class="bi bi-person fs-3 me-2 mt-2"></i>
								<div class="w-100">
									<label class="form-label">Guests & rooms</label>
									<div class="dropdown guest-selector me-2">
										<input type="text" class="form-guest-selector form-control selection-result"
											value="2 Guests 1 Room" id="dropdownGuest" data-bs-auto-close="outside"
											data-bs-toggle="dropdown" readonly>
										<ul class="dropdown-menu guest-selector-dropdown"
											aria-labelledby="dropdownGuest">
											<li class="d-flex justify-content-between">
												<div>
													<h6 class="mb-0">Adults</h6>
													<small>Ages 13 or above</small>
												</div>
												<div class="hstack gap-1 align-items-center">
													<button type="button" class="btn btn-link adult-remove p-0 mb-0"><i
															class="bi bi-dash-circle fs-5 fa-fw"></i></button>
													<h6 class="guest-selector-count mb-0 adults">2</h6>
													<button type="button" class="btn btn-link adult-add p-0 mb-0"><i
															class="bi bi-plus-circle fs-5 fa-fw"></i></button>
												</div>
											</li>
											<li class="dropdown-divider"></li>
											<li class="d-flex justify-content-between">
												<div>
													<h6 class="mb-0">Child</h6>
													<small>Ages 13 below</small>
												</div>
												<div class="hstack gap-1 align-items-center">
													<button type="button" class="btn btn-link child-remove p-0 mb-0"><i
															class="bi bi-dash-circle fs-5 fa-fw"></i></button>
													<h6 class="guest-selector-count mb-0 child">0</h6>
													<button type="button" class="btn btn-link child-add p-0 mb-0"><i
															class="bi bi-plus-circle fs-5 fa-fw"></i></button>
												</div>
											</li>
											<li class="dropdown-divider"></li>
											<li class="d-flex justify-content-between">
												<div>
													<h6 class="mb-0">Rooms</h6>
													<small>Max room 8</small>
												</div>
												<div class="hstack gap-1 align-items-center">
													<button type="button" class="btn btn-link room-remove p-0 mb-0"><i
															class="bi bi-dash-circle fs-5 fa-fw"></i></button>
													<h6 class="guest-selector-count mb-0 rooms">1</h6>
													<button type="button" class="btn btn-link room-add p-0 mb-0"><i
															class="bi bi-plus-circle fs-5 fa-fw"></i></button>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="btn-position-md-middle">
						<a class="icon-lg btn btn-round btn-primary mb-0" href="#" id="search-hotels"><i
								class="bi bi-search fa-fw"></i></a>
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
					<div class="alert alert-danger alert-dismissible d-flex justify-content-between align-items-center rounded-3 fade show mb-4 mb-0 pe-2"
						role="alert">
						<div class="d-flex">
							<span class="alert-heading h5 mb-0 me-2"><i
									class="bi bi-exclamation-octagon-fill"></i></span>
							<span><strong class="alert-heading me-2">Covid Policy:</strong>You may require to present an
								RT-PCR negative test report at the hotel</span>
						</div>
						<button type="button" class="btn btn-link pb-0 text-end" data-bs-dismiss="alert"
							aria-label="Close"><i class="bi bi-x-lg text-dark"></i></button>
					</div>
					<div class="hstack gap-3 justify-content-between justify-content-md-end">
						<button class="btn btn-primary-soft btn-primary-check mb-0 d-xl-none" type="button"
							data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar"
							aria-controls="offcanvasSidebar">
							<i class="fa-solid fa-sliders-h me-1"></i> Show filters
						</button>
						<ul class="nav nav-pills nav-pills-dark" id="tour-pills-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link rounded-start rounded-0 mb-0 active" href="#"><i
										class="bi fa-fw bi-list-ul"></i></a>
							</li>
							<li class="nav-item">
								<a class="nav-link rounded-end rounded-0 mb-0" href="#"><i
										class="bi fa-fw bi-grid-fill"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<aside class="col-xl-4 col-xxl-3">
					<div class="offcanvas-xl offcanvas-end" tabindex="-1" id="offcanvasSidebar"
						aria-labelledby="offcanvasSidebarLabel">
						<div class="offcanvas-header">
							<h5 class="offcanvas-title" id="offcanvasSidebarLabel">Advance Filters</h5>
							<button type="button" class="btn-close" data-bs-dismiss="offcanvas"
								data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
						</div>
						<div class="offcanvas-body flex-column p-3 p-xl-0">
							<form class="rounded-3 shadow" id="filter-form">
								<div class="card card-body rounded-0 rounded-top p-4">
									<h6 class="mb-2">Hotel Type</h6>
									<div class="col-12" id="hotel-type-group">
										@foreach(['All', 'Hotel', 'Apartment', 'Resort', 'Villa', 'Lodge', 'Guest House', 'Cottage', 'Beach Hut', 'Farm house', 'Luxury', 'Budget'] as $i => $type)
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="{{ $type }}"
													id="hotelType{{$i + 1}}" name="hotel_type[]">
												<label class="form-check-label" for="hotelType{{$i + 1}}">{{ $type }}</label>
											</div>
										@endforeach
									</div>
								</div>
								<hr class="my-0">
								<div class="card card-body rounded-0 p-4">
									<h6 class="mb-2">Rating Star</h6>
									<ul class="list-inline mb-0 g-3">
										@foreach([1, 2, 3, 4, 5] as $s)
											<li class="list-inline-item mb-0">
												<input type="checkbox" class="btn-check" id="btn-check-star{{$s}}"
													name="star_rating[]" value="{{$s}}">
												<label class="btn btn-sm btn-light btn-primary-soft-check"
													for="btn-check-star{{$s}}">{{$s}}<i class="bi bi-star-fill"></i></label>
											</li>
										@endforeach
									</ul>
								</div>
							</form>
						</div>
						<div class="d-flex justify-content-between p-2 p-xl-0 mt-xl-4">
							<button class="btn btn-link p-0 mb-0" id="clear-filters">Clear all</button>
							<button class="btn btn-primary mb-0" id="filter-hotels">Filter Result</button>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
const PAGE_SIZE = 5; 
let allHotels = [];
let filteredHotels = [];
let currentPage = 1;

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
			a.addEventListener('click', e => {
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
	prevLi.addEventListener('click', e => {
		e.preventDefault();
		if (current > 1) renderHotels(filteredHotels, current - 1);
	});
	pagination.appendChild(prevLi);

	pages.forEach(p => addPage(p, current === p));


	const nextLi = document.createElement('li');
	nextLi.className = `page-item mb-0${current === total ? ' disabled' : ''}`;
	nextLi.innerHTML = `<a class="page-link" href="#"><i class="fa-solid fa-angle-right"></i></a>`;
	nextLi.addEventListener('click', e => {
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
					<div class="col-md-5 position-relative">
					
					</div>
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

	hotelsPage.forEach((hotel, index) => {
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

		let imgs = Array.isArray(hotel.images) ? hotel.images : [hotel.images];
		let sliderId = `tiny-slider-${hotel.id}`;
		let imgSliderHTML = '';
		if (imgs.length > 1) {
			imgSliderHTML = `<div class="tiny-slider arrow-round arrow-xs arrow-dark overflow-hidden rounded-2" id="${sliderId}">
				<div class="tiny-slider-inner">
					${imgs.map(img => `<div><img src="${img}" class="img-fluid rounded-start" alt="${hotel.name}"></div>`).join('')}
				</div>
			</div>`;
		} else {
			imgSliderHTML = `<img src="${imgs[0]}" class="img-fluid rounded-start" alt="${hotel.name}">`;
		}

		const discountBadge = hotel.discount ? `
			<div class="position-absolute top-0 start-0 z-index-1 m-2">
				<div class="badge text-bg-danger">${hotel.discount}% Off</div>
			</div>` : '';

		const amenitiesHTML = `
			<li class="nav-item">Air Conditioning</li>
			<li class="nav-item">Wifi</li>
			<li class="nav-item">Kitchen</li>
			<li class="nav-item">Pool</li>
		`;

		let listGroup = '';
		if (hotel.free_cancellation)
			listGroup += `<li class="list-group-item d-flex text-success p-0"><i class="bi bi-patch-check-fill me-2"></i>Free Cancellation</li>`;
		else if (hotel.refundable === false)
			listGroup += `<li class="list-group-item d-flex text-danger p-0"><i class="bi bi-patch-check-fill me-2"></i>Non Refundable</li>`;
		if (hotel.free_breakfast)
			listGroup += `<li class="list-group-item d-flex text-success p-0"><i class="bi bi-patch-check-fill me-2"></i>Free Breakfast</li>`;

		const priceHTML = `
			<div class="d-flex align-items-center">
			
				${hotel.original_price ? `<span class="text-decoration-line-through mb-0">$${hotel.original_price}</span>` : ''}
			</div>
		`;

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
							<a href="${hotel.website || '#'}" target="_blank">${hotel.name}</a>
						</h5>
						<small><i class="bi bi-geo-alt me-2"></i>${hotel.address || 'Location not available'}</small>
						<ul class="nav nav-divider mt-3">
							${amenitiesHTML}
						</ul>
						<ul class="list-group list-group-borderless small mb-0 mt-2">
							${listGroup}
						</ul>
						<div class="d-sm-flex justify-content-sm-between align-items-center mt-3 mt-md-auto">
							${priceHTML}
							<div class="mt-3 mt-sm-0">
								<a href="#" class="btn btn-sm btn-dark mb-0 w-100">Select Room</a>
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
					nav: false
				});
			}, 0);
		}
	});

	const totalPages = Math.ceil(hotels.length / PAGE_SIZE);
	renderPagination(page, totalPages);
}

function applyFilters() {
	const form = document.getElementById('filter-form');
	const formData = new FormData(form);
	let selectedTypes = formData.getAll('hotel_type[]').filter(v => v !== "All");
	let selectedStars = formData.getAll('star_rating[]').map(Number);

	filteredHotels = allHotels.filter(hotel => {
		let pass = true;
		if (selectedTypes.length > 0 && !selectedTypes.includes(hotel.type || hotel.hotel_type)) pass = false;
		if (selectedStars.length > 0 && !selectedStars.includes(Math.floor(hotel.star_rating))) pass = false;
		return pass;
	});
	renderHotels(filteredHotels, 1);
}

async function fetchHotels() {
	try {
		const response = await fetch('/hotels/getHotels');
		if (!response.ok) throw new Error('Failed to fetch hotels');
		const result = await response.json();
		let hotels = result.data || result;
		hotels = hotels.map((hotel, idx) => ({
			id: hotel.id || idx + 1,
			name: hotel.name,
			address: hotel.address,
			star_rating: hotel.star_rating,
			images: Array.isArray(hotel.images)
				? hotel.images
				: (typeof hotel.images === "string" && hotel.images ? [hotel.images] : ['assets/images/category/hotel/4by3/04.jpg']),
			price: hotel.price || '',
			original_price: hotel.original_price || null,
			discount: hotel.discount || null,
			website: hotel.website || '#',
			free_cancellation: hotel.description && hotel.description.toLowerCase().includes('free cancellation'),
			free_breakfast: hotel.description && hotel.description.toLowerCase().includes('breakfast'),
			refundable: !(hotel.description && hotel.description.toLowerCase().includes('non refundable')),
			type: hotel.type || hotel.hotel_type || ''
		}));
		allHotels = hotels;
		filteredHotels = [...hotels];
		renderHotels(filteredHotels, 1);
	} catch (e) {
		const container = document.getElementById('hotel-list-container');
		container.innerHTML = `<div class="alert alert-danger">Failed to load hotels.</div>`;
		renderPagination(1, 1);
	}
}

document.addEventListener('DOMContentLoaded', function () {
	if (window.flatpickr) flatpickr("#checkinout", { mode: "range" });

	function updateGuestInput() {
		const adults = document.querySelector('.guest-selector-count.adults').textContent;
		const child = document.querySelector('.guest-selector-count.child').textContent;
		const rooms = document.querySelector('.guest-selector-count.rooms').textContent;
		document.getElementById('dropdownGuest').value = `${parseInt(adults) + parseInt(child)} Guests ${rooms} Room`;
	}
	document.querySelectorAll('.adult-add').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.adults');
		el.textContent = Math.min(parseInt(el.textContent) + 1, 8);
		updateGuestInput();
	});
	document.querySelectorAll('.adult-remove').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.adults');
		el.textContent = Math.max(parseInt(el.textContent) - 1, 1);
		updateGuestInput();
	});
	document.querySelectorAll('.child-add').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.child');
		el.textContent = Math.min(parseInt(el.textContent) + 1, 8);
		updateGuestInput();
	});
	document.querySelectorAll('.child-remove').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.child');
		el.textContent = Math.max(parseInt(el.textContent) - 1, 0);
		updateGuestInput();
	});
	document.querySelectorAll('.room-add').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.rooms');
		el.textContent = Math.min(parseInt(el.textContent) + 1, 8);
		updateGuestInput();
	});
	document.querySelectorAll('.room-remove').forEach(btn => btn.onclick = function () {
		let el = document.querySelector('.guest-selector-count.rooms');
		el.textContent = Math.max(parseInt(el.textContent) - 1, 1);
		updateGuestInput();
	});

	document.getElementById('clear-filters').onclick = function (e) {
		e.preventDefault();
		document.getElementById('filter-form').reset();
		filteredHotels = [...allHotels];
		renderHotels(filteredHotels, 1);
	};
	document.getElementById('filter-hotels').onclick = function (e) {
		e.preventDefault();
		applyFilters();
	};
	document.getElementById('search-hotels').onclick = function (e) {
		e.preventDefault();
		applyFilters();
	};

	fetchHotels();
});
</script>
@endpush
