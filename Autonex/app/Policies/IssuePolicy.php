<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Issue $issue): bool
    {
        return $user->role === 'admin'
            || $issue->car()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Issue $issue): bool
    {
        return $user->role === 'admin'
            || $issue->car()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Issue $issue): bool
    {
        return $user->role === 'admin'
            || $issue->car()->where('user_id', $user->id)->exists();
    }

    public function restore(User $user, Issue $issue): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Issue $issue): bool
    {
        return $user->role === 'admin';
    }
}
