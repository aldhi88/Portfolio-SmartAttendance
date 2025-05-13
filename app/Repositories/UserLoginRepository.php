<?php

namespace App\Repositories;

use App\Models\UserLogin;
use App\Repositories\Interfaces\UserLoginInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserLoginRepository implements UserLoginInterface
{
    public const DEFAULT_ROLE = 300;
    public const DEFAULT_PASSWORD = '123456';

    public function create($data)
    {
        if(!isset($data['user_role_id'])){
            $data['user_role_id'] = self::DEFAULT_ROLE;
        }
        if(!isset($data['password'])){
            $data['password'] = Hash::make(self::DEFAULT_PASSWORD);
        }
        try {
            $q = UserLogin::create($data);
            return $q->id;
        } catch (\Exception $e) {
            Log::error("Insert user_logins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id, $data)
    {
        dd($id, $data);
        try {
            UserLogin::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert user_logins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function delete($id)
    {
        try {
            UserLogin::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete user_logins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByUsername($data)
    {
        return UserLogin::with('user_roles')
            ->where('username', $data)->first();
    }
}
