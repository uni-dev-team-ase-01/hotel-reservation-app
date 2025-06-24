@extends("layouts.app")

@section("title", "Booking Policies - Hotel Reservation System")

@section("content")

    <main>
        <!-- =======================
                    Main banner START -->
        <section class="pt-4 pt-md-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-xl-10">
                        <!-- Title -->
                        <h1>Booking Policies & Terms</h1>
                        <p class="lead mb-0">
                            Understanding our comprehensive booking system policies will help ensure a smooth reservation experience. 
                            Please review these important terms and conditions that govern our hotel services.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================
                    Main banner END -->

        <!-- =======================
                    Policy Content START -->
        <section class="pt-0 pt-lg-5">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <!-- Policy Content START -->
                    <div class="col-lg-8">
                        <!-- Overview Section -->
                        <div class="card bg-light p-4 mb-4">
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

                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">📋 Overview</h3>
                            </div>
                            <div class="card-body p-0">
                                <p>Welcome to our comprehensive booking system. This platform manages reservations, customer information, room assignments, and billing across our hotel chain. Please review the following policies to understand how our service operates and your rights and responsibilities as a user.</p>
                            </div>
                        </div>

                        <!-- Reservation Policies Section -->
                        <div class="card bg-light p-4 mb-4">
                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">🏨 Reservation Policies</h3>
                            </div>
                            <div class="card-body p-0">
                                <h5 class="mt-3">Making Reservations</h5>
                                <p>Customers may make, change, or cancel reservations through our website. When booking, you must provide accurate personal details, specify room type, number of occupants, and arrival/departure dates. All information must be complete and truthful.</p>

                                <h5 class="mt-4">Payment & Credit Card Policy</h5>
                                <p>Reservations can be secured with or without credit card details. <strong>Important:</strong> Reservations without credit card information will be <strong>automatically cancelled at 7:00 PM daily</strong> if not guaranteed.</p>
                                
                                <div class="alert alert-warning">
                                    <strong>⚠️ Automatic Cancellation:</strong> Unreserved bookings are cancelled daily at 7 PM without credit card guarantee.
                                </div>

                                <h5 class="mt-4">No-Show Policy</h5>
                                <p>Customers who fail to arrive for their reservation will be charged as no-shows. <strong>Billing occurs automatically at 7:00 PM daily</strong> for no-show reservations.</p>

                                <h5 class="mt-4">Check-In Process</h5>
                                <p>Our reservation clerks can check in customers with or without prior reservations. During check-in, checkout dates may be modified, and room assignments are made.</p>
                                
                                <div class="alert alert-success">
                                    <strong>✅ Flexible Check-In:</strong> Walk-in customers welcome - reservations not required.
                                </div>

                                <h5 class="mt-4">Check-Out & Billing</h5>
                                <p>At checkout, customers may pay by cash or credit card, and customers receive a checkout statement. <strong>Late checkout fees apply</strong> - customers not checked out by the designated time will be charged for an additional night.</p>

                                <h5 class="mt-4">Additional Services & Charges</h5>
                                <p>Optional charges may include:</p>
                                <ul>
                                    <li>Restaurant charges</li>
                                    <li>Room service and laundry</li>
                                    <li>Telephone services</li>
                                    <li>Automatic key issuance</li>
                                    <li>Club facility access</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Corporate Services Section -->
                        <div class="card bg-light p-4 mb-4">
                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">💼 Corporate & Travel Company Services</h3>
                            </div>
                            <div class="card-body p-0">
                                <h5 class="mt-3">Group Bookings & Corporate Rates</h5>
                                <p>Travel companies can secure block bookings at discounted rates for <strong>one or more nights</strong> when reserving <strong>three or more rooms</strong>. Bills for corporate bookings are charged directly to the travel company account.</p>
                            </div>
                        </div>

                        <!-- Extended Stay Section -->
                        <div class="card bg-light p-4 mb-4">
                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">🏠 Extended Stay Services</h3>
                            </div>
                            <div class="card-body p-0">
                                <h5 class="mt-3">Residential Suites</h5>
                                <p>For extended stays, customers may reserve residential suites instead of standard hotel rooms. Guests can occupy suites for <strong>weekly or monthly periods</strong> with special rates. Payment is structured on a weekly or monthly basis rather than daily rates.</p>
                            </div>
                        </div>

                        <!-- Terms & Conditions Section -->
                        <div class="card bg-light p-4 mb-4">
                            <div class="card-header bg-light p-0 pb-3">
                                <h3 class="mb-0">⚖️ Terms & Conditions</h3>
                            </div>
                            <div class="card-body p-0">
                                <h5 class="mt-3">Service Agreement</h5>
                                <p>By using our reservation system, you agree to these terms and conditions. We reserve the right to modify policies with reasonable notice. All transactions are subject to our standard billing and cancellation policies as outlined above.</p>

                                <h5 class="mt-4">Data Privacy</h5>
                                <p>Customer information is collected solely for reservation and billing purposes. We maintain strict confidentiality of personal details and payment information in accordance with industry standards.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar START -->
                    <div class="col-lg-4">
                        <!-- Quick Reference Card -->
                        <div class="card bg-primary text-white p-4 sticky-top">
                            <div class="card-header bg-primary p-0 pb-3 border-0">
                                <h4 class="mb-0 text-white">Quick Reference</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="mb-3">
                                    <h6 class="text-white">⏰ Daily Cut-off Times</h6>
                                    <p class="mb-1 small">Automatic Cancellation: 7:00 PM</p>
                                    <p class="mb-0 small">No-Show Billing: 7:00 PM</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-white">💳 Payment Options</h6>
                                    <p class="mb-1 small">Cash or Credit Card</p>
                                    <p class="mb-0 small">Corporate Account Billing</p>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-white">🏢 Group Discounts</h6>
                                    <p class="mb-0 small">3+ rooms qualify for special rates</p>
                                </div>
                                
                                <div>
                                    <h6 class="text-white">🏠 Extended Stays</h6>
                                    <p class="mb-0 small">Weekly/Monthly suite rates available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar END -->
                </div>
            </div>
        </section>
        <!-- =======================
                    Policy Content END -->
    </main>
@endsection