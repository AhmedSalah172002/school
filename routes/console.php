<?php

use App\Console\Commands\SendScheduledEmails;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendScheduledEmails::class)->everyMinute();
