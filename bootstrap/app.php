<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UsersMiddleware;
use App\Http\Middleware\ProductsMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CartsMiddleware;
use App\Http\Middleware\OrderMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'UsersMiddleware'       => UsersMiddleware::class,
            'ProductsMiddleware'    => ProductsMiddleware::class,
            'AdminMiddleware'       => AdminMiddleware::class,
            'CartsMiddleware'       => CartsMiddleware::class,
            'OrderMiddleware'       => OrderMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
