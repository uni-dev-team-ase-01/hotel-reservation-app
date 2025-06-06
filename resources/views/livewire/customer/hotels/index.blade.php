<?php

use function Livewire\Volt\{state, mount, computed, title};

title("Hotels");

state([
    "location" => "",
    "check_in" => "",
    "check_out" => "",
    "adults" => 0,
    "children" => 0,
    "rooms" => 0,
]);

$guestDisplay = computed(function () {
    $totalGuests = $this->adults + $this->children;
    $guestText = $totalGuests . " Guest" . ($totalGuests > 1 ? "s" : "");
    $roomText = $this->rooms . " Room" . ($this->rooms > 1 ? "s" : "");

    return $guestText . " " . $roomText;
});

$incrementAdults = fn () => ($this->adults = $this->adults + 1);
$decrementAdults = fn () => ($this->adults = $this->adults - 1);

$incrementChildren = fn () => ($this->children = $this->children + 1);
$decrementChildren = fn () => ($this->children = $this->children - 1);

$incrementRooms = fn () => ($this->rooms = $this->rooms + 1);
$decrementRooms = fn () => ($this->rooms = $this->rooms - 1);

$searchHotels = function () {
    // $params = array_filter(
    //     [
    //         "location" => $this->location,
    //         "check_in" => $this->check_in,
    //         "check_out" => $this->check_out,
    //         "adults" => $this->adults,
    //         "children" => $this->children,
    //         "rooms" => $this->rooms,
    //     ],
    //     fn ($value) => ! empty($value) || is_numeric($value),
    // );

    // return redirect()->to(route("hotels", $params));
};

?>

<main>
    <!-- =======================
