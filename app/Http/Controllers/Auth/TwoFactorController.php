<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('2fa:user:id');
        $code = TwoFactorCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if ($code) {
            Auth::loginUsingId($userId);
            $code->delete(); // Invalidate code
            session()->forget('2fa:user:id');
            // Redirect based on role
            $user = Auth::user();
            if ($user->role === 'organizer') {
                return redirect('/organizer/dashboard');
            } elseif ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/');
            }
        }

        return back()->withErrors(['code' => 'Invalid or expired code.']);
    }
} 