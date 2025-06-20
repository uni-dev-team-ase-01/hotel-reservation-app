@extends("layouts.app")

@section("title", "Join Us - Booking Landing Page")

@section("content")

    <main>
        <!-- =======================
                    Main banner START -->
        <section class="pt-4 pt-md-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-xl-10">
                        <!-- Title -->
                        <h1>Let's Connect and Start Your Journey</h1>
                        <p class="lead mb-0">
                            We’re here to help you explore the world. Reach out to our team for inquiries, support, or
                            partnership opportunities. Let’s make your travel dreams a reality.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
                    Main banner START -->

        <!-- =======================
                    Contact form and vector START -->
        <section class="pt-0 pt-lg-5">
            <div class="container">
                <div class="row g-4 g-lg-5 align-items-center">
                    <!-- Vector image START -->
                    <div class="col-lg-6 text-center">
                        <img src="assets/images/element/contact.svg" alt="" />
                    </div>
                    <!-- Vector image END -->

                    <!-- Contact form START -->
                    <div class="col-lg-6">
                        <div class="card bg-light p-4">
                            <!-- Svg decoration -->
                            <figure class="position-absolute end-0 bottom-0 mb-n4 me-n2">
                                <svg class="fill-orange" width="104.2px" height="95.2px">
                                    <circle cx="2.6" cy="92.6" r="2.6" />
                                    <circle cx="2.6" cy="77.6" r="2.6" />
                                    <circle cx="2.6" cy="62.6" r="2.6" />
                                    <circle cx="2.6" cy="47.6" r="2.6" />
                                    <circle cx="2.6" cy="32.6" r="2.6" />
                                    <circle cx="2.6" cy="17.6" r="2.6" />
                                    <circle cx="2.6" cy="2.6" r="2.6" />
                                    <circle cx="22.4" cy="92.6" r="2.6" />
                                    <circle cx="22.4" cy="77.6" r="2.6" />
                                    <circle cx="22.4" cy="62.6" r="2.6" />
                                    <circle cx="22.4" cy="47.6" r="2.6" />
                                    <circle cx="22.4" cy="32.6" r="2.6" />
                                    <circle cx="22.4" cy="17.6" r="2.6" />
                                    <circle cx="22.4" cy="2.6" r="2.6" />
                                    <circle cx="42.2" cy="92.6" r="2.6" />
                                    <circle cx="42.2" cy="77.6" r="2.6" />
                                    <circle cx="42.2" cy="62.6" r="2.6" />
                                    <circle cx="42.2" cy="47.6" r="2.6" />
                                    <circle cx="42.2" cy="32.6" r="2.6" />
                                    <circle cx="42.2" cy="17.6" r="2.6" />
                                    <circle cx="42.2" cy="2.6" r="2.6" />
                                    <circle cx="62" cy="92.6" r="2.6" />
                                    <circle cx="62" cy="77.6" r="2.6" />
                                    <circle cx="62" cy="62.6" r="2.6" />
                                    <circle cx="62" cy="47.6" r="2.6" />
                                    <circle cx="62" cy="32.6" r="2.6" />
                                    <circle cx="62" cy="17.6" r="2.6" />
                                    <circle cx="62" cy="2.6" r="2.6" />
                                    <circle cx="81.8" cy="92.6" r="2.6" />
                                    <circle cx="81.8" cy="77.6" r="2.6" />
                                    <circle cx="81.8" cy="62.6" r="2.6" />
                                    <circle cx="81.8" cy="47.6" r="2.6" />
                                    <circle cx="81.8" cy="32.6" r="2.6" />
                                    <circle cx="81.8" cy="17.6" r="2.6" />
                                    <circle cx="81.8" cy="2.6" r="2.6" />
                                    <circle cx="101.7" cy="92.6" r="2.6" />
                                    <circle cx="101.7" cy="77.6" r="2.6" />
                                    <circle cx="101.7" cy="62.6" r="2.6" />
                                    <circle cx="101.7" cy="47.6" r="2.6" />
                                    <circle cx="101.7" cy="32.6" r="2.6" />
                                    <circle cx="101.7" cy="17.6" r="2.6" />
                                    <circle cx="101.7" cy="2.6" r="2.6" />
                                </svg>
                            </figure>

                            <!-- Card header -->
                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">Join our network</h3>
                            </div>

                            <!-- Card body START -->
                            <div class="card-body p-0">
                                <form action="{{ route('travel-agent.submit') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control" id="company_name"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="company_registration" class="form-label">Company Registration</label>
                                        <input type="text" name="company_registration" class="form-control" id="company_registration"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Your Email</label>
                                        <input type="email" name="email" class="form-control" id="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" id="phone" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="message" class="form-label">Your Message</label>
                                        <textarea name="message" class="form-control" id="message" rows="4"
                                            required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </form>


                            </div>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!-- Card body END -->
                        </div>
                    </div>
                    <!-- Contact form END -->
                </div>
            </div>
        </section>
        <!-- =======================
                    Contact form and vector END -->
    </main>
@endsection