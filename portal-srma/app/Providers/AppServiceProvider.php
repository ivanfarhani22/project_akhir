<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ActivityLogObserver;

// Models yang akan di-observe untuk activity logging
use App\Models\News;
use App\Models\Announcement;
use App\Models\Agenda;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\Banner;
use App\Models\Profile;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Facility;
use App\Models\StudentData;
use App\Models\StudentDistribution;

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
        /*
        |--------------------------------------------------------------------------
        | Activity Log Observers
        |--------------------------------------------------------------------------
        |
        | Mendaftarkan observer untuk auto-logging aktivitas CRUD pada model.
        | Observer akan mencatat: create, update, delete secara otomatis.
        |
        */
        $this->registerActivityLogObservers();
    }

    /**
     * Register Activity Log Observers untuk semua model yang perlu di-log
     */
    protected function registerActivityLogObservers(): void
    {
        $models = [
            News::class,
            Announcement::class,
            Agenda::class,
            Gallery::class,
            GalleryCategory::class,
            Banner::class,
            Profile::class,
            Contact::class,
            Setting::class,
            Teacher::class,
            Staff::class,
            Facility::class,
            StudentData::class,
            StudentDistribution::class,
        ];

        foreach ($models as $model) {
            if (class_exists($model)) {
                $model::observe(ActivityLogObserver::class);
            }
        }
    }
}
