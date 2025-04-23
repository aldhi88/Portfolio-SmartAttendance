<?php

use App\Http\Middleware\BlockApiBrowserRequest;
use App\Http\Middleware\BlockHttpApiRequest;
use App\Http\Middleware\CheckKeyApiRequest;
use App\Repositories\AuthRepository;
use App\Repositories\DataEmployeeRepo;
use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\LogAttendanceInterface;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterMinorFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\UserLoginInterface;
use App\Repositories\Interfaces\UserRoleInterface;
use App\Repositories\LogAttendanceRepository;
use App\Repositories\MasterLocationRepo;
use App\Repositories\MasterMinorRepo;
use App\Repositories\MasterOrganizationRepo;
use App\Repositories\UserLoginRepository;
use App\Repositories\UserRoleRepository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',

        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',

        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn (Request $request) => route('auth.formLogin'));

        $middleware->api(append: [
            BlockApiBrowserRequest::class,
            BlockHttpApiRequest::class,
            CheckKeyApiRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withBindings([
        UserLoginInterface::class => UserLoginRepository::class,
        AuthInterface::class => AuthRepository::class,
        UserRoleInterface::class => UserRoleRepository::class,
        LogAttendanceInterface::class => LogAttendanceRepository::class,
        MasterLocationFace::class => MasterLocationRepo::class,
        MasterMinorFace::class => MasterMinorRepo::class,
        MasterOrganizationFace::class => MasterOrganizationRepo::class,
        DataEmployeeFace::class => DataEmployeeRepo::class,
    ])
    ->create();
