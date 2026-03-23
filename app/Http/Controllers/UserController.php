<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin only.');
        }

        $users = User::paginate(50);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin only.');
        }

        $roles = ['admin', 'warehouse_manager', 'operations_officer'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access. Admin only.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,warehouse_manager,operations_officer',
            'is_active' => 'boolean',
        ], [
            'email.unique' => 'This email is already registered in the system.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$validated['name']}' has been created successfully.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access. Admin only.');
        }

        $user = User::findOrFail($id);

        // Prevent admins from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deletion of the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->back()->with('error', 'Cannot delete the last admin user.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$userName}' has been deleted successfully.");
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access. Admin only.');
        }

        $user = User::findOrFail($id);

        // Prevent toggling own account status
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'activated' : 'deactivated';
        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' has been {$statusText}.");
    }
}
