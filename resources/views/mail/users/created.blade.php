@extends('mail.layout.base')

@section('title', 'User Account Created - ' . config('app.name'))

@section('greeting')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        Dear {{ $user->name }},
    </p>
@endsection

@section('content')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        We are pleased to confirm that your account has been successfully created.
    </p>

    @include('mail.components.info-table', [
        'title' => 'Account Details',
        'items' => [
            'Email' => $user->email,
            'Password' => $password,
            'Role' => $userRole,
        ],
    ])

    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        Thank you for choosing our service! We look forward to serving you.
    </p>
@endsection

@section('action')

@endsection

@section('closing')
    <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
        If you have any questions or need to make changes to your account, please don't hesitate to contact us.
    </p>
@endsection
