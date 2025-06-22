<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CustomFilamentLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $panel = filament()->getCurrentPanel();

        if ($panel) {
            $panelId = $panel->getId();
            $authGuard = $panel->getAuthGuard();

            Auth::guard('admin')->logout();

            return match ($panelId) {
                'customer' => redirect('/login'),
                'dashboard', 'admin' => redirect('/dashboard/login'),
                default => redirect('/login')
            };
        }

        Auth::logout();
        return redirect('/login');
    }
}
