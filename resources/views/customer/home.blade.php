@extends("layouts.app")

@section("title", "Booking Landing Page")

@section("content")
    <section class="pt-3 pt-lg-5">
        <div class="container">
            <!-- Content and Image START -->
            <div class="row g-4 g-lg-5">
                <!-- Content -->
                <div class="col-lg-6 position-relative mb-4 mb-md-0">
                    <!-- Title -->
                    <h1 class="mb-4 mt-md-5 display-5">
                        Find the top
                        <span class="position-relative z-index-9">
                            Hotels nearby.
                            <!-- SVG START -->
                            <span
                                class="position-absolute top-50 start-50 translate-middle z-index-n1 d-none d-md-block mt-4">
                                <svg width="390.5px" height="21.5px" viewBox="0 0 445.5 21.5">
                                    <path class="fill-primary opacity-7"
                                        d="M409.9,2.6c-9.7-0.6-19.5-1-29.2-1.5c-3.2-0.2-6.4-0.2-9.7-0.3c-7-0.2-14-0.4-20.9-0.5 c-3.9-0.1-7.8-0.2-11.7-0.3c-1.1,0-2.3,0-3.4,0c-2.5,0-5.1,0-7.6,0c-11.5,0-23,0-34.5,0c-2.7,0-5.5,0.1-8.2,0.1 c-6.8,0.1-13.6,0.2-20.3,0.3c-7.7,0.1-15.3,0.1-23,0.3c-12.4,0.3-24.8,0.6-37.1,0.9c-7.2,0.2-14.3,0.3-21.5,0.6 c-12.3,0.5-24.7,1-37,1.5c-6.7,0.3-13.5,0.5-20.2,0.9C112.7,5.3,99.9,6,87.1,6.7C80.3,7.1,73.5,7.4,66.7,8 C54,9.1,41.3,10.1,28.5,11.2c-2.7,0.2-5.5,0.5-8.2,0.7c-5.5,0.5-11,1.2-16.4,1.8c-0.3,0-0.7,0.1-1,0.1c-0.7,0.2-1.2,0.5-1.7,1 C0.4,15.6,0,16.6,0,17.6c0,1,0.4,2,1.1,2.7c0.7,0.7,1.8,1.2,2.7,1.1c6.6-0.7,13.2-1.5,19.8-2.1c6.1-0.5,12.3-1,18.4-1.6 c6.7-0.6,13.4-1.1,20.1-1.7c2.7-0.2,5.4-0.5,8.1-0.7c10.4-0.6,20.9-1.1,31.3-1.7c6.5-0.4,13-0.7,19.5-1.1c2.7-0.1,5.4-0.3,8.1-0.4 c10.3-0.4,20.7-0.8,31-1.2c6.3-0.2,12.5-0.5,18.8-0.7c2.1-0.1,4.2-0.2,6.3-0.2c11.2-0.3,22.3-0.5,33.5-0.8 c6.2-0.1,12.5-0.3,18.7-0.4c2.2-0.1,4.4-0.1,6.7-0.1c11.5-0.1,23-0.2,34.6-0.4c7.2-0.1,14.4-0.1,21.6-0.1c12.2,0,24.5,0.1,36.7,0.1 c2.4,0,4.8,0.1,7.2,0.2c6.8,0.2,13.5,0.4,20.3,0.6c5.1,0.2,10.1,0.3,15.2,0.4c3.6,0.1,7.2,0.4,10.8,0.6c10.6,0.6,21.1,1.2,31.7,1.8 c2.7,0.2,5.4,0.4,8,0.6c2.9,0.2,5.8,0.4,8.6,0.7c0.4,0.1,0.9,0.2,1.3,0.3c1.1,0.2,2.2,0.2,3.2-0.4c0.9-0.5,1.6-1.5,1.9-2.5 c0.6-2.2-0.7-4.5-2.9-5.2c-1.9-0.5-3.9-0.7-5.9-0.9c-1.4-0.1-2.7-0.3-4.1-0.4c-2.6-0.3-5.2-0.4-7.9-0.6 C419.7,3.1,414.8,2.9,409.9,2.6z" />
                                </svg>
                            </span>
                            <!-- SVG END -->
                        </span>
                    </h1>
                    <!-- Info -->
                    <p class="mb-4">
                        We bring you not only a stay option, but an experience
                        in your budget to enjoy the luxury.
                    </p>

                    <!-- Buttons -->
                    <div class="hstack gap-4 flex-wrap align-items-center">
                        <!-- Button -->
                        <a href="#" class="btn btn-primary-soft mb-0">
                            Discover Now
                        </a>
                        <!-- Story button -->
                        <a data-glightbox="" data-gallery="office-tour" href="https://www.youtube.com/embed/tXHviS-4ygo"
                            class="d-block">
                            <!-- Avatar -->
                            <div class="avatar avatar-md z-index-1 position-relative me-2">
                                <img class="avatar-img rounded-circle" src="assets/images/avatar/12.jpg" alt="avatar" />
                                <!-- Video button -->
                                <div
                                    class="btn btn-xs btn-round btn-white shadow-sm position-absolute top-50 start-50 translate-middle z-index-9 mb-0">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <div class="align-middle d-inline-block">
                                <h6 class="fw-normal small mb-0">
                                    Watch our story
                                </h6>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-6 position-relative">
                    <img src="assets/images/bg/06.jpg" class="rounded" alt="" />

                    <!-- Svg decoration -->
                    <figure class="position-absolute end-0 bottom-0">
                        <svg width="163px" height="163px" viewBox="0 0 163 163">
                            <path class="fill-warning"
                                d="M145.6,66.2c-0.9-0.3-1.6,0.2-2.1-0.4c-0.5-0.7-1-1.5-1-2.4c0-3.1,0.1-6.2,0-9.3c0-0.7,0.3-1.3,0.5-1.9 c0.8-1.6,1.6-3.2,2.7-4.5c0.5-0.6,1.2-1.2,2-1.5c0.4-0.2,0.8,0.4,1.3-0.1c0.4-0.4,1,0.7,1.6,0.7c0.4,1-0.4,1.5-1,2.1 c0.7,0.3,1.4,0.3,2.1,0.7c0.6,0.4,1.2,0.7,1,1.5c-0.2,1,0.6,1.3,1,1.9c-0.2,0.3-0.6,0.4-0.5,0.8c1.2,3.2,0.3,5.4-0.7,8.1 c-0.3,0.7-0.7,1.6-0.7,2.2c-0.1,1.5-1.2,2.7-1.4,4.1c-0.2,1.1-0.9,1.7-2.1,1.6c-0.2,0-0.4,0.5-1,0.4c-0.2-0.2-0.7-0.5-0.7-0.8 c0-1-0.1-1.7-1.1-2.1C145.5,67.2,145.6,66.6,145.6,66.2" />
                            <path class="fill-warning"
                                d="M94.3,143.5c1.1,0.3,2.4-0.5,3.2,0.7c-0.4,0.7-0.7,1.4-1,2.1c0.5,0.5,0.7,0.2,1.2,0.1c1.6-0.6,2-0.4,2.5,1.2 c0.1,0.2,0,0.6,0.3,0.6c1.8,0.4,1.4,2.2,2.1,3.2c-0.8,0.9,0.5,1.8,0.1,2.6c-0.5,0.8-0.3,2-1.3,2.6c-0.3,0.2-0.1,0.5-0.2,0.7 c-0.3,2.1-1.2,3.7-3.4,4.4c-0.3,0.1-0.4,0.6-1,0.4c-0.3-0.6-0.6-1.3-1-1.9c-0.5-0.2-1.5,0.3-1.4-1h-3c-0.2-1.4,0-2.9-1.1-3.9 c-0.1-0.1-0.1-0.4,0-0.5c0.7-1.2,0.2-2.6,0.7-3.8c0.3-0.6,0.4-1,0.1-1.6c-0.9-1.3,0-2.4,0.7-3.3C92.5,145,93.4,144.3,94.3,143.5" />
                            <path class="fill-warning"
                                d="M119.6,77.3c-0.4,0.8-1.1,0.6-2,0.8c0.2,1.1-0.4,2.2,0.5,3.3c-0.8,0-0.8,0-1.2-0.3c-0.6,0.3-0.8,1-1.2,1.6 c0.1-1.9-0.6-3.2-2-4.1c-0.6-0.1-0.7,0.3-1,0.5c-1-1.9-1-2.8-0.2-7.7c0.4-2.5,1.7-4.6,3.6-6.8c0.6-0.1,1.5,1.5,2.3,0 c0.8,1.5-0.7,2.3-0.8,3.7c0.8-0.4,1.6-0.7,2.4-0.4c0.4,0.4-0.1,1,0.3,1.4c0.8,0.6,1.4,1.3,0.4,2.3c1.1,0.8-0.3,1.5-0.1,2.4 c0.2,0.8,0,1.7,0,2.5c-0.8-0.2-1-1.1-1.8-1C118.2,76.4,119.5,76.5,119.6,77.3" />
                            <path class="fill-warning"
                                d="M25,131c-0.3-0.6-1.2-0.3-1.7-0.5v-1.2c-0.1-0.1-0.1-0.2-0.2-0.2c-1.4,0.5-2.2-1-3.4-1.2 c-1.2-0.1-1.9-1-2.1-2.2c-0.1-0.5,0.1-0.8,0.5-1.1c-2-1.7-0.8-3.4-0.1-5.1c0.8-2.2,2.6-2.5,4.6-2.4c0.4,1.1,0.2,2-0.6,2.7 c1.5,1,2-0.5,3-0.8c0.3,0.6,0.6,1.2,0.9,1.6c0,0.6-0.8,0.8-0.4,1.4c0.7,0.8,0.9-0.5,1.7-0.3c1,0.9,0.9,2.2,0.8,3.4 c0.4,0.1,0.6,0.2,1,0.3c-0.1,0.6-1,0.8-1,1.5c0,0.8,0.8,0.2,1,0.7C27.7,128.8,26.9,130.3,25,131" />
                            <path class="fill-warning"
                                d="M84.9,95H87c0.4,0.4,0.3,1.6-0.3,2.8c1.2,1,1.7-0.5,2.4-0.8c0.8,0,0.8,0.6,1.2,0.7c0.2,0.8-0.7,0.9-0.4,1.7 c0.5,0.3,1.7,0,1.9,0.9c0.2,0.7,0.3,1.5-0.5,2.1c0.3,0.1,0.6,0.2,0.9,0.3c-0.1,0.7-1.1,1.3-0.5,2.2c-1.1,1.5-3,2.1-4.4,3.3 c-0.3,0.2-0.8,1-1.5,0.5c-0.3-0.4,0.4-0.4,0.3-0.8c-0.7-0.5-1.6,0.1-2.4-0.3c-0.2-0.6,0.1-1.4-0.8-1.8c-1.1,0.5-2.2,0.7-3.2-0.8 c1.3-0.8,3-1.1,3.2-3c-1,0-1.7,0.9-2.7,1c-0.2-0.2-0.5-0.4-0.8-0.7c-0.1-0.1,0.1-0.1,0.2-0.3c0.6-1.1,2.4-1,2.5-2.5 c1.2-0.5,1.1-1.7,1.3-2.5C83.8,96.3,84.3,95.7,84.9,95" />
                            <path class="fill-warning"
                                d="M41.2,153.9c0.3-0.7,0.9-0.8,0.4-1.6c-0.3-0.3-1.1,0.2-1.8-0.2c0-0.2-0.1-0.4-0.1-0.7c-0.1-0.1-0.2-0.2-0.3,0 c-0.3,0.4-0.7,0.4-1.1,0.4c-1.3,0-1.5-0.4-1.6-1.7c0-0.6,0.4-0.8,0.5-1.4c-0.4,0-0.8-0.1-1.4-0.1c-0.4-1.9,0.7-3.6,1.1-5.4 c0.2-0.9,1.6-1.3,2.7-1.3c0.4,0.2,0.3,0.6,0.3,0.7c0.2,0.4,0.3,0.3,0.4,0.1c0.6-0.5,1.3-0.6,1.7,0.1c0.5,0.7,1.1,0.6,1.8,0.7 c0.4,0.4,0.1,0.8,0.2,1.2c0.3,0.4,0.8,0.2,1.3,0.3c1,0.7,0.5,2.1,1.3,2.9C43.8,152.3,43.1,153.1,41.2,153.9" />
                            <path class="fill-warning"
                                d="M70.9,43.4c-0.3-1.4-1.2-1.8-2.6-1.5c-1.2-2.3-0.8-4.8-0.5-7.2c0.1-0.5,0.4-1.1,0.3-1.7 c-0.2-1.1,0.5-1.9,0.6-2.9c0.1-0.7,1.3-0.9,2-1.3c0.9,0.8,0.9,0.8,1.2,2c0.3,0,0.6,0,0.4,0c1.3,0,0.8,0.9,1.3,1.2 c0.3,0.1,0.8,0.5,0.7,1c-0.2,0.8,1,1.4,0.5,2.1c-0.5,0.7-0.2,1.5-0.5,2.1c-0.8,1.5-1,3.2-1.5,4.8C72.6,43.1,72,43.4,70.9,43.4" />
                            <path class="fill-warning"
                                d="M125.4,118.4c-0.4-0.3-0.6-0.7-1.3-0.8c-1.6-0.1-1.6-0.2-1.9-1.9c-1.1-0.4-2.2,0-3.2,0.4 c-0.5-0.5-0.2-0.9-0.4-1.4c0.4-0.1,0.7-0.2,1-0.4v-3c-0.5,0.2-1,0.3-1.7,0.5c-0.3,0-0.4-0.6-0.8-0.7c0.6-1.5,1.8-2.4,2.8-3.5 c1.3,0.3,2.6-1.1,3.8,0.4c0,0.1-0.1,1.8,0,2.1c-0.2,0-0.5,0.1-0.7,0.1c-0.2,0-0.3,0-0.5,0c-0.4,0.4-0.1,1.1-0.7,1.5 c1.3-0.5,2.4-1,3.3-2c0.4,0.4,0.7,0.8,1.4,0.6c-1.1,0.9,0.4,2.1-1,2.9c1,0,1.1-0.6,1.5-0.8c0.4-0.1,0.8-0.1,1.2-0.2 c0.5,1,1.1,1.8,0.6,3c-0.7,0.6-2.2,0.4-2.5,2.1c1.2-0.2,1.9-0.9,2.5-1.5c0.7,0.1,0.7,0.5,0.6,0.8c-1.3-0.1-1.2,1.5-2.3,1.9 c-0.9,0.3-1.6,1-2.7,1.8C124.7,119.5,125.1,119,125.4,118.4" />
                            <path class="fill-warning"
                                d="M101.7,41c-0.3,0.3-0.6,0.6-0.9,0.9c0.9,0.6-0.9,1.6,0.4,2.1c-2,2.3-2,2.4-2.1,4.8h-2.4c-0.2-0.1,0-0.5-0.2-0.8 c-2.4-0.3-2.9-0.8-3-3.3c0-0.6,0.2-1.4-0.5-1.8c0.5-0.7,0.2-1.6,0.7-2.4c1-1.5,2.3-2.7,3.5-3.9c0.5-0.2,1-0.1,1.4,0 c0.2,1-1.1,1.6-0.2,2.6c0.3-0.4,0.6-0.8,0.9-1.3C100.2,39.2,101.7,39.5,101.7,41" />
                            <path class="fill-warning"
                                d="M140.4,5.4c-0.4,0.6-1.2-0.1-1.5,0.6c0.7,0.4,1.5,0.1,2.3,0.2c0.3,1.1,0.9,2.1,1.3,3.2c0.9,2.4,0.3,4.4-0.6,6.6 c-0.4,0.9-0.9,1.2-1.9,1c-0.2-0.5-0.5-1.2-0.9-1.9c-0.6-0.2-1.5,0-1.9-1c0.1-1.7,0.1-3.6-1.1-5.2c0.4-0.7,0.7-1.3,1.1-1.9 c-0.3-0.1-0.6-0.2-1-0.4c0.2-0.8,0.5-1.6,1.3-2.3h2.2C140,4.6,140.5,4.8,140.4,5.4" />
                            <path class="fill-warning"
                                d="M65.7,68.8c-0.4,0.6-0.9,0.4-1.4,0.4c-1.2-1.1-0.4-2.9-1.4-4.1c1.5-3,1.5-3,4.1-4.2c0.5,0.1,0.8,0.5,1,1 c0.1,0.6-0.8,0.7-0.5,1.3c0.5,0.6,0.9,0.2,1.2-0.2c1.5,0.6,1.1,2.5,2.4,3.3c-0.1,1.1,0.2,2.2-0.2,3.2L69,72.2c-0.3,0-0.7,0-1,0 c-0.3-0.5-0.9-2.2-0.8-2.4C66.7,69.6,66.2,69.2,65.7,68.8" />
                            <path class="fill-warning"
                                d="M37.5,69.7c-0.5,0.2,0,0.9-0.4,1c-0.7,0.2-1-0.2-1.2-0.6c-0.4-0.7,0.1-1.6-0.2-2.2c-0.5-0.7-0.6-1.2-0.1-2 c0.5-0.6,0.2-1.5,0.6-2.3c0.9-2,0.9-2.1,3-2.1c0.1,0.1,0.2,0.1,0.2,0.2c0,0.3,0,0.7,0,1.1c0.7,0.4,1.7,0.1,2.1,1.3 c0.3,0.9,1.2,1.5,1,2.7c-0.2,0.9,0.1,1.8-0.8,2.5c-0.4,0.4-0.8,1.1-0.8,2c0,0.6-0.5,1-1.2,1.1c-0.6,0.1-1-0.3-1.2-0.7 C38,71,37.8,70.3,37.5,69.7" />
                            <path class="fill-warning"
                                d="M53.9,87.8c0.7,0,1.4,0,2.1,0c0.5,0.3,0.1,1,0.4,1.4c0.4,0.3,0.8,0.1,1.2,0.2c0.6,1.2,1.4,2.4,1.7,3.6 c0.4,1.4-0.2,2.7-0.7,4c-1,0.4-1.5-0.4-2.1-0.9c-0.7,0-1.4,0-2.1,0c-0.4-1-0.8-1.8-2.1-1.5c-0.6-0.7,0.2-1.8-0.7-2.3 c0.5-0.6,0.9-1.3,1-2.1C52.8,89.2,53.2,88.5,53.9,87.8" />
                            <path class="fill-warning"
                                d="M0.1,95.7c0.9-1.3,2.3-1.7,3.8-1.8c1,1.2-0.7,1.5-0.8,2.3c1.1,1,2-0.7,3.1,0c0.6,0.6-0.2,0.8-0.3,1.2 c0.4,0.5,1,0,1.4,0.3c0.4,1.1-0.3,2.3,0.6,3.3c-0.8,0.8-0.7,2.2-1.9,2.8c-1.1-0.2-1.8-1-2.6-1.7c-0.7-0.6-1.9-0.5-2.6-1.9 C1,98.9-0.4,97.4,0.1,95.7" />
                            <path class="fill-warning"
                                d="M155.5,91.5c-0.9-0.5-1.7-0.7-2.3-1.6c0.4-0.2,0.8-0.5,1.2-0.7c-1.2-0.4-2.1,0.7-3.1,0c0.6-1,1.8-1,2.5-1.7 c0.1-0.6-0.3-0.6-0.7-0.7c-0.7-0.2-0.9,0.9-1.6,0.5c-0.3-0.3-0.4-0.7-0.1-0.9c1.7-1,3-2.3,4.5-3.5c0.9-0.7,1.1-0.9,2.5-1.2 c-0.1,0.5-0.6,0.7-0.9,1.1c0.7,0.7,1.3,0.1,1.9-0.2c0.1,1.1,0.9,1.9,0.5,3.4C158.3,87.4,157.4,89.8,155.5,91.5" />
                        </svg>
                    </figure>

                    <!-- Support guid -->
                    <div class="position-absolute top-0 end-0 z-index-1 mt-n4">
                        <div class="bg-blur border border-light rounded-3 text-center shadow-lg p-3">
                            <!-- Title -->
                            <i class="bi bi-headset text-danger fs-3"></i>
                            <h5 class="text-dark mb-1">24 / 7</h5>
                            <h6 class="text-dark fw-light small mb-0">
                                Guide Supports
                            </h6>
                        </div>
                    </div>

                    <!-- Round image group -->
                    <div
                        class="vstack gap-5 align-items-center position-absolute top-0 start-0 d-none d-md-flex mt-4 ms-n3">
                        <img class="icon-lg shadow-lg border border-3 border-white rounded-circle"
                            src="assets/images/category/hotel/4by3/11.jpg" alt="avatar" />
                        <img class="icon-xl shadow-lg border border-3 border-white rounded-circle"
                            src="assets/images/category/hotel/4by3/12.jpg" alt="avatar" />
                    </div>
                </div>
            </div>
            <!-- Content and Image END -->

            <!-- Search START -->
            <div class="row">
                <div class="col-xl-10 position-relative mt-n3 mt-xl-n9">
                    <!-- Title -->
                    <h6 class="d-none d-xl-block mb-3">Check Availability</h6>

                    <!-- Booking from START -->
                    <form id="home-search-form" class="card shadow rounded-3 position-relative p-4 pe-md-5 pb-5 pb-md-4">
                        <div class="row g-4 align-items-center">
                            <!-- Location -->
                            <div class="col-lg-4">
                                <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                    <!-- Icon -->
                                    <i class="bi bi-geo-alt fs-3 me-2 mt-2"></i>
                                    <!-- Select input -->
                                    <div class="flex-grow-1">
                                        <label class="form-label">
                                            Location
                                        </label>
                                        <select class="form-select js-choice" data-search-enabled="true" name="location">
                                            <option value="">Select location</option>
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
                                        <input type="text" class="form-control flatpickr"
                                            placeholder="Select date" /> <!-- Value will be set by flatpickr or JS -->
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
                                                value="2 Guests 1 Room" data-bs-auto-close="outside"
                                                data-bs-toggle="dropdown" />

                                            <!-- dropdown items -->
                                            <ul class="dropdown-menu guest-selector-dropdown">
                                                <!-- Adult -->
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

                                                <!-- Divider -->
                                                <li class="dropdown-divider"></li>

                                                <!-- Child -->
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

                                                <!-- Divider -->
                                                <li class="dropdown-divider"></li>

                                                <!-- Rooms -->
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="btn-position-md-middle">
                            <a class="icon-lg btn btn-round btn-primary mb-0" href="#">
                                <i class="bi bi-search fa-fw"></i>
                            </a>
                        </div>
                    </form>
                    <!-- Booking from END -->
                </div>
            </div>
            <!-- Search END -->
        </div>
    </section>
    <section class="pb-0 pb-xl-5">
        <div class="container">
            <div class="row g-4 justify-content-between align-items-center">
                <!-- Left side START -->
                <div class="col-lg-5 position-relative">
                    <!-- Svg Decoration -->
                    <figure class="position-absolute top-0 start-0 translate-middle z-index-1 ms-4">
                        <svg class="fill-warning" width="77px" height="77px">
                            <path
                                d="M76.997,41.258 L45.173,41.258 L67.676,63.760 L63.763,67.673 L41.261,45.171 L41.261,76.994 L35.728,76.994 L35.728,45.171 L13.226,67.673 L9.313,63.760 L31.816,41.258 L-0.007,41.258 L-0.007,35.725 L31.816,35.725 L9.313,13.223 L13.226,9.311 L35.728,31.813 L35.728,-0.010 L41.261,-0.010 L41.261,31.813 L63.763,9.311 L67.676,13.223 L45.174,35.725 L76.997,35.725 L76.997,41.258 Z">
                            </path>
                        </svg>
                    </figure>

                    <!-- Svg decoration -->
                    <figure class="position-absolute bottom-0 end-0 d-none d-md-block mb-n5 me-n4">
                        <svg height="400" class="fill-primary opacity-2" viewBox="0 0 340 340">
                            <circle cx="194.2" cy="2.2" r="2.2"></circle>
                            <circle cx="2.2" cy="2.2" r="2.2"></circle>
                            <circle cx="218.2" cy="2.2" r="2.2"></circle>
                            <circle cx="26.2" cy="2.2" r="2.2"></circle>
                            <circle cx="242.2" cy="2.2" r="2.2"></circle>
                            <circle cx="50.2" cy="2.2" r="2.2"></circle>
                            <circle cx="266.2" cy="2.2" r="2.2"></circle>
                            <circle cx="74.2" cy="2.2" r="2.2"></circle>
                            <circle cx="290.2" cy="2.2" r="2.2"></circle>
                            <circle cx="98.2" cy="2.2" r="2.2"></circle>
                            <circle cx="314.2" cy="2.2" r="2.2"></circle>
                            <circle cx="122.2" cy="2.2" r="2.2"></circle>
                            <circle cx="338.2" cy="2.2" r="2.2"></circle>
                            <circle cx="146.2" cy="2.2" r="2.2"></circle>
                            <circle cx="170.2" cy="2.2" r="2.2"></circle>
                            <circle cx="194.2" cy="26.2" r="2.2"></circle>
                            <circle cx="2.2" cy="26.2" r="2.2"></circle>
                            <circle cx="218.2" cy="26.2" r="2.2"></circle>
                            <circle cx="26.2" cy="26.2" r="2.2"></circle>
                            <circle cx="242.2" cy="26.2" r="2.2"></circle>
                            <circle cx="50.2" cy="26.2" r="2.2"></circle>
                            <circle cx="266.2" cy="26.2" r="2.2"></circle>
                            <circle cx="74.2" cy="26.2" r="2.2"></circle>
                            <circle cx="290.2" cy="26.2" r="2.2"></circle>
                            <circle cx="98.2" cy="26.2" r="2.2"></circle>
                            <circle cx="314.2" cy="26.2" r="2.2"></circle>
                            <circle cx="122.2" cy="26.2" r="2.2"></circle>
                            <circle cx="338.2" cy="26.2" r="2.2"></circle>
                            <circle cx="146.2" cy="26.2" r="2.2"></circle>
                            <circle cx="170.2" cy="26.2" r="2.2"></circle>
                            <circle cx="194.2" cy="50.2" r="2.2"></circle>
                            <circle cx="2.2" cy="50.2" r="2.2"></circle>
                            <circle cx="218.2" cy="50.2" r="2.2"></circle>
                            <circle cx="26.2" cy="50.2" r="2.2"></circle>
                            <circle cx="242.2" cy="50.2" r="2.2"></circle>
                            <circle cx="50.2" cy="50.2" r="2.2"></circle>
                            <circle cx="266.2" cy="50.2" r="2.2"></circle>
                            <circle cx="74.2" cy="50.2" r="2.2"></circle>
                            <circle cx="290.2" cy="50.2" r="2.2"></circle>
                            <circle cx="98.2" cy="50.2" r="2.2"></circle>
                            <circle cx="314.2" cy="50.2" r="2.2"></circle>
                            <circle cx="122.2" cy="50.2" r="2.2"></circle>
                            <circle cx="338.2" cy="50.2" r="2.2"></circle>
                            <circle cx="146.2" cy="50.2" r="2.2"></circle>
                            <circle cx="170.2" cy="50.2" r="2.2"></circle>
                            <circle cx="194.2" cy="74.2" r="2.2"></circle>
                            <circle cx="2.2" cy="74.2" r="2.2"></circle>
                            <circle cx="218.2" cy="74.2" r="2.2"></circle>
                            <circle cx="26.2" cy="74.2" r="2.2"></circle>
                            <circle cx="242.2" cy="74.2" r="2.2"></circle>
                            <circle cx="50.2" cy="74.2" r="2.2"></circle>
                            <circle cx="266.2" cy="74.2" r="2.2"></circle>
                            <circle cx="74.2" cy="74.2" r="2.2"></circle>
                            <circle cx="290.2" cy="74.2" r="2.2"></circle>
                            <circle cx="98.2" cy="74.2" r="2.2"></circle>
                            <circle cx="314.2" cy="74.2" r="2.2"></circle>
                            <circle cx="122.2" cy="74.2" r="2.2"></circle>
                            <circle cx="338.2" cy="74.2" r="2.2"></circle>
                            <circle cx="146.2" cy="74.2" r="2.2"></circle>
                            <circle cx="170.2" cy="74.2" r="2.2"></circle>
                            <circle cx="194.2" cy="98.2" r="2.2"></circle>
                            <circle cx="2.2" cy="98.2" r="2.2"></circle>
                            <circle cx="218.2" cy="98.2" r="2.2"></circle>
                            <circle cx="26.2" cy="98.2" r="2.2"></circle>
                            <circle cx="242.2" cy="98.2" r="2.2"></circle>
                            <circle cx="50.2" cy="98.2" r="2.2"></circle>
                            <circle cx="266.2" cy="98.2" r="2.2"></circle>
                            <circle cx="74.2" cy="98.2" r="2.2"></circle>
                            <circle cx="290.2" cy="98.2" r="2.2"></circle>
                            <circle cx="98.2" cy="98.2" r="2.2"></circle>
                            <circle cx="314.2" cy="98.2" r="2.2"></circle>
                            <circle cx="122.2" cy="98.2" r="2.2"></circle>
                            <circle cx="338.2" cy="98.2" r="2.2"></circle>
                            <circle cx="146.2" cy="98.2" r="2.2"></circle>
                            <circle cx="170.2" cy="98.2" r="2.2"></circle>
                            <circle cx="194.2" cy="122.2" r="2.2"></circle>
                            <circle cx="2.2" cy="122.2" r="2.2"></circle>
                            <circle cx="218.2" cy="122.2" r="2.2"></circle>
                            <circle cx="26.2" cy="122.2" r="2.2"></circle>
                            <circle cx="242.2" cy="122.2" r="2.2"></circle>
                            <circle cx="50.2" cy="122.2" r="2.2"></circle>
                            <circle cx="266.2" cy="122.2" r="2.2"></circle>
                            <circle cx="74.2" cy="122.2" r="2.2"></circle>
                            <circle cx="290.2" cy="122.2" r="2.2"></circle>
                            <circle cx="98.2" cy="122.2" r="2.2"></circle>
                            <circle cx="314.2" cy="122.2" r="2.2"></circle>
                            <circle cx="122.2" cy="122.2" r="2.2"></circle>
                            <circle cx="338.2" cy="122.2" r="2.2"></circle>
                            <circle cx="146.2" cy="122.2" r="2.2"></circle>
                            <circle cx="170.2" cy="122.2" r="2.2"></circle>
                            <circle cx="194.2" cy="146.2" r="2.2"></circle>
                            <circle cx="2.2" cy="146.2" r="2.2"></circle>
                            <circle cx="218.2" cy="146.2" r="2.2"></circle>
                            <circle cx="26.2" cy="146.2" r="2.2"></circle>
                            <circle cx="242.2" cy="146.2" r="2.2"></circle>
                            <circle cx="50.2" cy="146.2" r="2.2"></circle>
                            <circle cx="266.2" cy="146.2" r="2.2"></circle>
                            <circle cx="74.2" cy="146.2" r="2.2"></circle>
                            <circle cx="290.2" cy="146.2" r="2.2"></circle>
                            <circle cx="98.2" cy="146.2" r="2.2"></circle>
                            <circle cx="314.2" cy="146.2" r="2.2"></circle>
                            <circle cx="122.2" cy="146.2" r="2.2"></circle>
                            <circle cx="338.2" cy="146.2" r="2.2"></circle>
                            <circle cx="146.2" cy="146.2" r="2.2"></circle>
                            <circle cx="170.2" cy="146.2" r="2.2"></circle>
                            <circle cx="194.2" cy="170.2" r="2.2"></circle>
                            <circle cx="2.2" cy="170.2" r="2.2"></circle>
                            <circle cx="218.2" cy="170.2" r="2.2"></circle>
                            <circle cx="26.2" cy="170.2" r="2.2"></circle>
                            <circle cx="242.2" cy="170.2" r="2.2"></circle>
                            <circle cx="50.2" cy="170.2" r="2.2"></circle>
                            <circle cx="266.2" cy="170.2" r="2.2"></circle>
                            <circle cx="74.2" cy="170.2" r="2.2"></circle>
                            <circle cx="290.2" cy="170.2" r="2.2"></circle>
                            <circle cx="98.2" cy="170.2" r="2.2"></circle>
                            <circle cx="314.2" cy="170.2" r="2.2"></circle>
                            <circle cx="122.2" cy="170.2" r="2.2"></circle>
                            <circle cx="338.2" cy="170.2" r="2.2"></circle>
                            <circle cx="146.2" cy="170.2" r="2.2"></circle>
                            <circle cx="170.2" cy="170.2" r="2.2"></circle>
                            <circle cx="194.2" cy="194.2" r="2.2"></circle>
                            <circle cx="2.2" cy="194.2" r="2.2"></circle>
                            <circle cx="218.2" cy="194.2" r="2.2"></circle>
                            <circle cx="26.2" cy="194.2" r="2.2"></circle>
                            <circle cx="242.2" cy="194.2" r="2.2"></circle>
                            <circle cx="50.2" cy="194.2" r="2.2"></circle>
                            <circle cx="266.2" cy="194.2" r="2.2"></circle>
                            <circle cx="74.2" cy="194.2" r="2.2"></circle>
                            <circle cx="290.2" cy="194.2" r="2.2"></circle>
                            <circle cx="98.2" cy="194.2" r="2.2"></circle>
                            <circle cx="314.2" cy="194.2" r="2.2"></circle>
                            <circle cx="122.2" cy="194.2" r="2.2"></circle>
                            <circle cx="338.2" cy="194.2" r="2.2"></circle>
                            <circle cx="146.2" cy="194.2" r="2.2"></circle>
                            <circle cx="170.2" cy="194.2" r="2.2"></circle>
                            <circle cx="194.2" cy="218.2" r="2.2"></circle>
                            <circle cx="2.2" cy="218.2" r="2.2"></circle>
                            <circle cx="218.2" cy="218.2" r="2.2"></circle>
                            <circle cx="26.2" cy="218.2" r="2.2"></circle>
                            <circle cx="242.2" cy="218.2" r="2.2"></circle>
                            <circle cx="50.2" cy="218.2" r="2.2"></circle>
                            <circle cx="266.2" cy="218.2" r="2.2"></circle>
                            <circle cx="74.2" cy="218.2" r="2.2"></circle>
                            <circle cx="290.2" cy="218.2" r="2.2"></circle>
                            <circle cx="98.2" cy="218.2" r="2.2"></circle>
                            <circle cx="314.2" cy="218.2" r="2.2"></circle>
                            <circle cx="122.2" cy="218.2" r="2.2"></circle>
                            <circle cx="338.2" cy="218.2" r="2.2"></circle>
                            <circle cx="146.2" cy="218.2" r="2.2"></circle>
                            <circle cx="170.2" cy="218.2" r="2.2"></circle>
                            <circle cx="194.2" cy="242.2" r="2.2"></circle>
                            <circle cx="2.2" cy="242.2" r="2.2"></circle>
                            <circle cx="218.2" cy="242.2" r="2.2"></circle>
                            <circle cx="26.2" cy="242.2" r="2.2"></circle>
                            <circle cx="242.2" cy="242.2" r="2.2"></circle>
                            <circle cx="50.2" cy="242.2" r="2.2"></circle>
                            <circle cx="266.2" cy="242.2" r="2.2"></circle>
                            <circle cx="74.2" cy="242.2" r="2.2"></circle>
                            <circle cx="290.2" cy="242.2" r="2.2"></circle>
                            <circle cx="98.2" cy="242.2" r="2.2"></circle>
                            <circle cx="314.2" cy="242.2" r="2.2"></circle>
                            <circle cx="122.2" cy="242.2" r="2.2"></circle>
                            <circle cx="338.2" cy="242.2" r="2.2"></circle>
                            <circle cx="146.2" cy="242.2" r="2.2"></circle>
                            <circle cx="170.2" cy="242.2" r="2.2"></circle>
                            <circle cx="194.2" cy="266.2" r="2.2"></circle>
                            <circle cx="2.2" cy="266.2" r="2.2"></circle>
                            <circle cx="218.2" cy="266.2" r="2.2"></circle>
                            <circle cx="26.2" cy="266.2" r="2.2"></circle>
                            <circle cx="242.2" cy="266.2" r="2.2"></circle>
                            <circle cx="50.2" cy="266.2" r="2.2"></circle>
                            <circle cx="266.2" cy="266.2" r="2.2"></circle>
                            <circle cx="74.2" cy="266.2" r="2.2"></circle>
                            <circle cx="290.2" cy="266.2" r="2.2"></circle>
                            <circle cx="98.2" cy="266.2" r="2.2"></circle>
                            <circle cx="314.2" cy="266.2" r="2.2"></circle>
                            <circle cx="122.2" cy="266.2" r="2.2"></circle>
                            <circle cx="338.2" cy="266.2" r="2.2"></circle>
                            <circle cx="146.2" cy="266.2" r="2.2"></circle>
                            <circle cx="170.2" cy="266.2" r="2.2"></circle>
                            <circle cx="194.2" cy="290.2" r="2.2"></circle>
                            <circle cx="2.2" cy="290.2" r="2.2"></circle>
                            <circle cx="218.2" cy="290.2" r="2.2"></circle>
                            <circle cx="26.2" cy="290.2" r="2.2"></circle>
                            <circle cx="242.2" cy="290.2" r="2.2"></circle>
                            <circle cx="50.2" cy="290.2" r="2.2"></circle>
                            <circle cx="266.2" cy="290.2" r="2.2"></circle>
                            <circle cx="74.2" cy="290.2" r="2.2"></circle>
                            <circle cx="290.2" cy="290.2" r="2.2"></circle>
                            <circle cx="98.2" cy="290.2" r="2.2"></circle>
                            <circle cx="314.2" cy="290.2" r="2.2"></circle>
                            <circle cx="122.2" cy="290.2" r="2.2"></circle>
                            <circle cx="338.2" cy="290.2" r="2.2"></circle>
                            <circle cx="146.2" cy="290.2" r="2.2"></circle>
                            <circle cx="170.2" cy="290.2" r="2.2"></circle>
                            <circle cx="194.2" cy="314.2" r="2.2"></circle>
                            <circle cx="2.2" cy="314.2" r="2.2"></circle>
                            <circle cx="218.2" cy="314.2" r="2.2"></circle>
                            <circle cx="26.2" cy="314.2" r="2.2"></circle>
                            <circle cx="242.2" cy="314.2" r="2.2"></circle>
                            <circle cx="50.2" cy="314.2" r="2.2"></circle>
                            <circle cx="266.2" cy="314.2" r="2.2"></circle>
                            <circle cx="74.2" cy="314.2" r="2.2"></circle>
                            <circle cx="290.2" cy="314.2" r="2.2"></circle>
                            <circle cx="98.2" cy="314.2" r="2.2"></circle>
                            <circle cx="314.2" cy="314.2" r="2.2"></circle>
                            <circle cx="122.2" cy="314.2" r="2.2"></circle>
                            <circle cx="338.2" cy="314.2" r="2.2"></circle>
                            <circle cx="146.2" cy="314.2" r="2.2"></circle>
                            <circle cx="170.2" cy="314.2" r="2.2"></circle>
                            <circle cx="194.2" cy="338.2" r="2.2"></circle>
                            <circle cx="2.2" cy="338.2" r="2.2"></circle>
                            <circle cx="218.2" cy="338.2" r="2.2"></circle>
                            <circle cx="26.2" cy="338.2" r="2.2"></circle>
                            <circle cx="242.2" cy="338.2" r="2.2"></circle>
                            <circle cx="50.2" cy="338.2" r="2.2"></circle>
                            <circle cx="266.2" cy="338.2" r="2.2"></circle>
                            <circle cx="74.2" cy="338.2" r="2.2"></circle>
                            <circle cx="290.2" cy="338.2" r="2.2"></circle>
                            <circle cx="98.2" cy="338.2" r="2.2"></circle>
                            <circle cx="314.2" cy="338.2" r="2.2"></circle>
                            <circle cx="122.2" cy="338.2" r="2.2"></circle>
                            <circle cx="338.2" cy="338.2" r="2.2"></circle>
                            <circle cx="146.2" cy="338.2" r="2.2"></circle>
                            <circle cx="170.2" cy="338.2" r="2.2"></circle>
                        </svg>
                    </figure>

                    <!-- Image -->
                    <img src="assets/images/about/01.jpg" class="rounded-3 position-relative" alt="" />

                    <!-- Client rating START -->
                    <div class="position-absolute bottom-0 start-0 z-index-1 mb-4 ms-5">
                        <div class="bg-body d-flex d-inline-block rounded-3 position-relative p-3">
                            <!-- Element -->
                            <img src="assets/images/element/01.svg"
                                class="position-absolute top-0 start-0 translate-middle w-40px" alt="" />

                            <!-- Avatar group -->
                            <div class="me-4">
                                <h6 class="fw-light">Client</h6>
                                <!-- Avatar group -->
                                <ul class="avatar-group mb-0">
                                    <li class="avatar avatar-sm">
                                        <img class="avatar-img rounded-circle" src="assets/images/avatar/01.jpg"
                                            alt="avatar" />
                                    </li>
                                    <li class="avatar avatar-sm">
                                        <img class="avatar-img rounded-circle" src="assets/images/avatar/02.jpg"
                                            alt="avatar" />
                                    </li>
                                    <li class="avatar avatar-sm">
                                        <img class="avatar-img rounded-circle" src="assets/images/avatar/03.jpg"
                                            alt="avatar" />
                                    </li>
                                    <li class="avatar avatar-sm">
                                        <img class="avatar-img rounded-circle" src="assets/images/avatar/04.jpg"
                                            alt="avatar" />
                                    </li>
                                    <li class="avatar avatar-sm">
                                        <div class="avatar-img rounded-circle bg-primary">
                                            <span
                                                class="text-white position-absolute top-50 start-50 translate-middle small">
                                                1K+
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- Rating -->
                            <div>
                                <h6 class="fw-light mb-3">Rating</h6>
                                <h6 class="m-0">
                                    4.5
                                    <i class="fa-solid fa-star text-warning ms-1"></i>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <!-- Client rating END -->
                </div>
                <!-- Left side END -->

                <!-- Right side START -->
                <div class="col-lg-6">
                    <h2 class="mb-3 mb-lg-5">The Best Holidays Start Here!</h2>
                    <p class="mb-3 mb-lg-5">
                        Book your hotel with us and don't forget to grab an
                        awesome hotel deal to save massive on your stay.
                    </p>

                    <!-- Features START -->
                    <div class="row g-4">
                        <!-- Item -->
                        <div class="col-sm-6">
                            <div class="icon-lg bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <h5 class="mt-2">Quality Food</h5>
                            <p class="mb-0">
                                Departure defective arranging rapturous did.
                                Conduct denied adding worthy little.
                            </p>
                        </div>
                        <!-- Item -->
                        <div class="col-sm-6">
                            <div class="icon-lg bg-danger bg-opacity-10 text-danger rounded-circle">
                                <i class="bi bi-stopwatch-fill"></i>
                            </div>
                            <h5 class="mt-2">Quick Services</h5>
                            <p class="mb-0">
                                Supposing so be resolving breakfast am or
                                perfectly.
                            </p>
                        </div>
                        <!-- Item -->
                        <div class="col-sm-6">
                            <div class="icon-lg bg-orange bg-opacity-10 text-orange rounded-circle">
                                <i class="bi bi-shield-fill-check"></i>
                            </div>
                            <h5 class="mt-2">High Security</h5>
                            <p class="mb-0">
                                Arranging rapturous did believe him all had
                                supported.
                            </p>
                        </div>
                        <!-- Item -->
                        <div class="col-sm-6">
                            <div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="bi bi-lightning-fill"></i>
                            </div>
                            <h5 class="mt-2">24 Hours Alert</h5>
                            <p class="mb-0">
                                Rapturous did believe him all had supported.
                            </p>
                        </div>
                    </div>
                    <!-- Features END -->
                </div>
                <!-- Right side END -->
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <!-- Title -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="mb-0">Featured Hotels</h2>
                </div>
            </div>

            <div class="row g-4">
                @if(!empty($hotels) && $hotels->count() > 0)
                    @foreach ($hotels as $hotel)
                    <div class="col-sm-6 col-xl-3">
                        <!-- Card START -->
                        <div class="card card-img-scale overflow-hidden bg-transparent">
                            <!-- Image and overlay -->
                            <div class="card-img-scale-wrapper rounded-3">
                                <!-- Image -->
                                <img src="{{ asset( ($hotel->images[0] ?? null) ? ($hotel->images[0]) : 'assets/images/category/hotel/01.jpg') }}" class="card-img" alt="{{ $hotel->name ?? 'Hotel' }} image" />
                                {{-- Note: If images are stored with Storage::put('public/...') then Storage::url($hotel->images[0]) would be correct.
                                     If they are already in public path directly, asset() is fine.
                                     The factory currently suggests simple names like 'placeholders/hotel_image_1.jpg',
                                     implying they might be directly under public/ or public/placeholders/.
                                     Using asset() directly assumes these paths are relative to the public directory.
                                --}}
                                <!-- Badge -->
                                <div class="position-absolute bottom-0 start-0 p-3">
                                    <div class="badge text-bg-dark fs-6 rounded-pill stretched-link">
                                        <i class="bi bi-geo-alt me-2"></i>
                                        {{-- Display city or a part of address if full address is too long --}}
                                        {{ $hotel->city ?? ($hotel->address ? Str::limit(explode(',', $hotel->address)[0], 15) : 'Unknown Location') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Card body -->
                            <div class="card-body px-2">
                                <!-- Title -->
                                <h5 class="card-title">
                                    <a href="{{ url("/hotel/{$hotel->id}/rooms") }}" class="stretched-link">
                                        {{ $hotel->name }}
                                    </a>
                                </h5>
                                <!-- Price and rating -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-success mb-0">
                                        {{-- Price removed as $hotel->price is not reliable and should come from room rates --}}
                                    </h6>
                                    <h6 class="mb-0">
                                        {{ number_format($hotel->star_rating ?? 0, 1) }}
                                        <i class="fa-solid fa-star text-warning ms-1"></i>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <!-- Card END -->
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p>No featured hotels available at the moment.</p>
                    </div>
                @endif
            </div>
            <!-- Row END -->
        </div>
    </section>

    <section>
        <div class="container">
            <!-- Title -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="mb-0">Explore Nearby</h2>
                </div>
            </div>

            <div class="row g-4 g-md-5">
                @foreach ($cities as $city)
                    <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                        <div class="card bg-transparent text-center p-1 h-100">
                            <!-- Image -->
                            <img src="{{ asset($city["img"]) }}" class="rounded-circle" alt="{{ $city["name"] }}" />
                            <div class="card-body p-0 pt-3">
                                <h5 class="card-title">
                                    <a href="/hotels" class="stretched-link">
                                        {{ $city["name"] }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Row END -->
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />

    <script>
        let homeFlatpickrInstance; // Make instance globally accessible within script if needed

        // Copied from hotels/index.blade.php - slightly adapted for home page
        async function populateLocationsDropdownFromApi(selectedValue = null) {
            const locationSelect = document.querySelector('#home-search-form select[name="location"]'); // Use new form ID
            if (!locationSelect) {
                console.error("Location select not found for home page (#home-search-form select[name='location'])");
                return;
            }

            try {
                const response = await fetch('/hotels/select-options');
                if (!response.ok) {
                    console.error("Failed to fetch locations for home page");
                    locationSelect.innerHTML = `<option value="">Could not load locations</option>`;
                    return;
                }
                const result = await response.json();
                const prev = selectedValue || locationSelect.value;
                locationSelect.innerHTML = `<option value="">Select location</option>`;
                let allLocations = [];
                result.data.forEach((item) => { // Assuming result.data is an array of objects
                    const locationName = item.address; // Use item.address directly
                    if (locationName && !allLocations.includes(locationName)) {
                        allLocations.push(locationName);
                        locationSelect.innerHTML += `<option value="${locationName}">${locationName}</option>`;
                    }
                });

                if (prev) { // If a previous value or selectedValue (from URL param) exists
                    locationSelect.value = prev;
                }
                // This function itself doesn't handle Choices.js re-initialization directly.
                // That should be handled after this promise resolves if Choices.js is used.
            } catch (error) {
                console.error("Error populating locations:", error);
                locationSelect.innerHTML = `<option value="">Error loading locations</option>`;
            }
        }


        document.addEventListener("DOMContentLoaded", function () {
            const bookingForm = document.getElementById('home-search-form'); // Use the new ID
            if (!bookingForm) {
                console.error("Home page search form with ID 'home-search-form' not found.");
                return;
            }

            let flatpickrDefaultDates = [];
            const query = getQueryParams();

            if (query.check_in && query.check_out) {
                flatpickrDefaultDates = [query.check_in, query.check_out];
            }

            homeFlatpickrInstance = flatpickr(bookingForm.querySelector(".flatpickr"), { // Target flatpickr within the form
                mode: "range",
                dateFormat: "Y-m-d", // Internal format
                altInput: true,      // User-friendly display
                altFormat: "d M Y",  // Format for user display
                defaultDate: flatpickrDefaultDates
            });

            function getQueryParams() {
                const params = {};
                window.location.search.substring(1).split("&").forEach(function (part) {
                    if (!part) return;
                    let [key, value] = part.split("=");
                    if (typeof value !== "undefined") value = decodeURIComponent(value.replace(/\+/g, ' '));
                    params[decodeURIComponent(key)] = value || "";
                });
                return params;
            }

            function setFormFromQuery(formElement, queryParams) {
                // Location is now set after populateLocationsDropdownFromApi promise resolves.
                // Dates are handled by flatpickr's defaultDate.

                // Set guests/rooms from query parameters
                if (queryParams.adults) {
                    let el = formElement.querySelector('.guest-selector-count.adults');
                    if (el) el.textContent = queryParams.adults;
                }
                if (query.children) {
                    let el = bookingForm.querySelector('.guest-selector-count.child');
                    if (el) el.textContent = query.children;
                }
                if (query.rooms) {
                    let el = bookingForm.querySelector('.guest-selector-count.rooms');
                    if (el) el.textContent = query.rooms;
                }
            }

            // Get the booking form and handle submission
            var bookingForm = document.querySelector('.card.shadow.rounded-3.position-relative.p-4.pe-md-5.pb-5.pb-md-4');
            if (bookingForm) {
                // Pre-fill from query params on load
                setFormFromQuery(bookingForm, getQueryParams());

                bookingForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Get location
                    var locationSelect = bookingForm.querySelector('select[name="location"]'); // bookingForm is now #home-search-form
                    var location = locationSelect ? locationSelect.value : '';

                    var check_in = '', check_out = '';
                    if (homeFlatpickrInstance && homeFlatpickrInstance.selectedDates.length === 2) {
                        check_in = homeFlatpickrInstance.formatDate(homeFlatpickrInstance.selectedDates[0], "Y-m-d");
                        check_out = homeFlatpickrInstance.formatDate(homeFlatpickrInstance.selectedDates[1], "Y-m-d");
                    } else {
                        // Optional: Show a message if dates are not selected
                        if (typeof Toastify !== 'undefined') {
                            Toastify({ text: "Please select check-in and check-out dates.", duration: 3000, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)" }).showToast();
                        } else {
                            alert("Please select check-in and check-out dates.");
                        }
                        return; // Prevent redirection if dates are not valid
                    }

                    // Get guest & room counts from display elements
                    // Note: It's more robust to have hidden inputs updated by guest selector, like in hotels/index
                    var adults = bookingForm.querySelector('.guest-selector-count.adults')?.textContent.trim() || '2';
                    var children = bookingForm.querySelector('.guest-selector-count.child')?.textContent.trim() || '0';
                    var rooms = bookingForm.querySelector('.guest-selector-count.rooms')?.textContent.trim() || '1';

                    // Build query params
                    var params = new URLSearchParams();
                    if (location) params.append('location', location);
                    if (check_in) params.append('check_in', check_in);
                    if (check_out) params.append('check_out', check_out);
                    if (adults) params.append('adults', adults);
                    if (children) params.append('children', children);
                    if (rooms) params.append('rooms', rooms);

                    // Redirect to /hotels with query params
                    window.location.href = '/hotels?' + params.toString();
                });

                // Ensure search button is type submit
                var searchBtn = bookingForm.querySelector('.btn.btn-primary');
                if (searchBtn && searchBtn.tagName.toLowerCase() === 'a') {
                    var btnParent = searchBtn.parentNode;
                    var newBtn = document.createElement('button');
                    newBtn.type = 'submit'; // Crucial for form submission
                    newBtn.className = searchBtn.className;
                    newBtn.innerHTML = searchBtn.innerHTML;
                    btnParent.replaceChild(newBtn, searchBtn);
                }

                // Populate locations, then set the value if query.location exists.
                // Also, call setFormFromQuery for other fields (guests/rooms).
                // Date pre-fill is handled by flatpickr defaultDate.
                populateLocationsDropdownFromApi(query.location).then(() => {
                    if (query.location) {
                        const currentForm = document.getElementById('home-search-form'); // Re-select to be safe within async scope
                        const locationSelectElem = currentForm.querySelector('select[name="location"]');
                        if (locationSelectElem) {
                             if (window.Choices && locationSelectElem.choices) { // Check if Choices.js is active
                                 // Ensure the value exists as an option before setting
                                 const optionExists = Array.from(locationSelectElem.options).some(opt => opt.value === query.location);
                                 if(optionExists){
                                     locationSelectElem.choices.setValue([{value: query.location, label: query.location}]);
                                 } else {
                                     console.warn(`Location value "${query.location}" from URL not found in populated options.`);
                                     // Optionally add it if desired and if Choices.js allows dynamic option addition easily
                                 }
                             } else {
                                locationSelectElem.value = query.location; // Fallback for standard select
                             }
                        }
                    }
                });

                // Set other form fields (guests, rooms) that don't depend on async location population.
                setFormFromQuery(bookingForm, query);

            } // end if(bookingForm)
        });
    </script>
@endpush