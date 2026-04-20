<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $calendarMonth)) {
            $calendarMonth = now()->format('Y-m');
        }
        $calendarDate = Carbon::createFromFormat('Y-m', $calendarMonth)->startOfMonth();
        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        $calendarAppointments = Appointment::with(['car', 'user'])
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->groupBy(fn ($a) => Carbon::parse($a->date)->format('Y-m-d'));

        return view('dashboard.admin', compact(
            'inServiceCount',
            'todayAppointments',
            'todayCompletedCars',
            'calendarAppointments',
            'calendarDate'
        ));
    }

    public function user()
    {
        $userId = auth()->id();

        $upcomingAppointmentsCount = Appointment::where('user_id', $userId)
            ->where('date', '>=', now()->toDateString())
            ->count();

        $totalCarsCount = Car::where('user_id', $userId)->count();

        $completedServicesCount = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

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
            'upcomingAppointmentsCount',
            'totalCarsCount',
            'completedServicesCount',
            'nextAppointment',
            'latestSales'
        ));
    }
}
