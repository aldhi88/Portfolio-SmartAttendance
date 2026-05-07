<?php

namespace App\Repositories;

use App\Models\RdpMasterAset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdpMasterAsetRepo
{
    public static function getByKey($id)
    {
        return RdpMasterAset::find($id);
    }

    public static function getDT($data)
    {
        return RdpMasterAset::query();
    }

    public static function getAll()
    {
        return RdpMasterAset::orderBy('perlengkapan')->get();
    }

    public static function create($data)
    {
        try {
            RdpMasterAset::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_master_asets failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function createMultiple($items)
    {
        try {
            DB::transaction(function () use ($items) {
                foreach ($items as $item) {
                    RdpMasterAset::create($item);
                }
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Insert multiple rdp_master_asets failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            RdpMasterAset::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_master_asets failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            RdpMasterAset::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_master_asets failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function deleteMultiple($ids)
    {
        try {
            RdpMasterAset::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple rdp_master_asets failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
