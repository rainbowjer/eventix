<?php

namespace App\Providers;
use App\Models\Event;
use App\Policies\EventPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        // ✅ Remove parent::boot()

        // Add role-based redirect route
        Route::middleware('web')
            ->group(function () {
                Route::get('/redirect-after-login', function () {
                    $user = auth()->user();

                    if ($user->role === 'organizer') {
                        return redirect('/organizer/dashboard');
                    } elseif ($user->role === 'admin') {
                        return redirect('/admin/dashboard');
                    } else {
                        return redirect('/');
                    }
                });
            });
            
    }


    protected $policies = [
    Event::class => EventPolicy::class,
];

}