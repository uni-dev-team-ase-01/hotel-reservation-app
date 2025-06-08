@extends('layouts.app')

@section('content')
<section class="py-0">
	<div class="container">
		<div class="card bg-light overflow-hidden px-sm-5">
			<div class="row align-items-center g-4">
				<div class="col-sm-9">
					<div class="card-body">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb breadcrumb-dots mb-0">
								<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i> Home</a></li>
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
										<img src="{{ $hotel->image_url ?? asset('assets/images/category/hotel/4by3/02.jpg') }}" class="card-img" alt="">
									</div>
									<div class="col-sm-6 col-md-9">
										<div class="card-body pt-3 pt-sm-0 p-0">
											<h5 class="card-title"><a href="#">{{ $hotel->name }}</a></h5>
											<p class="small mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $hotel->address ?? '-' }}</p>
											<ul class="list-inline mb-0">
												@for ($i = 0; $i < floor($hotel->rating ?? 4.5); $i++)
													<li class="list-inline-item me-0 small"><i class="fa-solid fa-star text-warning"></i></li>
												@endfor
												@if (($hotel->rating ?? 4.5) - floor($hotel->rating ?? 4.5) >= 0.5)
													<li class="list-inline-item me-0 small"><i class="fa-solid fa-star-half-alt text-warning"></i></li>
												@endif
												<li class="list-inline-item ms-2 h6 small fw-bold mb-0">{{ $hotel->rating ?? '4.5' }}/5.0</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="row g-4">
								<div class="col-lg-4">
									<div class="bg-light py-3 px-4 rounded-3">
										<h6 class="fw-light small mb-1">Check-in</h6>
										<h5 class="mb-1">{{ \Carbon\Carbon::parse($booking['check_in'])->format('d F Y') }}</h5>
										<small><i class="bi bi-alarm me-1"></i>12:30 pm</small>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="bg-light py-3 px-4 rounded-3">
										<h6 class="fw-light small mb-1">Check out</h6>
										<h5 class="mb-1">{{ \Carbon\Carbon::parse($booking['check_out'])->format('d F Y') }}</h5>
										<small><i class="bi bi-alarm me-1"></i>4:30 pm</small>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="bg-light py-3 px-4 rounded-3">
										<h6 class="fw-light small mb-1">Rooms &amp; Guests</h6>
										<h5 class="mb-1">{{ $booking['adults'] + $booking['children'] }} G - {{ count($rooms) }} R</h5>
										<small><i class="bi bi-brightness-high me-1"></i>
											{{ $booking['nights'] ?? (\Carbon\Carbon::parse($booking['check_in'])->diffInDays(\Carbon\Carbon::parse($booking['check_out']))) }} Nights
										</small>
									</div>
								</div>
							</div>
							@foreach ($rooms as $room)
							<div class="card border mt-4">
								<div class="card-header border-bottom d-md-flex justify-content-md-between">
									<h5 class="card-title mb-0">{{ $room->room_type ?? 'Room' }} - {{ $room->room_number }}</h5>
									<a href="#" class="btn btn-link p-0 mb-0">View Cancellation Policy</a>
								</div>
								<div class="card-body">
									<h6>Price Included</h6>
									<ul class="list-group list-group-borderless mb-0">
										<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>Free Breakfast</li>
										<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>Free Stay for Kids Below 12</li>
									</ul>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					<div class="card shadow">
						<div class="card-header border-bottom p-4">
							<h4 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Guest Details</h4>
						</div>
						<div class="card-body p-4">
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
										<input id="guest_name" name="guest_name" type="text" class="form-control" required>
									</div>
									<div class="col-md-5">
										<label class="form-label">Last Name</label>
										<input id="guest_last_name" name="guest_last_name" type="text" class="form-control">
									</div>
									<div class="col-md-6">
										<label class="form-label">Email</label>
										<input id="guest_email" name="guest_email" type="email" class="form-control" required>
									</div>
									<div class="col-md-6">
										<label class="form-label">Mobile number</label>
										<input id="guest_mobile" name="guest_mobile" type="text" class="form-control">
									</div>
								</div>
								<div class="mt-4">
									<div id="card-element" class="mb-3"></div>
									<div id="card-errors" role="alert" class="text-danger"></div>
									<button id="card-button" class="btn btn-primary" type="button" data-secret="">
										Pay ${{ number_format($total, 2) }}
									</button>
								</div>
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

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const cardButton = document.getElementById('card-button');
        cardButton.disabled = true;

        fetch("{{ route('stripe.intent') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                amount: {{ intval($total * 100) }}
            })
        })
        .then(response => response.json())
        .then(data => {
            cardButton.dataset.secret = data.clientSecret;
            cardButton.disabled = false;

            cardButton.addEventListener('click', async (e) => {
                e.preventDefault();
                cardButton.disabled = true;
                cardButton.textContent = 'Processing...';

                const cardHolderName = document.getElementById('guest_name').value + ' ' + (document.getElementById('guest_last_name').value ?? '');
                if (!cardHolderName.trim()) {
                    document.getElementById('card-errors').textContent = 'Card holder name is required.';
                    cardButton.disabled = false;
                    cardButton.textContent = 'Pay ${{ number_format($total, 2) }}';
                    return;
                }

                const { paymentIntent, error } = await stripe.confirmCardPayment(
                    data.clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: cardHolderName
                            }
                        }
                    }
                );
                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                    cardButton.disabled = false;
                    cardButton.textContent = 'Pay ${{ number_format($total, 2) }}';
                } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                    document.getElementById('payment-form').submit();
                    //   window.location.href = '/customer/reservations';
                } else if (paymentIntent && paymentIntent.status === 'processing') {
                    document.getElementById('card-errors').textContent = 'Your payment is processing. Please wait or check your email for confirmation.';
                    cardButton.disabled = false;
                    cardButton.textContent = 'Pay ${{ number_format($total, 2) }}';
                } else {
                    document.getElementById('card-errors').textContent = 'Payment could not be completed. Please try again.';
                    cardButton.disabled = false;
                    cardButton.textContent = 'Pay ${{ number_format($total, 2) }}';
                }
            });
        })
        .catch(error => {
            document.getElementById('card-errors').textContent = 'Could not initialize payment. Please refresh and try again.';
            cardButton.disabled = false;
        });
    });
</script>
@endpush