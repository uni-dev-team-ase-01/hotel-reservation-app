<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        info(Session::get('pending_booking'));
        if (session()->has('pending_booking')) {
            return route('reservation.paymentForm');
        }
        return '/';
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
        if (session()->has('pending_booking')) {
            return redirect($this->redirectTo());
        }
        if (!$user->hasRole('customer')) {
            Auth::guard('web')->logout();

            return redirect('/login')->withErrors(['email' => 'Access denied. Customers only.']);
        }

        return redirect('/');
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
