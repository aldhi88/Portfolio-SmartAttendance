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
    public function deleteMulti($id)
    {
        try {
            UserLogin::whereIn('id',$id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete user_logins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByUsername($data)
    {
        return UserLogin::query()
            ->select([
                'id','nickname','username','password'
            ])
            ->with([
                'data_employees' => function($q){
                    $q->select([
                        'id','user_login_id',
                        'master_organization_id',
                        'master_position_id',
                        'name','number','status'
                    ]);
                },
                'data_employees.master_organizations:id,name',
                'data_employees.master_positions:id,name',
            ])
            ->where('username', $data)->first();
    }
}
