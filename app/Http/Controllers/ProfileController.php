<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

      return Redirect::route('profile.edit')->with('success', 'Profile updated sucessfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Account deleted successfully.');
    }

    public function userProfile(Request $request)
{
    $user = $request->user();

    // Optionally check role
    if ($user->role !== 'user') {
        abort(403, 'Unauthorized');
    }

    return view('profile.user', compact('user'));
}
public function uploadPicture(Request $request)
{
    $request->validate([
        'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = $request->user();

    // Store new picture
    $path = $request->file('profile_picture')->store('profile_pictures', 'public');

    // Delete old one (optional)
    if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
        \Storage::disk('public')->delete($user->profile_picture);
    }

    $user->profile_picture = $path;
    $user->save();

    return back()->with('success', 'Profile picture updated.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    $user = $request->user();
    $user->password = $request->password;
    $user->save();

    return back()->with('success', 'Password updated successfully.');
}
public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
    ]);

    $user = auth()->user();
    $user->update($request->only('name', 'email'));

    return back()->with('success', 'Profile updated successfully.');
}
public function show()
{
    return view('user.profile', ['user' => auth()->user()]);
}
public function deletePicture()
{
    $user = auth()->user();

    if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
        Storage::delete('public/' . $user->profile_picture);
    }

    $user->update(['profile_picture' => null]);

    return back()->with('success', 'Profile picture deleted.');
}
}
