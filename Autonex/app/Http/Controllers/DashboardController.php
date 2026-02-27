<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use App\Models\Issue;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'cars' => Car::count(),
            'issues' => Issue::count(),
            'appointments' => Appointment::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    public function user()
    {
        $appointments = Appointment::with('car')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.user', compact('appointments'));
    }
}
