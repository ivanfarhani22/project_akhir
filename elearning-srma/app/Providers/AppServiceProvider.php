<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\AttendanceSession;
use App\Observers\MaterialObserver;
use App\Observers\AssignmentObserver;
use App\Observers\GradeObserver;
use App\Observers\AttendanceSessionObserver;

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
        // Register observers
        Material::observe(MaterialObserver::class);
        Assignment::observe(AssignmentObserver::class);
        Grade::observe(GradeObserver::class);
        AttendanceSession::observe(AttendanceSessionObserver::class);
    }
}

