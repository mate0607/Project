<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Car;
use App\Models\Issue;
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
            ->whereRaw('TIMESTAMP(`date`, `time`) >= ?', [$this->currentTimestamp()])
            ->orderBy('date')
            ->orderBy('time')
            ->limit(8)
            ->get();
    }

    public function admin()
    {
        $stats = [
            'cars' => Car::count(),
            'users' => User::count(),
            'issues' => Issue::count(),
            'appointments' => Appointment::count(),
            'todayAppointments' => Appointment::where('date', now()->toDateString())->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'confirmedAppointments' => Appointment::where('status', 'confirmed')->count(),
            'pendingIssues' => Issue::count(),
            'completedServices' => Appointment::where('status', 'completed')->count(),
        ];

        $recentActivities = $this->recentActivities();
        [$monthlyLabels, $monthlyCounts] = $this->buildMonthlyAppointmentSeries();
        $upcomingAppointments = $this->upcomingAppointments();

        return view('dashboard.admin', compact(
            'stats',
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

        $carsCount = Car::where('user_id', $userId)->count();

        $appointmentsCount = Appointment::where('user_id', $userId)->count();

        $issuesCount = $this->userIssuesQuery($userId)->count();

        $servicesCount = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $nextAppointment = Appointment::with('car')
            ->where('user_id', $userId)
            ->whereRaw('TIMESTAMP(`date`, `time`) >= ?', [$this->currentTimestamp()])
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        return view('dashboard.user', compact(
            'appointments',
            'carsCount',
            'appointmentsCount',
            'issuesCount',
            'servicesCount',
            'nextAppointment'
        ));
    }
}
