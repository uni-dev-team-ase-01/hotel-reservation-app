<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (session()->has('pending_booking')) {
            return route('reservation.paymentForm');
        }
        if (Auth::check() && Auth::user()->hasRole('customer')) {
            // Assuming 'filament.customer.pages.dashboard' is the correct route name (updated)
            // for the customer Hotels Listing page based on FilamentPHP structure.
            return route('filament.customer.pages.dashboard');
        }
        return '/'; // Fallback
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasRole('customer')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors(['email' => 'Access denied. Customers only.']);
        }

        // If the user is a customer, the AuthenticatesUsers trait will call $this->redirectPath(),
        // which internally calls the updated redirectTo() method. This handles both
        // pending_booking and direct login scenarios correctly.
        return redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return $this->loggedOut($request) ?: redirect('/');
    }

    protected function guard()
    {
        return Auth::guard('web');
    }
}
