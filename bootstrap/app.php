<?php

use \App\Services\ResponseCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Iqbalatma\LaravelJwtAuthentication\Exceptions\MissingRequiredTokenException;
use Iqbalatma\LaravelUtils\APIResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    $e->getMessage(),
                    ResponseCode::ERR_VALIDATION(),
                    errors: $e->errors(),
                    exception: $e
                );
            }
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    message: $e->getMessage(),
                    responseCode: ResponseCode::ERR_NOT_FOUND(),
                    exception: $e
                );
            }
        });

        $exceptions->renderable(function (HttpExceptionInterface $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    $e->getMessage(),
                    exception: $e
                );
            }
        });

        $exceptions->renderable(function (UnauthorizedException $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    $e->getMessage(),
                    responseCode: ResponseCode::ERR_FORBIDDEN(),
                    exception: $e
                );
            }
        });


        $exceptions->renderable(function (AuthenticationException $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    $e->getMessage(),
                    responseCode: ResponseCode::ERR_UNAUTHENTICATED(),
                    exception: $e
                );
            }
        });

        $exceptions->renderable(function (MissingRequiredTokenException $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    $e->getMessage(),
                    responseCode: ResponseCode::ERR_UNAUTHENTICATED(),
                    exception: $e
                );
            }
        });


        $exceptions->renderable(function (Throwable|Exception $e) {
            if (request()->expectsJson()) {
                return new APIResponse(
                    null,
                    config("app.env") === "production" ? "Something went wrong" : $e->getMessage(),
                    exception: $e
                );
            }
        });
    })->create();
