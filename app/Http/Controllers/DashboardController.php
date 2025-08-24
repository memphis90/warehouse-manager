<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
    }

        return $this->userDashboard(); // default per tutti gli altri
    }

    public function adminDashboard()
    {
        // TODO: stats per admin
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pending_requests' => \App\Models\Request::whereHas('status', fn($q) => $q->where('name', 'pending'))->count(),
                'total_items' => Item::count(),
                'total_users' => User::count(),
            ],
        ]);
    }

    public function userDashboard()
    {
         return Inertia::render('User/Dashboard', [
            'my_requests' => auth()->user()->load('requests.requestItems.item')->requests,
        ]);
    }
}
