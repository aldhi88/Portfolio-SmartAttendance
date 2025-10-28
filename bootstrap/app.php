<?php

use App\Http\Middleware\BlockApiBrowserRequest;
use App\Http\Middleware\BlockHttpApiRequest;
use App\Http\Middleware\CheckAuthorization;
use App\Http\Middleware\CheckKeyApiRequest;
use App\Http\Middleware\EnforceTokenExpiry;
use App\Repositories\AuthRepository;
use App\Repositories\DataEmployeeRepo;
use App\Repositories\DataLemburRepo;
use App\Repositories\DataLiburIzinRepo;
use App\Repositories\DataLiburRepo;
use App\Repositories\DataScheduleBebasRepo;
use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\Interfaces\DataLiburFace;
use App\Repositories\Interfaces\DataLiburIzinFace;
use App\Repositories\Interfaces\DataScheduleBebasFace;
use App\Repositories\Interfaces\LogAttendanceInterface;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterMinorFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use App\Repositories\Interfaces\UserRoleFace;
use App\Repositories\Interfaces\UserRoleInterface;
use App\Repositories\LogAttendanceRepository;
use App\Repositories\MasterFunctionRepo;
use App\Repositories\MasterLocationRepo;
use App\Repositories\MasterMinorRepo;
use App\Repositories\MasterOrganizationRepo;
use App\Repositories\MasterPositionRepo;
use App\Repositories\MasterScheduleRepo;
use App\Repositories\RelDataEmployeeMasterScheduleRepo;
use App\Repositories\UserLoginRepository;
use App\Repositories\UserRoleRepo;
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

        $middleware->web(append: [
            CheckAuthorization::class,

        ]);
        $middleware->api(append: [
            BlockApiBrowserRequest::class,
            BlockHttpApiRequest::class,
            CheckKeyApiRequest::class,
            EnforceTokenExpiry::class,
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
        MasterMinorFace::class => MasterMinorRepo::class,
        MasterOrganizationFace::class => MasterOrganizationRepo::class,
        MasterPositionFace::class => MasterPositionRepo::class,
        MasterLocationFace::class => MasterLocationRepo::class,
        MasterFunctionFace::class => MasterFunctionRepo::class,
        MasterScheduleFace::class => MasterScheduleRepo::class,
        RelDataEmployeeMasterScheduleFace::class => RelDataEmployeeMasterScheduleRepo::class,
        DataEmployeeFace::class => DataEmployeeRepo::class,
        DataLiburFace::class => DataLiburRepo::class,
        DataLiburIzinFace::class => DataLiburIzinRepo::class,
        DataLemburFace::class => DataLemburRepo::class,
        UserRoleFace::class => UserRoleRepo::class,
        DataScheduleBebasFace::class => DataScheduleBebasRepo::class,
    ])
    ->create();
