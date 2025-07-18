<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = $request->user();

    // Update login tracking
    $user->increment('login_count');
    $user->update(['last_login_at' => now()]);

    // Redirect after login based on role
    if ($user->role === 'organizer') {
        return redirect()->intended('/organizer/dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->intended('/admin/dashboard');
    } else {
        return redirect()->intended('/');
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
{
    $user->increment('login_count');
    $user->update(['last_login_at' => now()]);
}
}
