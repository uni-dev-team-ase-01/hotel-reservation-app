@extends('layouts.app')

@section('content')
	@php
		use Carbon\Carbon;
		$checkInDate = $booking['check_in'];
		$today = Carbon::now('Asia/Colombo')->startOfDay();
		$checkInDate = \Carbon\Carbon::parse($checkInDate)->timezone('Asia/Colombo')->startOfDay();
		$pastDay = $checkInDate->lessThan($today);
	@endphp
	<section class="py-0">
		<div class="container">
			<div class="card bg-light overflow-hidden px-sm-5">
				<div class="row align-items-center g-4">
					<div class="col-sm-9">
						<div class="card-body">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb breadcrumb-dots mb-0">
									<li class="breadcrumb-item"><a href="{{ route('home') }}"><i
												class="bi bi-house me-1"></i> Home</a></li>
									<li class="breadcrumb-item">Hotel detail</li>
									<li class="breadcrumb-item active">Booking</li>
								</ol>
							</nav>
							<h1 class="m-0 h2 card-title">Review your Booking</h1>
						</div>
					</div>
					<div class="col-sm-3 text-end d-none d-sm-block">
						<img src="{{ asset('assets/images/element/17.svg') }}" class="mb-n4" alt="">
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row g-4 g-lg-5">
				<div class="col-xl-8">
					<div class="vstack gap-5">
						<div class="card shadow">
							<div class="card-header p-4 border-bottom">
								<h3 class="mb-0"><i class="fa-solid fa-hotel me-2"></i>Hotel Information</h3>
							</div>
							<div class="card-body p-4">
								<div class="card mb-4">
									<div class="row align-items-center">
										<div class="col-sm-6 col-md-3">
											<img src="{{ $hotel->image_url ?? asset('assets/images/category/hotel/4by3/02.jpg') }}"
												class="card-img" alt="">
										</div>
										<div class="col-sm-6 col-md-9">
											<div class="card-body pt-3 pt-sm-0 p-0">
												<h5 class="card-title"><a href="#">{{ $hotel->name }}</a></h5>
												<p class="small mb-2"><i
														class="bi bi-geo-alt me-2"></i>{{ $hotel->address ?? '-' }}</p>
												<ul class="list-inline mb-0">
													@for ($i = 0; $i < floor($hotel->rating ?? 4.5); $i++)
														<li class="list-inline-item me-0 small"><i
																class="fa-solid fa-star text-warning"></i></li>
													@endfor
													@if (($hotel->rating ?? 4.5) - floor($hotel->rating ?? 4.5) >= 0.5)
														<li class="list-inline-item me-0 small"><i
																class="fa-solid fa-star-half-alt text-warning"></i></li>
													@endif
													<li class="list-inline-item ms-2 h6 small fw-bold mb-0">
														{{ $hotel->rating ?? '4.5' }}/5.0
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="row g-4">
									<div class="col-lg-4">
										<div class="bg-light py-3 px-4 rounded-3">
											<h6 class="fw-light small mb-1">Check-in</h6>
											<h5 class="mb-1">
												{{ \Carbon\Carbon::parse($booking['check_in'])->format('d F Y') }}
											</h5>
											<small><i class="bi bi-alarm me-1"></i>12:30 pm</small>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="bg-light py-3 px-4 rounded-3">
											<h6 class="fw-light small mb-1">Check out</h6>
											<h5 class="mb-1">
												{{ \Carbon\Carbon::parse($booking['check_out'])->format('d F Y') }}
											</h5>
											<small><i class="bi bi-alarm me-1"></i>4:30 pm</small>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="bg-light py-3 px-4 rounded-3">
											<h6 class="fw-light small mb-1">Rooms &amp; Guests</h6>
											<h5 class="mb-1">{{ $booking['adults'] + $booking['children'] }} G -
												{{ count($rooms) }} R
											</h5>
											<small><i class="bi bi-brightness-high me-1"></i>
												{{ $booking['nights'] ?? (\Carbon\Carbon::parse($booking['check_in'])->diffInDays(\Carbon\Carbon::parse($booking['check_out']))) }}
												Nights
											</small>
										</div>
									</div>
								</div>
								@foreach ($rooms as $room)
									<div class="card border mt-4">
										<div class="card-header border-bottom d-md-flex justify-content-md-between">
											<h5 class="card-title mb-0">{{ $room->room_type ?? 'Room' }} -
												{{ $room->room_number }}
											</h5>
											<a href="/policy" class="btn btn-link p-0 mb-0">View Cancellation Policy</a>
										</div>
										<div class="card-body">
											<h6>Price Included</h6>
											<ul class="list-group list-group-borderless mb-0">
												<li class="list-group-item h6 fw-light d-flex mb-0"><i
														class="bi bi-patch-check-fill text-success me-2"></i>Free Breakfast</li>
												<li class="list-group-item h6 fw-light d-flex mb-0"><i
														class="bi bi-patch-check-fill text-success me-2"></i>Free Stay for Kids
													Below 12</li>
											</ul>
										</div>
									</div>
								@endforeach
							</div>
						</div>
						<div class="card shadow">
							<div class="card-header border-bottom p-4">
								<h4 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Customer Details</h4>
							</div>
							<div class="card-body p-4">
								@php
									$fullName = Auth::user()->name ?? '';
									$nameParts = explode(' ', $fullName, 2); // Split into max 2 parts
									$firstName = $nameParts[0] ?? '';
									$lastName = $nameParts[1] ?? '';
								@endphp
								<form id="payment-form" method="POST" action="{{ route('reservation.processPayment') }}">
									@csrf
									<input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
									@foreach ($rooms as $room)
										<input type="hidden" name="room_ids[]" value="{{ $room->id }}">
									@endforeach
									<input type="hidden" name="check_in" value="{{ $booking['check_in'] }}">
									<input type="hidden" name="check_out" value="{{ $booking['check_out'] }}">
									<input type="hidden" name="adults" value="{{ $booking['adults'] }}">
									<input type="hidden" name="children" value="{{ $booking['children'] }}">

									<div class="row g-3">
										<div class="col-md-2">
											<label class="form-label">Title</label>
											<select class="form-select" name="guest_title">
												<option value="Mr">Mr</option>
												<option value="Mrs">Mrs</option>
												<option value="Ms">Ms</option>
											</select>
										</div>
										<div class="col-md-5">
											<label class="form-label">First Name</label>
											<input id="guest_name" name="guest_name" type="text" class="form-control"
												required value="{{ $firstName }}" readonly>
										</div>
										<div class="col-md-5">
											<label class="form-label">Last Name</label>
											<input id="guest_last_name" name="guest_last_name" type="text"
												class="form-control" value="{{ $lastName }}" readonly>
										</div>
										<div class="col-md-6">
											<label class="form-label">Email</label>
											<input id="guest_email" name="guest_email" type="email" class="form-control"
												required value="{{ Auth::user()->email ?? '' }}">
										</div>
										<div class="col-md-6">
											<label class="form-label">Mobile number</label>
											<input id="guest_mobile" name="guest_mobile" type="text" class="form-control"
												value="{{ Auth::user()->phone ?? '' }}">
										</div>
									</div>
									@if ($checkInDate == $today)
										<!-- Commented out the Stripe payment section for now -->
										<!-- <div class="mt-4">
																			<div id="card-element" class="mb-3"></div>
																			<div id="card-errors" role="alert" class="text-danger"></div>
																			<button id="card-button" class="btn btn-primary" type="button" data-secret="">
																				Pay ${{ number_format($total, 2) }}
																			</button>
																		</div> -->
										<div class="alert alert-info mt-4">
											<h5 class="alert-heading">Payment Information</h5>
											<p>This booking requires immediate payment. Your reservation will be confirmed once
												payment is processed successfully.</p>
											<p>Please ensure your payment details are correct and your mobile number is accurate
												as it will be used for booking confirmation and communication.</p>
										</div>
										<div class="mt-4">
											<input type="hidden" name="payment_status" value="pending">
											<input type="hidden" name="room_charges" value="{{ $room_charges ?? $total }}">
											<input type="hidden" name="extra_charges" value="{{ $extra_charges ?? 0 }}">
											<input type="hidden" name="discount" value="{{ $discount ?? 0 }}">
											<input type="hidden" name="taxes" value="{{ $taxes ?? 0 }}">
											<input type="hidden" name="total_amount" value="{{ $total ?? 0 }}">
											<button type="button" class="btn btn-primary confirm-reservation">Confirm
												Reservation</button>
										</div>
									@elseif ($pastDay)
										<div class="alert alert-warning mt-4">
											<h5 class="alert-heading">Booking Error</h5>
											<p>It seems you are trying to book a room for a date that has already passed. Please
												select a valid check-in date.</p>
										</div>
									@else

										<div class="alert alert-info mt-4">
											<h5 class="alert-heading">Payment Information</h5>
											<p>This booking does not require immediate payment. Payment will be collected at the
												hotel upon arrival/check-in.</p>
											<p>Please ensure your mobile number is correct as it will be used for booking
												confirmation and communication.</p>
										</div>
										<div class="mt-4">
											<input type="hidden" name="payment_status" value="pending">
											<input type="hidden" name="room_charges" value="{{ $room_charges ?? $total }}">
											<input type="hidden" name="extra_charges" value="{{ $extra_charges ?? 0 }}">
											<input type="hidden" name="discount" value="{{ $discount ?? 0 }}">
											<input type="hidden" name="taxes" value="{{ $taxes ?? 0 }}">
											<input type="hidden" name="total_amount" value="{{ $total ?? 0 }}">
											<button type="button" class="btn btn-primary confirm-reservation">Confirm
												Reservation</button>
										</div>

									@endif
								</form>
							</div>
						</div>
					</div>
				</div>
				<aside class="col-xl-4">
					<div class="row g-4">
						<div class="col-md-6 col-xl-12">
							<div class="card shadow rounded-2">
								<div class="card-header border-bottom">
									<h5 class="card-title mb-0">Price Summary</h5>
								</div>
								<div class="card-body">
									<ul class="list-group list-group-borderless">
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="h6 fw-light mb-0">Room Charges</span>
											<span class="fs-5">${{ number_format($total, 2) }}</span>
										</li>
									</ul>
								</div>
								<div class="card-footer border-top">
									<div class="d-flex justify-content-between align-items-center">
										<span class="h5 mb-0">Payable Now</span>
										<span class="h5 mb-0">${{ number_format($total, 2) }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</section>
@endsection
@if($checkInDate !== $today)
	@push('scripts')
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Handle the confirmation button click
				const confirmBtn = document.querySelector('.confirm-reservation');
				if (confirmBtn) {
					confirmBtn.addEventListener('click', function (e) {
						e.preventDefault();

						Swal.fire({
							title: 'Confirm Reservation',
							html: `Are you sure you want to confirm this reservation?<br><br>
											   <div class="text-start">
												   <strong>Hotel:</strong> {{ $hotel->name }}<br>
												   <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking['check_in'])->format('d M Y') }}<br>
												   <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking['check_out'])->format('d M Y') }}<br>
												   <strong>Total:</strong> ${{ number_format($total, 2) }}
											   </div>`,
							icon: 'question',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, confirm!',
							cancelButtonText: 'Cancel',
							customClass: {
								popup: 'animated bounceIn'
							},
							backdrop: `
											rgba(0,0,123,0.4)
											url("{{ asset('assets/images/element/confirmation.gif') }}")
											left top
											no-repeat
										`
						}).then((result) => {
							if (result.isConfirmed) {
								// Show processing alert
								Swal.fire({
									title: 'Processing Reservation',
									html: 'Please wait while we process your reservation...',
									timerProgressBar: true,
									didOpen: () => {
										Swal.showLoading()
									},
									allowOutsideClick: false,
									allowEscapeKey: false,
									allowEnterKey: false
								});

								const form = document.getElementById('payment-form');
								const formData = new FormData(form);

								fetch("{{ route('reservation.ajaxConfirm') }}", {
									method: "POST",
									headers: {
										'X-CSRF-TOKEN': '{{ csrf_token() }}',
										'Accept': 'application/json'
									},
									body: formData
								})
									.then(response => response.json())
									.then(data => {
										Swal.close();
										if (data.success) {
											Swal.fire({
												title: 'Success!',
												text: 'Your reservation has been confirmed!',
												icon: 'success',
												confirmButtonText: 'View Reservations',
												showCancelButton: true,
												cancelButtonText: 'Stay Here'
											}).then((result) => {
												if (result.isConfirmed) {
													window.location.href = "{{ url('/customer/reservations') }}";
												}
											});
										} else {
											Swal.fire({
												title: 'Error!',
												text: data.message || "Reservation failed. Please try again.",
												icon: 'error',
												confirmButtonText: 'OK'
											});
										}
									})
									.catch(error => {
										Swal.close();
										Swal.fire({
											title: 'Error!',
											text: 'An error occurred. Please try again.',
											icon: 'error',
											confirmButtonText: 'OK'
										});
									});
							}
						});
					});
				}
			});
		</script>
	@endpush

@endif