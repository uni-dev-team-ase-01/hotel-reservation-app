@extends('mail.layout.base')

@section('title', 'Reservation No Show - ' . config('app.name'))

@section('greeting')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        Dear {{ $reservation->user->name }},
    </p>
@endsection

@section('content')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        We regret to inform you that your reservation has been marked as a no-show. This is because you did not check in on
        the scheduled date.
    </p>

    @include('mail.components.info-table', [
        'title' => 'Reservation Details',
        'items' => [
            'Reservation ID' => $reservation->confirmation_number,
            'Check-in Date' => \Carbon\Carbon::parse($reservation->check_in_date)->format('F j, Y'),
            'Check-out Date' => isset($reservation->check_out_date)
                ? \Carbon\Carbon::parse($reservation->check_out_date)->format('F j, Y')
                : 'N/A',
            'Guests' => $reservation->number_of_guests ?? 'N/A',
            'Status' => ucfirst($reservation->status ?? 'no show'),
        ],
    ])

    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        Thank you for choosing our service! We look forward to serving you.
    </p>
@endsection

@section('action')
    @if (isset($viewReservationUrl))
        @include('mail.components.button', [
            'url' => $viewReservationUrl,
            'text' => 'View Reservation Details',
            'color' => '#28a745',
        ])
    @endif
@endsection

@section('closing')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        If you have any questions or need to make changes to your reservation, please don't hesitate to contact us.
    </p>
@endsection
