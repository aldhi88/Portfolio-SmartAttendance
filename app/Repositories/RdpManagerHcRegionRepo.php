<?php

namespace App\Repositories;

use App\Models\UserLogin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RdpManagerHcRegionRepo
{
    public const DEFAULT_ROLE = 800;

    public static function getByKey($id)
    {
        return UserLogin::where('user_role_id', self::DEFAULT_ROLE)->find($id);
    }

    public static function getDT()
    {
        return UserLogin::query()
            ->where('user_role_id', self::DEFAULT_ROLE);
    }

    public static function create($data)
    {
        try {
            UserLogin::create([
                'user_role_id' => self::DEFAULT_ROLE,
                'nickname' => $data['nickname'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Insert manager_hc_region user_login failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                $user = UserLogin::where('user_role_id', self::DEFAULT_ROLE)->findOrFail($id);

                $payload = [
                    'nickname' => $data['nickname'],
                    'username' => $data['username'],
                ];

                if (!empty($data['password'])) {
                    $payload['password'] = Hash::make($data['password']);
                }

                $user->update($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update manager_hc_region user_login failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            UserLogin::where('user_role_id', self::DEFAULT_ROLE)
                ->whereKey($id)
                ->forceDelete();

            return true;
        } catch (\Exception $e) {
            Log::error("Delete manager_hc_region user_login failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function deleteMultiple($ids)
    {
        try {
            UserLogin::where('user_role_id', self::DEFAULT_ROLE)
                ->whereIn('id', $ids)
                ->forceDelete();

            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple manager_hc_region user_login failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
