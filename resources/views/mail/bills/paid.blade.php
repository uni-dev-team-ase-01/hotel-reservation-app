@extends('mail.layout.base')

@section('title', 'Reservation Bill Paid - ' . config('app.name'))

@section('greeting')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        Dear {{ $bill->reservation->user->name }},
    </p>
@endsection

@section('content')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        We are pleased to confirm that your reservation bill has been successfully paid.
    </p>

    @include('mail.components.info-table', [
        'title' => 'Reservation Bill Details',
        'items' => [
            'Reservation ID' => $bill->reservation->confirmation_number,
            'Check-in Date' => \Carbon\Carbon::parse($bill->reservation->check_in_date)->format('F j, Y'),
            'Check-out Date' => isset($bill->reservation->check_out_date)
                ? \Carbon\Carbon::parse($bill->reservation->check_out_date)->format('F j, Y')
                : 'N/A',
            'Total Amount' => '$' . number_format($bill->total_amount, 2),
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
