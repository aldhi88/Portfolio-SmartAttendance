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
                ->with([
                    'user_roles:id,name',
                    'data_employees:id,user_login_id,status'
                ])
                ->where('username', $data['username'])
                ->first()
                ->toArray()
            ;

            if (in_array($user['user_roles']['id'], [300])) {
                return "invalid_role";
            }

            if(count($user) > 0 && $data['username'] != 'superuser' && $user['user_role_id']!=600){
                if($user['data_employees']['status'] != 'Aktif'){
                    return "not_active";
                }
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
