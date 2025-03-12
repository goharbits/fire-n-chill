<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthenticateApi;
use App\Http\Middleware\AuthenticateWeb;
use App\Http\Middleware\AuthenticateGuest;
use App\Http\Middleware\DecryptRequestMiddleware;
use App\Http\Middleware\VerifyRecaptcha;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
        '*',
        ]);

        $middleware->alias([
            'verify_token'    => AuthenticateApi::class,
            'verify.guest'    => AuthenticateGuest::class,
            'verify_web_auth' => Authenticateweb::class,
            'decrypt.request' => DecryptRequestMiddleware::class,
            'recaptcha'       => VerifyRecaptcha::class,
            'admin'           => AdminMiddleware::class

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
