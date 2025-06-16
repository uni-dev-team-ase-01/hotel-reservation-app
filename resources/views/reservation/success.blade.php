@extends('layouts.app')

@section('title', 'Reservation Confirmation & Receipt')

@section('content')
<div class="container my-5">
    @if(isset($reservation) && $reservation)
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h2 class="mb-0">Reservation Successful!</h2>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4>Thank you for your booking, {{ $reservation->user->name ?? 'Guest' }}!</h4>
                        <p>Your confirmation number is: <strong>{{ $reservation->confirmation_number ?? $reservation->id }}</strong></p>
                        <p>A confirmation email has been sent to: <strong>{{ $reservation->user->email ?? 'your email address' }}</strong>.</p>
                        <p>Booking Date: {{ $reservation->created_at ? $reservation->created_at->format('F j, Y, g:i a') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Print Receipt</button>
                    </div>
                </div>

                <hr>

                <div class="row my-4">
                    <div class="col-md-6">
                        <h5>Guest Information:</h5>
                        <p>
                            <strong>Name:</strong> {{ $reservation->user->name ?? 'N/A' }}<br>
                            <strong>Email:</strong> {{ $reservation->user->email ?? 'N/A' }}<br>
                            @if($reservation->user->phone)
                                <strong>Phone:</strong> {{ $reservation->user->phone }}<br>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Hotel Information:</h5>
                        @if($reservation->hotel)
                        <p>
                            <strong>{{ $reservation->hotel->name }}</strong><br>
                            {{ $reservation->hotel->address ?? 'Address not available' }}
                        </p>
                        @else
                        <p>Hotel details not available.</p>
                        @endif
                    </div>
                </div>

                <hr>

                <h5 class="mt-4">Booking Summary:</h5>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Check-in Date:</strong></td>
                            <td>{{ $reservation->check_in_date ? \Carbon\Carbon::parse($reservation->check_in_date)->format('F j, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Check-out Date:</strong></td>
                            <td>{{ $reservation->check_out_date ? \Carbon\Carbon::parse($reservation->check_out_date)->format('F j, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Number of Nights:</strong></td>
                            <td>{{ $nights ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Number of Guests:</strong></td>
                            <td>{{ $reservation->number_of_guests ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="align-top"><strong>Room(s):</strong></td>
                            <td>
                                @if($reservation->rooms && $reservation->rooms->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($reservation->rooms as $room)
                                            <li>{{ $room->room_type ?? $room->name ?? 'Room details unavailable' }}
                                                @if($room->pivot && isset($room->pivot->number_of_rooms))
                                                    (x{{ $room->pivot->number_of_rooms }}) <!-- Assuming pivot table stores quantity if applicable -->
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    Room details not available.
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                @if($reservation->bill)
                    <h5 class="mt-4">Billing Details:</h5>
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Room Charges:</td>
                                <td class="text-end">${{ number_format($reservation->bill->room_charges ?? 0, 2) }}</td>
                            </tr>
                            @if(isset($reservation->bill->extra_charges) && $reservation->bill->extra_charges > 0)
                            <tr>
                                <td>Extra Charges:</td>
                                <td class="text-end">${{ number_format($reservation->bill->extra_charges, 2) }}</td>
                            </tr>
                            @endif
                            @if(isset($reservation->bill->taxes) && $reservation->bill->taxes > 0)
                            <tr>
                                <td>Taxes:</td>
                                <td class="text-end">${{ number_format($reservation->bill->taxes, 2) }}</td>
                            </tr>
                            @endif
                            @if(isset($reservation->bill->discount) && $reservation->bill->discount > 0)
                            <tr>
                                <td>Discount:</td>
                                <td class="text-end">-${{ number_format($reservation->bill->discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="fw-bold">
                                <td>Grand Total:</td>
                                <td class="text-end">${{ number_format($reservation->bill->total_amount ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($reservation->bill->payment)
                        <h5 class="mt-4">Payment Information:</h5>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>
                                        @if($reservation->bill->payment->method === 'credit_card')
                                            Credit Card (Online)
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $reservation->bill->payment->method ?? 'N/A')) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Amount Paid:</strong></td>
                                    <td>${{ number_format($reservation->bill->payment->amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Date:</strong></td>
                                    <td>{{ $reservation->bill->payment->paid_at ? \Carbon\Carbon::parse($reservation->bill->payment->paid_at)->format('F j, Y, g:i a') : 'N/A' }}</td>
                                </tr>
                                 @if($reservation->bill->payment->transaction_id) <!-- If you store a transaction ID -->
                                <tr>
                                    <td><strong>Transaction ID:</strong></td>
                                    <td>{{ $reservation->bill->payment->transaction_id }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @else
                        <p class="mt-3">Payment details are not available for this bill.</p>
                    @endif
                @else
                    <p class="mt-3">Billing details are not available for this reservation.</p>
                @endif

                <hr>
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary"><i class="bi bi-house"></i> Go to Homepage</a>
                    <!-- Add a link to user's booking history if such a page exists -->
                    {{-- @auth
                    <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-secondary ms-2">My Bookings</a>
                    @endauth --}}
                </div>

            </div>
        </div>
    @else
        <div class="alert alert-danger text-center">
            <h4 class="alert-heading">Reservation Not Found!</h4>
            <p>We could not find the details for this reservation. Please check your confirmation email or contact support.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Go to Homepage</a>
        </div>
    @endif
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * { /* Target the main card for printing */
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
        }
        .btn, hr, nav, aside /* Hide buttons, hrs, navs, asides when printing */ {
            display: none !important;
        }
    }
</style>
@endsection
