<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Message;
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
            if ($user && !$user->isAdmin()) {
                $navUnreadCount = AdminNotification::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhereNull('user_id');
                })->where('is_read', false)->count();

                $navNotifications = AdminNotification::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhereNull('user_id');
                })->latest()->limit(8)->get();

                $view->with('navNotifications', $navNotifications);
                $view->with('navUnreadCount', $navUnreadCount);
            } else {
                $view->with('navNotifications', collect());
                $view->with('navUnreadCount', 0);
            }

            if ($user && $user->isAdmin()) {
                $adminUnreadMsgCount = Message::where('receiver_id', $user->id)
                    ->where('is_read', false)->count();
                $view->with('adminUnreadMsgCount', $adminUnreadMsgCount);
            } else {
                $view->with('adminUnreadMsgCount', 0);
            }
        });
    }
}
