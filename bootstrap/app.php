<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    //se registra aca porque en laravel 11 ya no sirve eso,
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:generar-tareas-repetitivas')->everyMinute()->withoutOverlapping();
        $schedule->command('inspire')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

