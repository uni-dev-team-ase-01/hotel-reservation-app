@extends('layouts.app')

@section('content')
	@php
		use Carbon\Carbon;
		$checkInDate = $booking['check_in'];
		$today = Carbon::now('Asia/Colombo')->startOfDay();
		$checkInDate = \Carbon\Carbon::parse($checkInDate)->timezone('Asia/Colombo')->startOfDay();
		$pastDay = $checkInDate->lessThan($today);
		
		// Check if this is a suite booking and calculate discounts
		$isSuiteBooking = isset($booking['room_type']) && $booking['room_type'] === 'suite';
		$suiteDiscountPercent = 15; // 15% discount for suite bookings
		
		// Calculate pricing
		$originalTotal = $total ?? 0;
		$discountAmount = 0;
		$finalTotal = $originalTotal;
		
		if ($isSuiteBooking) {
			$discountAmount = $originalTotal * ($suiteDiscountPercent / 100);
			$finalTotal = $originalTotal - $discountAmount;
		}
		
		// Additional charges
		$roomCharges = $room_charges ?? $originalTotal;
		$extraCharges = $extra_charges ?? 0;
		$taxes = $taxes ?? ($finalTotal * 0.12); // 12% tax
		$grandTotal = $finalTotal + $extraCharges + $taxes;
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
							@if($isSuiteBooking)
								<div class="badge bg-success mt-2">
									<i class="bi bi-star-fill me-1"></i>Suite Booking - {{ $suiteDiscountPercent }}% Discount Applied!
								</div>
							@endif
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
												@if($isSuiteBooking)
													{{ \Carbon\Carbon::parse($booking['check_in'])->format('F Y') }}
													<small class="text-muted d-block">Suite Monthly Booking</small>
												@else
													{{ \Carbon\Carbon::parse($booking['check_in'])->format('d F Y') }}
													<small><i class="bi bi-alarm me-1"></i>12:30 pm</small>
												@endif
											</h5>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="bg-light py-3 px-4 rounded-3">
											<h6 class="fw-light small mb-1">Check out</h6>
											<h5 class="mb-1">
												@if($isSuiteBooking)
													{{ \Carbon\Carbon::parse($booking['check_out'])->format('F Y') }}
													<small class="text-muted d-block">End of Month</small>
												@else
													{{ \Carbon\Carbon::parse($booking['check_out'])->format('d F Y') }}
													<small><i class="bi bi-alarm me-1"></i>4:30 pm</small>
												@endif
											</h5>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="bg-light py-3 px-4 rounded-3">
											<h6 class="fw-light small mb-1">Rooms &amp; Guests</h6>
											<h5 class="mb-1">{{ $booking['adults'] + $booking['children'] }} G -
												{{ count($rooms) }} R
											</h5>
											<small><i class="bi bi-brightness-high me-1"></i>
												@if($isSuiteBooking)
													Monthly Stay
												@else
													{{ $booking['nights'] ?? (\Carbon\Carbon::parse($booking['check_in'])->diffInDays(\Carbon\Carbon::parse($booking['check_out']))) }}
													Nights
												@endif
											</small>
										</div>
									</div>
								</div>
								
								@if($isSuiteBooking)
									<div class="alert alert-info mt-4">
										<div class="d-flex align-items-center">
											<i class="bi bi-info-circle-fill me-2"></i>
											<div>
												<h6 class="mb-1">Suite Booking Benefits</h6>
												<small>Enjoy {{ $suiteDiscountPercent }}% discount on monthly suite bookings, premium amenities, and exclusive services.</small>
											</div>
										</div>
									</div>
								@endif

								@foreach ($rooms as $room)
									<div class="card border mt-4">
										<div class="card-header border-bottom d-md-flex justify-content-md-between">
											<h5 class="card-title mb-0">
												{{ $room->room_type ?? 'Room' }} - {{ $room->room_number }}
												@if($isSuiteBooking)
													<span class="badge bg-primary ms-2">Suite</span>
												@endif
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
												@if($isSuiteBooking)
													<li class="list-group-item h6 fw-light d-flex mb-0"><i
															class="bi bi-patch-check-fill text-success me-2"></i>Concierge Service</li>
													<li class="list-group-item h6 fw-light d-flex mb-0"><i
															class="bi bi-patch-check-fill text-success me-2"></i>Premium Amenities</li>
													<li class="list-group-item h6 fw-light d-flex mb-0"><i
															class="bi bi-patch-check-fill text-success me-2"></i>Monthly Housekeeping</li>
												@endif
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
									<input type="hidden" name="room_type" value="{{ $booking['room_type'] ?? 'standard' }}">
									<input type="hidden" name="is_suite_booking" value="{{ $isSuiteBooking ? '1' : '0' }}">

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
										<div class="alert alert-info mt-4">
											<h5 class="alert-heading">Payment Information</h5>
											<p>This booking requires immediate payment. Your reservation will be confirmed once
												payment is processed successfully.</p>
											@if($isSuiteBooking)
												<p class="mb-0"><strong>Suite Booking:</strong> You're saving ${{ number_format($discountAmount, 2) }} with our exclusive suite discount!</p>
											@endif
										</div>
										<div class="mt-4">
											<input type="hidden" name="payment_status" value="pending">
											<input type="hidden" name="room_charges" value="{{ $roomCharges }}">
											<input type="hidden" name="extra_charges" value="{{ $extraCharges }}">
											<input type="hidden" name="discount" value="{{ $discountAmount }}">
											<input type="hidden" name="taxes" value="{{ $taxes }}">
											<input type="hidden" name="total_amount" value="{{ $grandTotal }}">
											<button type="button" class="btn btn-primary confirm-reservation">
												@if($isSuiteBooking)
													Confirm Suite Reservation - ${{ number_format($grandTotal, 2) }}
												@else
													Confirm Reservation - ${{ number_format($grandTotal, 2) }}
												@endif
											</button>
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
											@if($isSuiteBooking)
												<p class="mb-0"><strong>Suite Booking:</strong> You're saving ${{ number_format($discountAmount, 2) }} with our exclusive suite discount!</p>
											@endif
										</div>
										<div class="mt-4">
											<input type="hidden" name="payment_status" value="pending">
											<input type="hidden" name="room_charges" value="{{ $roomCharges }}">
											<input type="hidden" name="extra_charges" value="{{ $extraCharges }}">
											<input type="hidden" name="discount" value="{{ $discountAmount }}">
											<input type="hidden" name="taxes" value="{{ $taxes }}">
											<input type="hidden" name="total_amount" value="{{ $grandTotal }}">
											<button type="button" class="btn btn-primary confirm-reservation">
												@if($isSuiteBooking)
													Confirm Suite Reservation
												@else
													Confirm Reservation
												@endif
											</button>
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
									@if($isSuiteBooking)
										<small class="text-success"><i class="bi bi-star-fill me-1"></i>Suite Discount Applied</small>
									@endif
								</div>
								<div class="card-body">
									<ul class="list-group list-group-borderless">
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="h6 fw-light mb-0">Room Charges</span>
											<span class="fs-5">${{ number_format($originalTotal, 2) }}</span>
										</li>
										@if($isSuiteBooking && $discountAmount > 0)
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span class="h6 fw-light mb-0 text-success">
													<i class="bi bi-tag-fill me-1"></i>Suite Discount ({{ $suiteDiscountPercent }}%)
												</span>
												<span class="fs-5 text-success">-${{ number_format($discountAmount, 2) }}</span>
											</li>
										@endif
										@if($extraCharges > 0)
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span class="h6 fw-light mb-0">Extra Charges</span>
												<span class="fs-5">${{ number_format($extraCharges, 2) }}</span>
											</li>
										@endif
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<span class="h6 fw-light mb-0">Taxes & Fees</span>
											<span class="fs-5">${{ number_format($taxes, 2) }}</span>
										</li>
									</ul>
								</div>
								<div class="card-footer border-top">
									<div class="d-flex justify-content-between align-items-center">
										<span class="h5 mb-0">Total Amount</span>
										<div class="text-end">
											@if($isSuiteBooking && $discountAmount > 0)
												<div class="text-muted text-decoration-line-through small">
													${{ number_format($originalTotal + $extraCharges + $taxes, 2) }}
												</div>
											@endif
											<span class="h5 mb-0 {{ $isSuiteBooking ? 'text-success' : '' }}">
												${{ number_format($grandTotal, 2) }}
											</span>
										</div>
									</div>
									@if($isSuiteBooking && $discountAmount > 0)
										<div class="text-center mt-2">
											<small class="text-success">
												<i class="bi bi-check-circle-fill me-1"></i>You saved ${{ number_format($discountAmount, 2) }}!
											</small>
										</div>
									@endif
								</div>
							</div>
						</div>
						
						@if($isSuiteBooking)
							<div class="col-md-6 col-xl-12">
								<div class="card border-success">
									<div class="card-body text-center">
										<div class="icon-lg bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3">
											<i class="bi bi-star-fill fs-5"></i>
										</div>
										<h6 class="card-title">Suite Benefits</h6>
										<ul class="list-unstyled small text-start">
											<li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i>{{ $suiteDiscountPercent }}% Monthly Discount</li>
											<li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i>Premium Room Amenities</li>
											<li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i>Dedicated Concierge</li>
											<li class="mb-0"><i class="bi bi-check-lg text-success me-2"></i>Flexible Monthly Terms</li>
										</ul>
									</div>
								</div>
							</div>
						@endif
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
				const confirmBtn = document.querySelector('.confirm-reservation');
				if (confirmBtn) {
					confirmBtn.addEventListener('click', function (e) {
						e.preventDefault();

						const isSuite = {{ $isSuiteBooking ? 'true' : 'false' }};
						const discount = {{ $discountAmount }};
						const grandTotal = {{ $grandTotal }};
						
						let confirmationHtml = `Are you sure you want to confirm this reservation?<br><br>
							<div class="text-start">
								<strong>Hotel:</strong> {{ $hotel->name }}<br>
								<strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking['check_in'])->format('d M Y') }}<br>
								<strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking['check_out'])->format('d M Y') }}<br>`;
						
						if (isSuite && discount > 0) {
							confirmationHtml += `<strong>Original Total:</strong> <span class="text-decoration-line-through">${{ number_format($originalTotal + $extraCharges + $taxes, 2) }}</span><br>
								<strong>Suite Discount:</strong> <span class="text-success">-${{ number_format($discountAmount, 2) }}</span><br>`;
						}
						
						confirmationHtml += `<strong>Final Total:</strong> <span class="${isSuite ? 'text-success fw-bold' : ''}">$${grandTotal.toFixed(2)}</span>
							</div>`;

						Swal.fire({
							title: 'Confirm Reservation',
							html: confirmationHtml,
							icon: 'question',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, confirm!',
							cancelButtonText: 'Cancel',
							customClass: {
								popup: 'animated bounceIn'
							}
						}).then((result) => {
							if (result.isConfirmed) {
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
											let successMessage = 'Your reservation has been confirmed!';
											if (isSuite && discount > 0) {
												successMessage += ` You saved $${discount.toFixed(2)} with the suite discount!`;
											}
											
											Swal.fire({
												title: 'Success!',
												text: successMessage,
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