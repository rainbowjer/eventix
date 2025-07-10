<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        // Middleware is handled at route level
    }
    public function index(Request $request)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('role', 'like', "%$search%");
            });
        }
        $users = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        // Prevent admins from editing other admins
        if ($user->role === 'admin' && auth()->user()->id !== $user->id) {
            return back()->with('error', 'Cannot edit another admin user.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,organizer,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $oldRole = $user->role;
        $oldName = $user->name;
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Prevent demoting the last admin
        if ($user->role === 'admin' && $request->role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot demote the last admin.');
        }
        
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = $request->password;
        }
        
        $user->save();

        // Log the admin activity
        AdminActivityLog::create([
            'admin_id' => auth()->id(),
            'action' => 'update_user',
            'description' => "Updated user: {$oldName} (ID: {$user->id}) - Role changed from {$oldRole} to {$user->role}",
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        // Prevent deleting admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete another admin.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $userName = $user->name;
        $userId = $user->id;
        $userRole = $user->role;

        $user->delete();

        // Log the admin activity
        AdminActivityLog::create([
            'admin_id' => auth()->id(),
            'action' => 'delete_user',
            'description' => "Deleted user: {$userName} (ID: {$userId}, Role: {$userRole})",
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
} 