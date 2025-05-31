<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CustomFilamentLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect('/admin/login');
    }
}
