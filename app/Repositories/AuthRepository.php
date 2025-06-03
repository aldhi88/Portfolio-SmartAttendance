<?php

namespace App\Repositories;

use App\Models\UserLogin;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthInterface
{
    public function login($data)
    {
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        if (Auth::validate($credentials)) {
            $user = UserLogin::query()
                ->with('user_roles:id,name')
                ->where('username', $data['username'])
                ->first()
                ->toArray()
                ;

            // if (in_array($user['user_roles']['name'], ['Supervisor', 'Super User'])) {
            //     $isLoginValid = Auth::attempt($credentials );
            //     return $isLoginValid;
            // }else{
            //     return "non pengawas";
            // }
            if (in_array($user['user_roles']['id'], [300])) {
                return "invalid_role";
            }
            $isLoginValid = Auth::attempt($credentials );
            return $isLoginValid;

        }

        return false;

    }
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return true;
    }
}
