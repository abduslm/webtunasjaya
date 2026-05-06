<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiCors;
use App\Http\Middleware\role;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\loginLimit;
use App\Http\Middleware\logLogin;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(ApiCors::class);
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\isAdmin::class,
            'role' => \App\Http\Middleware\role::class,
            'logRequests' => \App\Http\Middleware\LogRequests::class,
            'loginLimit' => \App\Http\Middleware\loginLimit::class,
            'logLogin' => \App\Http\Middleware\logLogin::class,
            'loginLokasi' => \App\Http\Middleware\loginLokasi::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
