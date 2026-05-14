<?php

namespace App\Repositories;

use App\Models\UserLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RdpManagerAccountRepo
{
    public const FILE_DIR_TTD = 'rdp/manager/ttd';
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
            $user = UserLogin::with('user_roles:id,name')
                ->whereIn('user_role_id', self::ROLE_IDS)
                ->findOrFail($id);
            $roleName = trim($data['role_name'] ?? '');

            $payload = [
                'print_role_name' => $roleName !== '' ? $roleName : $user->user_roles?->name,
                'nickname' => $data['nickname'],
                'username' => $data['username'],
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            if (!empty($data['ttd'])) {
                $payload['ttd'] = self::storeTtd($data['ttd'], $user->ttd);
            }

            $user->update($payload);

            return true;
        } catch (\Exception $e) {
            Log::error("Update manager account failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function getPrintSignerByRole($roleId)
    {
        return UserLogin::with('user_roles:id,name')
            ->where('user_role_id', $roleId)
            ->whereIn('user_role_id', self::ROLE_IDS)
            ->first();
    }

    public static function getPrintRoleName($user)
    {
        return $user?->print_role_name ?: $user?->user_roles?->name;
    }

    public static function updateSelf($id, $data)
    {
        try {
            $user = UserLogin::whereIn('user_role_id', self::ROLE_IDS)->findOrFail($id);

            return self::update($user->id, $data);
        } catch (\Exception $e) {
            Log::error('Update self manager account failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function storeTtd($file, $oldFile = null)
    {
        $fileName = uniqid('ttd_manager_', true) . '.' . $file->extension();
        $path = $file->storeAs(self::FILE_DIR_TTD, $fileName, 'public');

        if ($oldFile) {
            Storage::disk('public')->delete(self::FILE_DIR_TTD . '/' . $oldFile);
        }

        return basename($path);
    }
}
