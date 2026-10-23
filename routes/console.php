<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sync:gbp-metrics')->daily();
Schedule::command('sync:google-ads-metrics')->daily();
Schedule::command('sync:youtube-metrics')->daily();
