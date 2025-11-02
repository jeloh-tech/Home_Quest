<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

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
        // Share pending verifications count with admin views
        View::composer('admin.*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $pendingVerificationsCount = User::where('role', 'landlord')
                    ->whereIn('verification_status', ['pending', null])
                    ->count();
                $view->with('pendingVerificationsCount', $pendingVerificationsCount);
            }
        });

        // Share pending rental applications count with landlord views
        View::composer('landlord.*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'landlord') {
                $pendingApplicationsCount = \App\Models\RentalApplication::where('status', 'pending')
                    ->whereHas('listing', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->count();
                $view->with('pendingApplicationsCount', $pendingApplicationsCount);

                // Share unread messages count with landlord views
                $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadMessagesCount', $unreadMessagesCount);
            }
        });

        // Share unread messages count with tenant views
        View::composer('tenant.*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'tenant') {
                $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadMessagesCount', $unreadMessagesCount);
            }
        });
    }
}
