@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reservation Successful!</h2>
    <div class="alert alert-success">
        Thank you for your reservation, {{ $reservation->guest_name }}.<br>
        A confirmation has been sent to {{ $reservation->guest_email }}.
    </div>
    <div class="mb-3">
        <strong>Hotel:</strong> {{ $reservation->hotel->name ?? '' }}<br>
        <strong>Rooms:</strong>
        @if($reservation->rooms)
            {{ implode(', ', $reservation->rooms->pluck('name')->toArray()) }}
        @endif
        <br>
        <strong>Check-in:</strong> {{ $reservation->check_in }}<br>
        <strong>Check-out:</strong> {{ $reservation->check_out }}<br>
        <strong>Guests:</strong> {{ $reservation->adults }} adults, {{ $reservation->children }} children<br>
    </div>
    <a href="{{ route('hotels.index') }}" class="btn btn-primary">Back to Hotel List</a>
</div>
@endsection