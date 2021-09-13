<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // dd($request);
        $request->authenticate();

        $request->session()->regenerate();

        // CHECK ACCOUNT'S ROLE
        $user = User::where('email', $request->email)->first();
        // dd($user);
        if ($user->role == 'employee') {
            return redirect()->intended(RouteServiceProvider::HOME_EMPLOYEE);
        } elseif ($user->role == 'admin') {
            return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
        } elseif ($user->role == 'director') {
            return redirect()->intended(RouteServiceProvider::HOME_DIRECTOR);
        } else {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
