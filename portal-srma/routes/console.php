<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks - Activity Log Cleanup
|--------------------------------------------------------------------------
|
| Task untuk membersihkan activity logs secara otomatis.
| Berjalan setiap hari pada jam 02:00 pagi untuk meminimalkan
| dampak pada performa sistem.
|
| Periode retensi: 6 bulan (dapat diubah sesuai kebijakan)
|
| Untuk menjalankan scheduler di production:
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
*/
Schedule::command('logs:cleanup --months=6')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/cleanup.log'))
    ->description('Cleanup old activity logs (6 months retention)');
