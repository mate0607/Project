<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id
            || $user->id === $message->receiver_id
            || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id;
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id || $user->role === 'admin';
    }

    public function restore(User $user, Message $message): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Message $message): bool
    {
        return $user->role === 'admin';
    }
}
