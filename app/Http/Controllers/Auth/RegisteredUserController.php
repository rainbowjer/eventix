<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'phone_number' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);

        event(new Registered($user));

        // --- 2FA logic ---
        $code = rand(100000, 999999);
        TwoFactorCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => now()->addMinutes(5)]
        );

        // Send code via email
        Mail::raw("Your EventiX registration verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your EventiX Registration Verification Code');
        });

        // Log out user and redirect to 2FA page
        Auth::logout();
        session(['2fa:user:id' => $user->id]);
        return redirect()->route('2fa.verify');
    }
}
