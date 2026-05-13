<?php

namespace App\Repositories;

use App\Models\UserLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RdpManagerAccountRepo
{
    public const MANAGER_HC_REGION_ROLE = 800;
    public const MANAGER_ASET_REGION_ROLE = 900;
    public const ROLE_IDS = [
        self::MANAGER_HC_REGION_ROLE,
        self::MANAGER_ASET_REGION_ROLE,
    ];

    public static function getByKey($id)
    {
        return UserLogin::with('user_roles:id,name')
            ->whereIn('user_role_id', self::ROLE_IDS)
            ->find($id);
    }

    public static function getDT()
    {
        return UserLogin::query()
            ->with('user_roles:id,name')
            ->whereIn('user_role_id', self::ROLE_IDS)
            ->orderBy('user_role_id');
    }

    public static function update($id, $data)
    {
        try {
            $user = UserLogin::whereIn('user_role_id', self::ROLE_IDS)->findOrFail($id);

            $payload = [
                'nickname' => $data['nickname'],
                'username' => $data['username'],
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);

            return true;
        } catch (\Exception $e) {
            Log::error("Update manager account failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
