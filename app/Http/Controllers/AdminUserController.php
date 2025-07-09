<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        $users = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,organizer,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        // Prevent demoting the last admin
        if ($user->role === 'admin' && $request->role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot demote the last admin.');
        }
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = $request->password; // Let the mutator handle hashing
        }
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete another admin.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
} 