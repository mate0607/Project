<?php

namespace App\Http\Controllers\Traits;

use App\Models\Car;

trait AdminHelpers
{
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    private function currentUserId(): ?int
    {
        return auth()->id();
    }

    private function userCarsQuery()
    {
        $query = Car::orderBy('make_model');

        if (!$this->isAdmin()) {
            $query->where('user_id', $this->currentUserId());
        }

        return $query;
    }
}
