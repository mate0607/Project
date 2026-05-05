<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Car;
use App\Models\Message;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    private function isAdminRelevantCar(Car $car): bool
    {
        return $car->user()?->where('role', 'admin')->exists()
            || $car->sales()->exists()
            || $car->messages()
                ->where(function ($query) {
                    $query->whereHas('sender', fn ($userQuery) => $userQuery->where('role', 'admin'))
                        ->orWhereHas('receiver', fn ($userQuery) => $userQuery->where('role', 'admin'));
                })
                ->exists();
    }

    private function validateAntiSpam(Request $request, string $scope, int $scopeId): string
    {
        $user = auth()->user();
        $message = trim((string) $request->input('message'));

        if ($user && $user->isAdmin()) {
            return $message;
        }

        $rateKey = "messages:{$scope}:{$scopeId}:{$user->id}";
        if (RateLimiter::tooManyAttempts($rateKey, 6)) {
            throw ValidationException::withMessages([
                'message' => 'Túl sok üzenetet küldtél rövid idő alatt. Kérlek várj 1 percet.',
            ]);
        }
        RateLimiter::hit($rateKey, 60);

        $normalized = mb_strtolower(preg_replace('/\s+/u', ' ', $message) ?? $message);
        $hasLink = (bool) preg_match('/https?:\/\/|www\.|bit\.ly|t\.me|telegram|whatsapp|discord\.gg/i', $message);
        $containsSpamPhrase = Str::contains($normalized, [
            'ingyen penz',
            'gyors meggazdagodas',
            'crypto',
            'kaszino',
            'adult',
        ]);

        $isDuplicate = Message::where('sender_id', $user->id)
            ->when($scope === 'car', fn ($query) => $query->where('car_id', $scopeId))
            ->when($scope === 'sale', fn ($query) => $query->where('sale_id', $scopeId))
            ->whereRaw('LOWER(message) = ?', [mb_strtolower($message)])
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($hasLink || $containsSpamPhrase || $isDuplicate) {
            throw ValidationException::withMessages([
                'message' => 'A rendszer spam-gyanús üzenetet észlelt. Kérlek módosítsd a szöveget.',
            ]);
        }

        return $message;
    }

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

        $message = $this->validateAntiSpam($request, 'car', (int) $car->id);

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
            'message' => $message,
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
            ->where(function ($query) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('role', 'admin'))
                    ->orWhereHas('sales')
                    ->orWhereHas('messages', function ($messageQuery) {
                        $messageQuery->whereHas('sender', fn ($userQuery) => $userQuery->where('role', 'admin'))
                            ->orWhereHas('receiver', fn ($userQuery) => $userQuery->where('role', 'admin'));
                    });
            })
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
        if (!$this->isAdminRelevantCar($car)) {
            abort(404);
        }

        $messages = Message::with('sender')
            ->where('car_id', $car->id)
            ->oldest()
            ->get();

        Message::where('car_id', $car->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $car->load(['user', 'sales']);

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

    /**
     * User sends a message about a sale listing.
     */
    public function storeSaleMessage(Request $request, Sale $sale)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = auth()->user();

        // The seller and admin can always message; other users can message if the sale is active.
        if (!$user->isAdmin() && $sale->seller_id !== $user->id && !$sale->is_active) {
            abort(403);
        }

        $message = $this->validateAntiSpam($request, 'sale', (int) $sale->id);

        if ($user->isAdmin()) {
            // Admin replies to the last non-admin sender
            $lastUserMessage = Message::where('sale_id', $sale->id)
                ->whereHas('sender', fn ($q) => $q->where('role', '!=', 'admin'))
                ->latest()
                ->first();
            $receiverId = $lastUserMessage ? $lastUserMessage->sender_id : $sale->seller_id;
        } elseif ($sale->seller_id === $user->id) {
            // Seller replies to the last non-seller message sender
            $lastBuyerMessage = Message::where('sale_id', $sale->id)
                ->where('sender_id', '!=', $user->id)
                ->latest()
                ->first();
            $receiverId = $lastBuyerMessage ? $lastBuyerMessage->sender_id : User::where('role', 'admin')->value('id');
        } else {
            // Buyer messages the seller
            $receiverId = $sale->seller_id;
        }

        Message::create([
            'sale_id' => $sale->id,
            'car_id' => $sale->car_id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $message,
        ]);

        AdminNotification::create([
            'user_id' => $receiverId,
            'title' => 'Új üzenet érkezett',
            'message' => $user->name . ' üzenetet küldött (' . ($sale->brand ?? '') . ' ' . ($sale->model ?? '') . ')',
            'is_read' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Üzenet elküldve!');
    }

    /**
     * Get messages for a sale (JSON for AJAX).
     */
    public function saleMessages(Sale $sale)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $sale->seller_id !== $user->id && !$sale->is_active) {
            abort(403);
        }

        Message::where('sale_id', $sale->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::with('sender')
            ->where('sale_id', $sale->id)
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
     * Admin moderation: soft delete a message.
     */
    public function destroy(Message $message)
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403);
        }

        $message->delete();

        return back()->with('success', 'Üzenet moderálva (törölve).');
    }
}
