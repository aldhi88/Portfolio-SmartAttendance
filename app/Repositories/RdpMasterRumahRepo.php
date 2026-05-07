<?php

namespace App\Repositories;

use App\Models\RdpMasterClusterMasterAset;
use App\Models\RdpMasterRumah;
use App\Models\RdpMasterRumahAset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdpMasterRumahRepo
{
    public static function getByKey($id)
    {
        return RdpMasterRumah::with(['rdp_master_clusters', 'rdp_master_rumah_asets.rdp_master_asets'])->find($id);
    }

    public static function getDT($data)
    {
        return RdpMasterRumah::query()
            ->with('rdp_master_clusters')
            ->withCount('rdp_master_rumah_asets');
    }

    public static function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                $rumah = RdpMasterRumah::create($data);
                self::replicateClusterAset($rumah->id, $rumah->rdp_master_cluster_id);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_master_rumahs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                RdpMasterRumah::find($id)->update($data['rumah']);
                RdpMasterRumahAset::where('rdp_master_rumah_id', $id)->delete();

                foreach ($data['aset'] as $item) {
                    $item['rdp_master_rumah_id'] = $id;
                    RdpMasterRumahAset::create($item);
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_master_rumahs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            RdpMasterRumah::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_master_rumahs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function deleteMultiple($ids)
    {
        try {
            RdpMasterRumah::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple rdp_master_rumahs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function replicateClusterAset($rumahId, $clusterId)
    {
        RdpMasterClusterMasterAset::where('cluster_id', $clusterId)
            ->orderBy('id')
            ->get()
            ->each(function ($item) use ($rumahId) {
                RdpMasterRumahAset::create([
                    'rdp_master_rumah_id' => $rumahId,
                    'rdp_master_aset_id' => $item->aset_id,
                    'jenis' => $item->jenis,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->satuan,
                    'status' => 'Ada',
                    'catatan' => null,
                ]);
            });
    }
}
