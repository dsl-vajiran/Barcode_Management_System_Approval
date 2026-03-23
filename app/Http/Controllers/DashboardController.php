<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $dashboardData = [
            'user_role' => $user->role,
            'user_name' => $user->name,
            'role_label' => $this->getRoleLabel($user->role)
        ];

        return view('dashboard.index', $dashboardData);
    }

    /**
     * Get human-readable role label
     */
    private function getRoleLabel($role)
    {
        return match($role) {
            'admin' => 'Administrator',
            'warehouse_manager' => 'Warehouse Manager',
            'operations_officer' => 'Operations Officer',
            default => 'User'
        };
    }
}

