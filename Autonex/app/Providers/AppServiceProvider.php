<?php

namespace App\Providers;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            if ($user && $user->role === 'user') {
                $navNotifications = AdminNotification::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhereNull('user_id');
                })->latest()->limit(8)->get();

                $navUnreadCount = AdminNotification::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhereNull('user_id');
                })->where('is_read', false)->count();

                $view->with('navNotifications', $navNotifications);
                $view->with('navUnreadCount', $navUnreadCount);
            } else {
                $view->with('navNotifications', collect());
                $view->with('navUnreadCount', 0);
            }
        });
    }
}
