<?php

namespace App\Repositories;

use App\Models\RdpMasterCluster;
use App\Models\RdpMasterClusterMasterAset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdpMasterClusterRepo
{
    public static function getByKey($id)
    {
        return RdpMasterCluster::with(['rdp_master_cluster_master_asets.rdp_master_asets'])->find($id);
    }

    public static function getDT($data)
    {
        return RdpMasterCluster::query()
            ->withCount('rdp_master_cluster_master_asets');
    }

    public static function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                $cluster = RdpMasterCluster::create($data['cluster']);
                foreach ($data['detail'] as $item) {
                    $item['cluster_id'] = $cluster->id;
                    RdpMasterClusterMasterAset::create($item);
                }
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_master_clusters failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                RdpMasterCluster::find($id)->update($data['cluster']);
                RdpMasterClusterMasterAset::where('cluster_id', $id)->delete();
                foreach ($data['detail'] as $item) {
                    $item['cluster_id'] = $id;
                    RdpMasterClusterMasterAset::create($item);
                }
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_master_clusters failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            RdpMasterCluster::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_master_clusters failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function deleteMultiple($ids)
    {
        try {
            RdpMasterCluster::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple rdp_master_clusters failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
