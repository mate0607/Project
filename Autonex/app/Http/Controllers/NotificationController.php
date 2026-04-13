<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function markAsRead(AdminNotification $notification): JsonResponse
    {
        if ($notification->user_id && $notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $userId = auth()->id();

        AdminNotification::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
