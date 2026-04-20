<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminNotification::with('user')
            ->where('title', '!=', 'Új üzenet érkezett')
            ->latest();

        if ($request->filled('filter_name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->filter_name . '%'));
        }
        if ($request->filled('filter_plate')) {
            $query->whereHas('user', fn($q) => $q->whereHas('cars', fn($c) => $c->where('license_plate', 'like', '%' . $request->filter_plate . '%')));
        }
        if ($request->filled('filter_vin')) {
            $query->whereHas('user', fn($q) => $q->whereHas('cars', fn($c) => $c->where('vin', 'like', '%' . $request->filter_vin . '%')));
        }
        if ($request->filled('filter_date')) {
            $query->whereDate('created_at', $request->filter_date);
        }

        $notifications = $query->paginate(20)->withQueryString();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create(Request $request)
    {
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'user_id' => 'nullable|exists:users,id',
        ]);

        AdminNotification::create($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Értesítés sikeresen elküldve.');
    }

    public function destroy(AdminNotification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Értesítés törölve.');
    }
}
