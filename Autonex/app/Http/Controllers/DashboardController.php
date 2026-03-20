<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Issue;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    // Az aktualis idopont SQL-kompatibilis formaban, hogy a whereRaw hivasok egysegesek legyenek.
    private function currentTimestamp(): string
    {
        return now()->format('Y-m-d H:i:s');
    }

    // User oldali issue darabszam: csak a sajat autoihoz kapcsolodo hibakat szamoljuk.
    private function userIssuesQuery(int $userId)
    {
        return Issue::whereHas('car', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    // Admin dashboard havi chart adatai (feliratok + darabszamok).
    private function buildMonthlyAppointmentSeries(): array
    {
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

        return [$monthlyLabels, $monthlyCounts];
    }

    // Admin aktivitasi feed forrasok egy helyen, jol olvashato strukturaban.
    private function recentActivities(): array
    {
        return [
            [
                'label' => 'Új időpont létrehozva',
                'item' => Appointment::latest()->first(),
                'dateField' => 'created_at',
            ],
            [
                'label' => 'Hibajegy frissítve',
                'item' => Issue::latest('updated_at')->first(),
                'dateField' => 'updated_at',
            ],
            [
                'label' => 'Autó hozzáadva',
                'item' => Car::latest()->first(),
                'dateField' => 'created_at',
            ],
            [
                'label' => 'Időpont törölve',
                'item' => Appointment::onlyTrashed()->latest('deleted_at')->first(),
                'dateField' => 'deleted_at',
            ],
        ];
    }

    // Admin oldali kozelgo idopontok.
    private function upcomingAppointments(): Collection
    {
        return Appointment::with(['car', 'user'])
            ->where(function ($query) {
                $query->where('date', '>', now()->toDateString())
                      ->orWhere(function ($q) {
                          $q->where('date', now()->toDateString())
                            ->where('time', '>=', now()->format('H:i:s'));
                      });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->limit(8)
            ->get();
    }

    public function admin()
    {
        $today = now()->toDateString();

        // Jelenleg szervizben levo autok (in_progress statuszu idopontok egyedi autoi)
        $inServiceCount = Appointment::where('status', 'in_progress')
            ->distinct('car_id')
            ->count('car_id');

        // Mai idopontok listaja
        $todayAppointments = Appointment::with(['car', 'user'])
            ->where('date', $today)
            ->orderBy('time')
            ->get();

        // Mai kesz autok (completed + service_stage = ready, mai datum)
        $todayCompletedCars = Appointment::with(['car', 'user'])
            ->where('date', $today)
            ->where('status', 'completed')
            ->where('service_stage', 'ready')
            ->get();

        $recentActivities = $this->recentActivities();
        [$monthlyLabels, $monthlyCounts] = $this->buildMonthlyAppointmentSeries();
        $upcomingAppointments = $this->upcomingAppointments();

        return view('dashboard.admin', compact(
            'inServiceCount',
            'todayAppointments',
            'todayCompletedCars',
            'recentActivities',
            'monthlyLabels',
            'monthlyCounts',
            'upcomingAppointments'
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

        // A 8 legujabban hozzaadott aktiv elado auto, kepekkel egyutt.
        $latestSales = Sale::with(['car', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard.user', compact(
            'appointments',
            'inServiceCount',
            'upcomingAppointmentsCount',
            'notificationsCount',
            'adminNotifications',
            'nextAppointment',
            'latestSales'
        ));
    }
}
