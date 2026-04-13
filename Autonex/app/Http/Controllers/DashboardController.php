<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Issue;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $today = now()->toDateString();

        $inServiceCount = Appointment::where('status', 'in_progress')
            ->distinct('car_id')
            ->count('car_id');

        $todayAppointments = Appointment::with(['car', 'user'])
            ->where('date', $today)
            ->orderBy('time')
            ->get();

        $todayCompletedCars = Appointment::with(['car', 'user'])
            ->where('date', $today)
            ->where('status', 'completed')
            ->where('service_stage', 'ready')
            ->get();

        // Calendar data: current month or requested month
        $calendarMonth = $request->input('month', now()->format('Y-m'));
        $calendarDate = \Carbon\Carbon::createFromFormat('Y-m', $calendarMonth)->startOfMonth();
        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        $calendarAppointments = Appointment::with(['car', 'user'])
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->groupBy(fn ($a) => \Carbon\Carbon::parse($a->date)->format('Y-m-d'));

        // Monthly chart
        $monthlyLabels = [];
        $monthlyCounts = [];
        for ($monthsBack = 5; $monthsBack >= 0; $monthsBack--) {
            $month = now()->copy()->startOfMonth()->subMonths($monthsBack);
            $monthlyLabels[] = $month->isoFormat('MMM');
            $monthlyCounts[] = Appointment::whereBetween('date', [
                $month->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ])->count();
        }

        return view('dashboard.admin', compact(
            'inServiceCount',
            'todayAppointments',
            'todayCompletedCars',
            'calendarAppointments',
            'calendarDate',
            'monthlyLabels',
            'monthlyCounts'
        ));
    }

    public function user()
    {
        $userId = auth()->id();

        $appointments = Appointment::with('car')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $inServiceCount = Appointment::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->distinct('car_id')
            ->count('car_id');

        $upcomingAppointmentsCount = Appointment::where('user_id', $userId)
            ->where('date', '>=', now()->toDateString())
            ->count();

        $totalCarsCount = Car::where('user_id', $userId)->count();

        $completedServicesCount = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $notificationsCount = AdminNotification::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })->count();

        $adminNotifications = AdminNotification::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })->latest()->limit(10)->get();

        $nextAppointment = Appointment::with('car')
            ->where('user_id', $userId)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        $latestSales = Sale::with(['car', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard.user', compact(
            'appointments',
            'inServiceCount',
            'upcomingAppointmentsCount',
            'totalCarsCount',
            'completedServicesCount',
            'notificationsCount',
            'adminNotifications',
            'nextAppointment',
            'latestSales'
        ));
    }
}