Main Banner START -->
    <section class="pt-0">
        <div class="container">
            <!-- Background image -->
            <div class="rounded-3 p-3 p-sm-5" style="
                    background-image: url(assets/images/bg/05.jpg);
                    background-position: center center;
                    background-repeat: no-repeat;
                    background-size: cover;
                ">
                <!-- Banner title -->
                <div class="row my-2 my-xl-5">
                    <div class="col-md-8 mx-auto">
                        <h1 class="text-center text-white">
                            150 Hotels in New York
                        </h1>
                    </div>
                </div>

                <!-- Booking from START -->
                <form class="card shadow rounded-3 position-relative p-4 pe-md-5 pb-5 pb-md-4"
                    wire:submit="searchHotels">
                    <div class="row g-4 align-items-center">
                        <!-- Location -->
                        <div class="col-lg-4">
                            <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                <!-- Icon -->
                                <i class="bi bi-geo-alt fs-3 me-2 mt-2"></i>
                                <!-- Select input -->
                                <div class="flex-grow-1">
                                    <label class="form-label">Location</label>
                                    <select class="form-select js-choice" wire:model="location"
                                        data-search-enabled="true">
                                        <option value="">
                                            Select location
                                        </option>
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
                                <!-- Icon -->
                                <i class="bi bi-calendar fs-3 me-2 mt-2"></i>
                                <!-- Date input -->
                                <div class="form-control-border form-control-transparent form-fs-md">
                                    <label class="form-label">
                                        Check in - out
                                    </label>
                                    <input type="text" class="form-control flatpickr" data-mode="range"
                                        wire:model="check_in" placeholder="Select date" value="19 Sep to 28 Sep" />
                                </div>
                            </div>
                        </div>

                        <!-- Guest -->
                        <div class="col-lg-4">
                            <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                <!-- Icon -->
                                <i class="bi bi-person fs-3 me-2 mt-2"></i>
                                <!-- Dropdown input -->
                                <div class="w-100">
                                    <label class="form-label">
                                        Guests & rooms
                                    </label>
                                    <div class="dropdown guest-selector me-2">
                                        <input type="text" class="form-guest-selector form-control selection-result"
                                            value="{{ $this->guestDisplay }}" data-bs-auto-close="outside"
                                            data-bs-toggle="dropdown" />

                                        <!-- dropdown items -->
                                        <ul class="dropdown-menu guest-selector-dropdown">
                                            <!-- Adult -->
                                            <li class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-0">Adults</h6>
                                                    <small>
                                                        Ages 13 or above
                                                    </small>
                                                </div>

                                                <div class="hstack gap-1 align-items-center">
                                                    <button type="button" class="btn btn-link adult-remove p-0 mb-0"
                                                        wire:click="decrementAdults" @if($adults <= 1) disabled @endif>
                                                        <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                    </button>
                                                    <h6 class="guest-selector-count mb-0 adults">
                                                        {{ $adults }}
                                                    </h6>
                                                    <button type="button" class="btn btn-link adult-add p-0 mb-0"
                                                        wire:click="incrementAdults" @if($adults >= 10) disabled @endif>
                                                        <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                    </button>
                                                </div>
                                            </li>

                                            <!-- Divider -->
                                            <li class="dropdown-divider"></li>

                                            <!-- Child -->
                                            <li class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-0">Child</h6>
                                                    <small>Ages 13 below</small>
                                                </div>

                                                <div class="hstack gap-1 align-items-center">
                                                    <button type="button" wire:click="decrementChildren" @if($children <= 0) disabled @endif class="btn btn-link child-remove p-0 mb-0">
                                                        <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                    </button>
                                                    <h6 class="guest-selector-count mb-0 child">
                                                        {{ $children }}
                                                    </h6>
                                                    <button type="button" wire:click="incrementChildren" @if($children >= 8) disabled @endif class="btn btn-link child-add p-0 mb-0">
                                                        <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                    </button>
                                                </div>
                                            </li>

                                            <!-- Divider -->
                                            <li class="dropdown-divider"></li>

                                            <!-- Rooms -->
                                            <li class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-0">Rooms</h6>
                                                    <small>Max room 8</small>
                                                </div>

                                                <div class="hstack gap-1 align-items-center">
                                                    <button type="button" wire:click="decrementRooms" @if($rooms <= 1)
                                                        disabled @endif class="btn btn-link room-remove p-0 mb-0">
                                                        <i class="bi bi-dash-circle fs-5 fa-fw"></i>
                                                    </button>
                                                    <h6 class="guest-selector-count mb-0 rooms">
                                                        {{ $rooms }}
                                                    </h6>
                                                    <button type="button" wire:click="incrementRooms" @if($rooms >= 8)
                                                        disabled @endif class="btn btn-link room-add p-0 mb-0">
                                                        <i class="bi bi-plus-circle fs-5 fa-fw"></i>
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="btn-position-md-middle">
                        <a class="icon-lg btn btn-round btn-primary mb-0" href="#" wire:click.prevent="searchHotels"
                            wire:loading.attr="disabled">
                            <i class="bi bi-search fa-fw"></i>
                        </a>
                    </div>
                </form>
                <!-- Booking from END -->
            </div>
        </div>
    </section>
    <!-- =======================
Main Banner END -->

    <!-- =======================
