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
                    'data_employees:id,user_login_id,master_organization_id,status',
                    'data_employees.master_organizations:id,is_rdp_eligible',
                    'rdp_master_vendors:id,user_login_id,status',
                ])
                ->where('username', $data['username'])
                ->first()
                ->toArray()
            ;

            if (count($user) > 0 && $data['username'] != 'superuser' && $user['user_role_id'] == 700) {
                if (($user['rdp_master_vendors']['status'] ?? null) != 'Aktif') {
                    return "not_active";
                }
            }

            if (
                count($user) > 0
                && $data['username'] != 'superuser'
                && $user['user_role_id'] == 300
                && !($user['data_employees']['master_organizations']['is_rdp_eligible'] ?? false)
            ) {
                return "not_rdp_eligible";
            }

            if(count($user) > 0 && $data['username'] != 'superuser' && !in_array($user['user_role_id'], [600, 700, 800, 900])){
                if(($user['data_employees']['status'] ?? null) != 'Aktif'){
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
