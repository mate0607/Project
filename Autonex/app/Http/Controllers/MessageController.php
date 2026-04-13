<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Sale;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        // Az osszes uzenetet lekerjuk, ahol a felhasznalo kuldo vagy fogado.
        $messages = Message::with(['sale', 'sender', 'receiver'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get()
            ->groupBy('sale_id');

        return view('messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $saleId = request('sale_id');
        $sale = Sale::with('seller')->findOrFail($saleId);

        return view('messages.create', compact('sale'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();
        $validated['sender_id'] = auth()->id();

        Message::create($validated);

        return redirect()->route('messages.show_conversation', $validated['sale_id'])
            ->with('success', 'Üzenet elküldve!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        $this->authorize('view', $message);

        $message->load(['sale', 'sender', 'receiver']);

        return view('messages.show', compact('message'));
    }

    /**
     * Show conversation thread for a given sale.
     */
    public function conversation(Sale $sale)
    {
        $userId = auth()->id();

        $messages = Message::with(['sender', 'receiver'])
            ->where('sale_id', $sale->id)
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->oldest()
            ->get();

        // Olvasatlan uzenetek megjelolese olvasottra.
        Message::where('sale_id', $sale->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $sale->load('seller');

        return view('messages.conversation', compact('messages', 'sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        $this->authorize('update', $message);

        return view('messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        $this->authorize('update', $message);

        $message->update($request->validated());

        return redirect()->route('messages.show_conversation', $message->sale_id)
            ->with('success', 'Üzenet frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $saleId = $message->sale_id;
        $message->delete();

        return redirect()->route('messages.show_conversation', $saleId)
            ->with('success', 'Üzenet törölve!');
    }
}
