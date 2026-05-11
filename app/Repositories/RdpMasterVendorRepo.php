<?php

namespace App\Repositories;

use App\Models\RdpMasterVendor;
use App\Models\UserLogin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RdpMasterVendorRepo
{
    public const DEFAULT_ROLE = 700;
    public const DEFAULT_STATUS = 'Aktif';

    public static function getByKey($id)
    {
        return RdpMasterVendor::with(['user_logins'])->find($id);
    }

    public static function getDT($data)
    {
        return RdpMasterVendor::query()
            ->with(['user_logins'])
            ->withCount(['rdp_perbaikans', 'rdp_pengadaans']);
    }

    public static function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                $userLogin = UserLogin::create([
                    'user_role_id' => self::DEFAULT_ROLE,
                    'nickname' => $data['nama'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                ]);

                RdpMasterVendor::create([
                    'user_login_id' => $userLogin->id,
                    'nama' => $data['nama'],
                    'telp' => $data['telp'],
                    'alamat' => $data['alamat'],
                    'status' => $data['status'] ?? self::DEFAULT_STATUS,
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_master_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                $vendor = RdpMasterVendor::findOrFail($id);

                $userLogin = [
                    'nickname' => $data['nama'],
                    'username' => $data['username'],
                ];

                if (!empty($data['password'])) {
                    $userLogin['password'] = Hash::make($data['password']);
                }

                UserLogin::where('id', $vendor->user_login_id)->update($userLogin);

                $vendor->update([
                    'nama' => $data['nama'],
                    'telp' => $data['telp'],
                    'alamat' => $data['alamat'],
                    'status' => $data['status'],
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_master_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            if (self::isUsed($id)) {
                return false;
            }

            DB::transaction(function () use ($id) {
                $vendor = RdpMasterVendor::findOrFail($id);
                $userLoginId = $vendor->user_login_id;

                $vendor->delete();
                UserLogin::where('id', $userLoginId)->forceDelete();
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_master_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function deleteMultiple($ids)
    {
        try {
            if (RdpMasterVendor::whereIn('id', $ids)
                ->where(function ($query) {
                    $query->whereHas('rdp_perbaikans')
                        ->orWhereHas('rdp_pengadaans');
                })
                ->exists()) {
                return false;
            }

            DB::transaction(function () use ($ids) {
                $userLoginIds = RdpMasterVendor::whereIn('id', $ids)
                    ->pluck('user_login_id')
                    ->toArray();

                RdpMasterVendor::whereIn('id', $ids)->delete();
                UserLogin::whereIn('id', $userLoginIds)->forceDelete();
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple rdp_master_vendors failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function isUsed($id)
    {
        return RdpMasterVendor::whereKey($id)
            ->where(function ($query) {
                $query->whereHas('rdp_perbaikans')
                    ->orWhereHas('rdp_pengadaans');
            })
            ->exists();
    }
}
