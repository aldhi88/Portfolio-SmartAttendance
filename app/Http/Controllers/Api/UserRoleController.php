<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRoleResource;
use App\Repositories\Interfaces\UserRoleInterface;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    protected $userRoleRepository;

    public function __construct(UserRoleInterface $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    public function index()
    {
        $roles = $this->userRoleRepository->getAll();
        return UserRoleResource::collection($roles);
    }
}
