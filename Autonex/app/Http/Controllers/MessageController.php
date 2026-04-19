<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Car;
use App\Models\Message;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * User or admin sends a message about a car.
     */
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = auth()->user();
        $isOwner = $car->user_id == $user->id;
        $hasActiveSale = Sale::where('car_id', $car->id)->where('is_active', true)->exists();

        if (!$user->isAdmin() && !$isOwner && !$hasActiveSale) {
            abort(403);
        }

        if ($user->isAdmin()) {
            // Admin válasza: az utolsó nem-admin üzenet küldőjének megy
            $lastUserMessage = Message::where('car_id', $car->id)
                ->whereHas('sender', fn ($q) => $q->where('role', '!=', 'admin'))
                ->latest()
                ->first();
            $receiverId = $lastUserMessage ? $lastUserMessage->sender_id : $car->user_id;
        } else {
            $receiverId = User::where('role', 'admin')->value('id');
        }

        Message::create([
            'car_id' => $car->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->input('message'),
        ]);

        // Értesítés a címzettnek
        AdminNotification::create([
            'user_id' => $receiverId,
            'title' => 'Új üzenet érkezett',
            'message' => $user->name . ' üzenetet küldött (' . $car->make_model . ')',
            'is_read' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Üzenet elküldve!');
    }

    /**
     * Get messages for a car (JSON for AJAX).
     */
    public function carMessages(Car $car)
    {
        $user = auth()->user();
        $isOwner = $car->user_id == $user->id;
        $hasActiveSale = Sale::where('car_id', $car->id)->where('is_active', true)->exists();

        if (!$user->isAdmin() && !$isOwner && !$hasActiveSale) {
            abort(403);
        }

        Message::where('car_id', $car->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::with('sender')
            ->where('car_id', $car->id)
            ->oldest()
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'message' => $m->message,
                'sender_name' => $m->sender->name,
                'sender_id' => $m->sender_id,
                'is_mine' => $m->sender_id === $user->id,
                'created_at' => $m->created_at->format('Y.m.d H:i'),
            ]);

        return response()->json($messages);
    }

    /**
     * Admin: list all cars that have messages.
     */
    public function adminIndex()
    {
        $carsWithMessages = Car::with(['user', 'messages' => fn ($q) => $q->latest()])
            ->whereHas('messages')
            ->get()
            ->map(function ($car) {
                $car->unread_count = $car->messages
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                $car->last_message = $car->messages->first();
                return $car;
            })
            ->sortByDesc('unread_count');

        return view('messages.admin-index', compact('carsWithMessages'));
    }

    /**
     * Admin: view conversation for a specific car.
     */
    public function adminConversation(Car $car)
    {
        $messages = Message::with('sender')
            ->where('car_id', $car->id)
            ->oldest()
            ->get();

        Message::where('car_id', $car->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $car->load('user');

        return view('messages.admin-conversation', compact('messages', 'car'));
    }

    /**
     * Unread count for badge.
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
