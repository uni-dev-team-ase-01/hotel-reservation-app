@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review Your Reservation</h2>
    <div class="mb-3">
        <strong>Hotel:</strong> {{ $hotel->name }}<br>
        <strong>Rooms:</strong> {{ implode(', ', $rooms->pluck('name')->toArray()) }}<br>
        <strong>Check-in:</strong> {{ $checkIn }}<br>
        <strong>Check-out:</strong> {{ $checkOut }}<br>
        <strong>Guests:</strong> {{ $adults }} adults, {{ $children }} children<br>
    </div>
    <a href="{{ route('reservation.paymentForm') }}" class="btn btn-success">Proceed to Payment</a>
</div>
@endsection