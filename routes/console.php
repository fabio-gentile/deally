<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Clear expired password reset tokens every 15 minutes
Schedule::command('auth:clear-resets')->everyFifteenMinutes();

// Send newsletter every day at 16:00
Schedule::command('app:send-daily-newsletter')->dailyAt('16:00');
