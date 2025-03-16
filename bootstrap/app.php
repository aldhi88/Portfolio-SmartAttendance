<?php

use App\Http\Middleware\BlockApiBrowserRequest;
use App\Http\Middleware\BlockHttpApiRequest;
use App\Repositories\Interfaces\UserLoginInterface;
use App\Repositories\Interfaces\UserRoleInterface;
use App\Repositories\UserLoginRepository;
use App\Repositories\UserRoleRepository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            BlockApiBrowserRequest::class,
            BlockHttpApiRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withBindings([
        UserLoginInterface::class => UserLoginRepository::class,
        UserRoleInterface::class => UserRoleRepository::class,
    ])
    ->create();