Hotel list START -->
    <section class="pt-0">
        <div class="container">
            <!-- Tabs and alert START -->
            <div class="row mb-4">
                <div class="col-12">
                    <!-- Alert box START -->
                    <div class="alert alert-danger alert-dismissible d-flex justify-content-between align-items-center rounded-3 fade show mb-4 mb-0 pe-2"
                        role="alert">
                        <div class="d-flex">
                            <span class="alert-heading h5 mb-0 me-2">
                                <i class="bi bi-exclamation-octagon-fill"></i>
                            </span>
                            <span>
                                <strong class="alert-heading me-2">
                                    Covid Policy:
                                </strong>
                                You may require to present an RT-PCR negative
                                test report at the hotel
                            </span>
                        </div>
                        <button type="button" class="btn btn-link pb-0 text-end" data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="bi bi-x-lg text-dark"></i>
                        </button>
                    </div>
                    <!-- Alert box END -->

                    <!-- Buttons -->
                    <div class="hstack gap-3 justify-content-between justify-content-md-end">
                        <!-- Filter offcanvas button -->
                        <button class="btn btn-primary-soft btn-primary-check mb-0 d-xl-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar"
                            aria-controls="offcanvasSidebar">
                            <i class="fa-solid fa-sliders-h me-1"></i>
                            Show filters
                        </button>
                        <!-- tabs -->
                        <ul class="nav nav-pills nav-pills-dark" id="tour-pills-tab" role="tablist">
                            <!-- Tab item -->
                            <li class="nav-item">
                                <a class="nav-link rounded-start rounded-0 mb-0 active" href="hotel-list.html">
                                    <i class="bi fa-fw bi-list-ul"></i>
                                </a>
                            </li>
                            <!-- Tab item -->
                            <li class="nav-item">
                                <a class="nav-link rounded-end rounded-0 mb-0" href="hotel-grid.html">
                                    <i class="bi fa-fw bi-grid-fill"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Tabs and alert END -->

            <div class="row">
                <!-- Left sidebar START -->
                <aside class="col-xl-4 col-xxl-3">
                    <!-- Responsive offcanvas body START -->
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
                            <form class="rounded-3 shadow">
                                <!-- Hotel type START -->
                                <div class="card card-body rounded-0 rounded-top p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Hotel Type</h6>
                                    <!-- Hotel Type group -->
                                    <div class="col-12">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="hotelType1" />
                                            <label class="form-check-label" for="hotelType1">
                                                All
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="hotelType2" />
                                            <label class="form-check-label" for="hotelType2">
                                                Family
                                            </label>
                                        </div>
                                    <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="hotelType2" />
                                            <label class="form-check-label" for="hotelType2">
                                                Couple
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Hotel type END -->

                                <hr class="my-0" />
                                <!-- Divider -->

                                <!-- Price range START -->
                                <div class="card card-body rounded-0 p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Price range</h6>
                                    <!-- Price range group -->
                                    <div class="col-12">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="priceRange1" />
                                            <label class="form-check-label" for="priceRange1">
                                                Up to $500
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="priceRange2" />
                                            <label class="form-check-label" for="priceRange2">
                                                $500 - $1000
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="priceRange3" />
                                            <label class="form-check-label" for="priceRange3">
                                                $1000 - $1500
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="priceRange4" />
                                            <label class="form-check-label" for="priceRange4">
                                                $1500 - $2000
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="priceRange5" />
                                            <label class="form-check-label" for="priceRange5">
                                                $2000+
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Price range END -->

                                <hr class="my-0" />
                                <!-- Divider -->

                                <!-- Popular type START -->
                                <div class="card card-body rounded-0 p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Popular Type</h6>
                                    <!-- Popular Type group -->
                                    <div class="col-12">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="popolarType1" />
                                            <label class="form-check-label" for="popolarType1">
                                                Free Breakfast Included
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="popolarType2" />
                                            <label class="form-check-label" for="popolarType2">
                                                Pay At Hotel Available
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="popolarType3" />
                                            <label class="form-check-label" for="popolarType3">
                                                Free Cancellation Available
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Popular type END -->

                                <hr class="my-0" />
                                <!-- Divider -->

                                <!-- Customer Rating START -->
                                <div class="card card-body rounded-0 p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Customer Rating</h6>
                                    <!-- Customer Rating group -->
                                    <ul class="list-inline mb-0 g-3">
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-c1" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-c1">
                                                3+
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-c2" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-c2">
                                                3.5+
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-c3" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-c3">
                                                4+
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-c4" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-c4">
                                                4.5+
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Customer Rating END -->

                                <hr class="my-0" />
                                <!-- Divider -->

                                <!-- Rating Star START -->
                                <div class="card card-body rounded-0 p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Rating Star</h6>
                                    <!-- Rating Star group -->
                                    <ul class="list-inline mb-0 g-3">
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-6" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-6">
                                                1
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-7" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-7">
                                                2
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-8" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-8">
                                                3
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-15" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-15">
                                                4
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        </li>
                                        <!-- Item -->
                                        <li class="list-inline-item mb-0">
                                            <input type="checkbox" class="btn-check" id="btn-check-16" />
                                            <label class="btn btn-sm btn-light btn-primary-soft-check"
                                                for="btn-check-16">
                                                5
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Rating Star END -->

                                <hr class="my-0" />
                                <!-- Divider -->

                                <!-- Amenities START -->
                                <div class="card card-body rounded-0 rounded-bottom p-4">
                                    <!-- Title -->
                                    <h6 class="mb-2">Amenities</h6>
                                    <!-- Amenities group -->
                                    <div class="col-12">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType1" />
                                            <label class="form-check-label" for="amenitiesType1">
                                                All
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType2" />
                                            <label class="form-check-label" for="amenitiesType2">
                                                Air Conditioning
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType3" />
                                            <label class="form-check-label" for="amenitiesType3">
                                                Bar
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType4" />
                                            <label class="form-check-label" for="amenitiesType4">
                                                Bonfire
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType5" />
                                            <label class="form-check-label" for="amenitiesType5">
                                                Business Services
                                            </label>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="amenitiesType6" />
                                            <label class="form-check-label" for="amenitiesType6">
                                                Caretaker
                                            </label>
                                        </div>

                                        <!-- Collapse body -->
                                        <div class="multi-collapse collapse" id="amenitiesCollapes">
                                            <!-- Checkbox -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="amenitiesType7" />
                                                <label class="form-check-label" for="amenitiesType7">
                                                    Dining
                                                </label>
                                            </div>
                                            <!-- Checkbox -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="amenitiesType8" />
                                                <label class="form-check-label" for="amenitiesType8">
                                                    Free Internet
                                                </label>
                                            </div>
                                            <!-- Checkbox -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="amenitiesType9" />
                                                <label class="form-check-label" for="amenitiesType9">
                                                    Hair nets
                                                </label>
                                            </div>
                                            <!-- Checkbox -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="amenitiesType10" />
                                                <label class="form-check-label" for="amenitiesType10">
                                                    Masks
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Collapse button -->
                                        <a class="p-0 mb-0 mt-2 btn-more d-flex align-items-center collapsed"
                                            data-bs-toggle="collapse" href="#amenitiesCollapes" role="button"
                                            aria-expanded="false" aria-controls="amenitiesCollapes">
                                            See
                                            <span class="see-more ms-1">
                                                more
                                            </span>
                                            <span class="see-less ms-1">
                                                less
                                            </span>
                                            <i class="fa-solid fa-angle-down ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Amenities END -->
                            </form>
                            <!-- Form End -->
                        </div>
                        <!-- Buttons -->
                        <div class="d-flex justify-content-between p-2 p-xl-0 mt-xl-4">
                            <button class="btn btn-link p-0 mb-0">
                                Clear all
                            </button>
                            <button class="btn btn-primary mb-0">
                                Filter Result
                            </button>
                        </div>
                    </div>
                    <!-- Responsive offcanvas body END -->
                </aside>
                <!-- Left sidebar END -->

                <!-- Main content START -->
                <div class="col-xl-8 col-xxl-9">
                    <div class="vstack gap-4">
                        <!-- Card item START -->
                        <div class="card shadow p-2">
                            <div class="row g-0">
                                <!-- Card img -->
                                <div class="col-md-5 position-relative">
                                    <!-- Overlay item -->
                                    <div class="position-absolute top-0 start-0 z-index-1 m-2">
                                        <div class="badge text-bg-danger">
                                            30% Off
                                        </div>
                                    </div>

                                    <!-- Slider START -->
                                    <div class="tiny-slider arrow-round arrow-xs arrow-dark overflow-hidden rounded-2">
                                        <div class="tiny-slider-inner" data-autoplay="false" data-arrow="true"
                                            data-dots="false" data-items="1">
                                            <!-- Image item -->
                                            <div>
                                                <img src="assets/images/category/hotel/4by3/04.jpg" alt="Card image" />
                                            </div>

                                            <!-- Image item -->
                                            <div>
                                                <img src="assets/images/category/hotel/4by3/02.jpg" alt="Card image" />
                                            </div>

                                            <!-- Image item -->
                                            <div>
                                                <img src="assets/images/category/hotel/4by3/03.jpg" alt="Card image" />
                                            </div>

                                            <!-- Image item -->
                                            <div>
                                                <img src="assets/images/category/hotel/4by3/01.jpg" alt="Card image" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slider END -->
                                </div>

                                <!-- Card body -->
                                <div class="col-md-7">
                                    <div class="card-body py-md-2 d-flex flex-column h-100 position-relative">
                                        <!-- Rating and buttons -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-0 small">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small">
                                                    <i class="fa-solid fa-star-half-alt text-warning"></i>
                                                </li>
                                            </ul>

                                            <ul class="list-inline mb-0 z-index-2">
                                                <!-- Heart icon -->
                                                <li class="list-inline-item">
                                                    <a href="#" class="btn btn-sm btn-round btn-light">
                                                        <i class="fa-solid fa-fw fa-heart"></i>
                                                    </a>
                                                </li>
                                                <!-- Share icon -->
                                                <li class="list-inline-item dropdown">
                                                    <!-- Share button -->
                                                    <a href="#" class="btn btn-sm btn-round btn-light" role="button"
                                                        id="dropdownShare" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="fa-solid fa-fw fa-share-alt"></i>
                                                    </a>
                                                    <!-- dropdown button -->
                                                    <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow rounded"
                                                        aria-labelledby="dropdownShare">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fab fa-twitter-square me-2"></i>
                                                                Twitter
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fab fa-facebook-square me-2"></i>
                                                                Facebook
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fab fa-linkedin me-2"></i>
                                                                LinkedIn
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fa-solid fa-copy me-2"></i>
                                                                Copy link
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Title -->
                                        <h5 class="card-title mb-1">
                                            <a href="hotel-detail.html">
                                                Courtyard by Marriott New York
                                            </a>
                                        </h5>
                                        <small>
                                            <i class="bi bi-geo-alt me-2"></i>
                                            5855 W Century Blvd, Los Angeles -
                                            90045
                                        </small>
                                        <!-- Amenities -->
                                        <ul class="nav nav-divider mt-3">
                                            <li class="nav-item">
                                                Air Conditioning
                                            </li>
                                            <li class="nav-item">Wifi</li>
                                            <li class="nav-item">Kitchen</li>
                                            <li class="nav-item">
                                                <a href="#" class="mb-0 text-primary">
                                                    More+
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- List -->
                                        <ul class="list-group list-group-borderless small mb-0 mt-2">
                                            <li class="list-group-item d-flex text-success p-0">
                                                <i class="bi bi-patch-check-fill me-2"></i>
                                                Free Cancellation till 7 Jan
                                                2022
                                            </li>
                                            <li class="list-group-item d-flex text-success p-0">
                                                <i class="bi bi-patch-check-fill me-2"></i>
                                                Free Breakfast
                                            </li>
                                        </ul>

                                        <!-- Price and Button -->
                                        <div
                                            class="d-sm-flex justify-content-sm-between align-items-center mt-3 mt-md-auto">
                                            <!-- Button -->
                                            <div class="d-flex align-items-center">
                                                <h5 class="fw-bold mb-0 me-1">
                                                    $750
                                                </h5>
                                                <span class="mb-0 me-2">
                                                    /day
                                                </span>
                                                <span class="text-decoration-line-through mb-0">
                                                    $1000
                                                </span>
                                            </div>
                                            <!-- Price -->
                                            <div class="mt-3 mt-sm-0">
                                                <a href="#" class="btn btn-sm btn-dark mb-0 w-100">
                                                    Select Room
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card item END -->

                        <!-- Pagination -->
                        <nav class="d-flex justify-content-center" aria-label="navigation">
                            <ul id="pagination"
                                class="pagination pagination-primary-soft d-inline-block d-md-flex rounded mb-0"></ul>

                            <li class="page-item mb-0">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            </li>
                            <li class="page-item mb-0">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item mb-0 active">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item mb-0">
                                <a class="page-link" href="#">..</a>
                            </li>
                            <li class="page-item mb-0">
                                <a class="page-link" href="#">6</a>
                            </li>
                            <li class="page-item mb-0">
                                <a class="page-link" href="#">
                                    <i class="fa-solid fa-angle-right"></i>
                                </a>
                            </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Main content END -->
            </div>
            <!-- Row END -->
        </div>
    </section>
    <!-- =======================
