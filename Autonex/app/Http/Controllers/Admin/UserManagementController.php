<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));

        $users = User::withTrashed()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $admin = auth()->user();

        if ($admin && $admin->id === $user->id) {
            return back()->with('error', 'A saját admin fiókodat nem törölheted.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->whereNull('deleted_at')->count() <= 1) {
            return back()->with('error', 'Az utolsó aktív admin nem törölhető.');
        }

        $user->delete();

        return back()->with('success', 'Felhasználó soft delete-elve.');
    }

    public function restore(int $userId): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($userId);

        if ($user->trashed()) {
            $user->restore();
        }

        return back()->with('success', 'Felhasználó visszaállítva.');
    }
}
