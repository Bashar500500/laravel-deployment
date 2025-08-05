<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\DatabaseReconnect;
use App\Exceptions\InternalException;
use App\Traits\Response\ResponseTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\CustomException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(DatabaseReconnect::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (InternalException $exception) {
            return (new class { use ResponseTrait; })->setInternalException($exception)
                ->errorResponse();
        });

        $exceptions->renderable(function (NotFoundHttpException $exception) {
            if ($exception->getPrevious() && $exception->getPrevious() instanceof ModelNotFoundException)
            {
                $model = class_basename($exception->getPrevious()->getModel());
                throw CustomException::notFound($model);
            }
            else
            {
                throw CustomException::notFound('Route');
            }
        });

        $exceptions->renderable(function (ValidationException $exception) {
            throw CustomException::unprocessableContent(
                $exception->getMessage(),
                $exception->errors(),
            );
        });

        // $exceptions->renderable(function (AuthenticationException $exception) {
        //     throw CustomException::forbidden(
        //         $exception->getMessage(),
        //         $exception->errors(),
        //     );
        // });

        // $exceptions->renderable(function (AuthorizationException $exception) {
        //     throw CustomException::unauthorized(
        //         $exception->getMessage(),
        //         $exception->errors(),
        //     );
        // });

        // $exceptions->renderable(function (Exception $exception) {
        //     throw CustomException::internalServerError();
        // });
    })->create();
