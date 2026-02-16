<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// خل الكود يشتغل كل دقيقة عشان يفحص المواعيد بدقة
Schedule::command('app:check-task-deadlines')->everyMinute();
