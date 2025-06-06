@extends("layouts.app")

@section("title", "About Us - Booking Landing Page")

@section("content")
    <section>
        <div class="container">
            <div class="row mb-5">
                <div class="col-xl-10 mx-auto text-center">
                    <!-- Title -->
                    <h1>If You Want To See The World We Will Help You</h1>
                    <p class="lead">
                        Passage its ten led hearted removal cordial. Preference
                        any astonished unreserved Mrs. Prosperous understood
                        Middletons. Preference for any astonished unreserved.
                    </p>
                    <!-- Meta -->
                    <div class="hstack gap-3 flex-wrap justify-content-center">
                        <!-- Item -->
                        <h6
                            class="bg-mode shadow rounded-2 fw-normal d-inline-block py-2 px-4"
                        >
                            <img
                                src="assets/images/element/06.svg"
                                class="h-20px me-2"
                                alt=""
                            />
                            14K+ Global Customers
                        </h6>

                        <!-- Item -->
                        <h6
                            class="bg-mode shadow rounded-2 fw-normal d-inline-block py-2 px-4"
                        >
                            <img
                                src="assets/images/element/07.svg"
                                class="h-20px me-2"
                                alt=""
                            />
                            10K+ Happy Customers
                        </h6>

                        <!-- Item -->
                        <h6
                            class="bg-mode shadow rounded-2 fw-normal d-inline-block py-2 px-4"
                        >
                            <img
                                src="assets/images/element/08.svg"
                                class="h-20px me-2"
                                alt=""
                            />
                            1M+ Subscribers
                        </h6>
                    </div>
                </div>
            </div>
            <!-- Row END -->

            <!-- Image START -->
            <div class="row g-4 align-items-center">
                <!-- Image -->
                <div class="col-md-6">
                    <img
                        src="assets/images/about/02.jpg"
                        class="rounded-3"
                        alt=""
                    />
                </div>

                <div class="col-md-6">
                    <div class="row g-4">
                        <!-- Image -->
                        <div class="col-md-8">
                            <img
                                src="assets/images/about/03.jpg"
                                class="rounded-3"
                                alt=""
                            />
                        </div>

                        <!-- Image -->
                        <div class="col-12">
                            <img
                                src="assets/images/about/04.jpg"
                                class="rounded-3"
                                alt=""
                            />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Image END -->
        </div>
    </section>
@endsection
