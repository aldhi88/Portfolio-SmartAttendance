<?php

namespace App\Repositories;

use App\Models\UserLogin;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthInterface
{
    public function login($data)
    {
        $isLoginValid = Auth::attempt(
            [
                'username' => $data['username'],
                'password' => $data['password']
            ]
        );

        return $isLoginValid;
    }
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return true;
    }
}