Hotel list END -->
</main>
<script>
    async function fetchHotels(page = 1) {
        try {
            const response = await fetch(`/api/hotels?page=${page}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            const hotels = result.data || [];
            const container = document.querySelector('.vstack');

            container.innerHTML = ''; // Clear previous results

            if (hotels.length === 0) {
                container.innerHTML = `
          <div class="card shadow p-2 mb-3">
            <div class="row g-0">
              <div class="col-md-5 position-relative">
                <img src="assets/images/category/hotel/4by3/04.jpg" 
                     class="img-fluid rounded-start" alt="No hotels available" />
              </div>
              <div class="col-md-7">
                <div class="card-body py-md-2 d-flex flex-column h-100 position-relative">
                  <h5 class="card-title">No hotels available</h5>
                  <p class="card-text">Sorry, we couldn't find any hotels at the moment.</p>
                </div>
              </div>
            </div>
          </div>`;
                renderPagination(0, 0);
                return;
            }

            hotels.forEach(hotel => {
                // Prepare star ratings HTML
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

                // Use first image or fallback image
                const imgSrc = hotel.images && hotel.images.length > 0
                    ? hotel.images[0]
                    : 'assets/images/category/hotel/4by3/04.jpg';

                // Discount badge if any
                const discountBadge = hotel.discount ? `
          <div class="position-absolute top-0 start-0 z-index-1 m-2">
            <div class="badge text-bg-danger">${hotel.discount}% Off</div>
          </div>` : '';

                // Amenities limited to 3
                const amenities = hotel.amenities && hotel.amenities.length > 0
                    ? hotel.amenities.slice(0, 3).map(am => `<li class="nav-item">${am}</li>`).join('')
                    : '';

                // Hotel card HTML
                const cardHTML = `
          <div class="card shadow p-2 mb-3">
            <div class="row g-0">
              <div class="col-md-5 position-relative">
                ${discountBadge}
                <img src="${imgSrc}" class="img-fluid rounded-start" alt="${hotel.name}">
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
                    <a href="${hotel.detail_url || '#'}">${hotel.name}</a>
                  </h5>

                  <small><i class="bi bi-geo-alt me-2"></i>${hotel.address || 'Location not available'}</small>

                  <ul class="nav nav-divider mt-3">
                    ${amenities ||  'Location not available'} 
             
                  </ul>

                  <ul class="list-group list-group-borderless small mb-0 mt-2">
                    ${hotel.free_cancellation ? `<li class="list-group-item d-flex text-success p-0">
                      <i class="bi bi-patch-check-fill me-2"></i>Free Cancellation</li>` : ''}
                    ${hotel.free_breakfast ? `<li class="list-group-item d-flex text-success p-0">
                      <i class="bi bi-patch-check-fill me-2"></i>Free Breakfast</li>` : ''}
                  </ul>

                  <div class="d-sm-flex justify-content-sm-between align-items-center mt-3 mt-md-auto">
                    <div class="d-flex align-items-center">
                      <h5 class="fw-bold mb-0 me-1">$${hotel.price_per_day || 'N/A'}</h5>
                      <span class="mb-0 me-2">/day</span>
                      ${hotel.original_price ? `<span class="text-decoration-line-through mb-0">$${hotel.original_price}</span>` : ''}
                    </div>
                    <div class="mt-3 mt-sm-0">
                      <a href="${hotel.booking_url || '#'}" class="btn btn-sm btn-dark mb-0 w-100">Select Room</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `;

                container.insertAdjacentHTML('beforeend', cardHTML);
            });

            renderPagination(result.current_page, result.last_page);
        } catch (error) {
            console.error('Error fetching hotels:', error);
            document.querySelector('.vstack').innerHTML = `<div class="alert alert-danger">Failed to load hotels.</div>`;
            renderPagination(0, 0);
        }
    }

    function renderPagination(current, last) {
        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        pagination.innerHTML = '';

        if (last < 1) return;

        for (let i = 1; i <= last; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === current ? 'active' : ''}`;

            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = i;

            a.addEventListener('click', e => {
                e.preventDefault();
                fetchHotels(i);
            });

            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    document.addEventListener('DOMContentLoaded', () => fetchHotels());
</script>