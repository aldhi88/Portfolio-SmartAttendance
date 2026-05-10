<?php

namespace App\Repositories;

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterCluster;
use App\Models\RdpMasterClusterMasterAset;
use App\Models\RdpMasterRumah;
use App\Models\RdpMasterRumahAset;

class RdpReportRepo
{
    public const STATUS_RUMAH_LIST = [
        'Terisi',
        'Kosong',
    ];

    public static function getClusters()
    {
        return RdpMasterCluster::query()
            ->orderBy('nama_cluster')
            ->get();
    }

    public static function getRumahs()
    {
        return RdpMasterRumah::query()
            ->with('rdp_master_clusters')
            ->orderBy('rdp_master_cluster_id')
            ->orderBy('block')
            ->orderBy('nomor')
            ->get();
    }

    public static function getRumahById($rumahId)
    {
        if (empty($rumahId)) {
            return null;
        }

        return RdpMasterRumah::query()
            ->with('rdp_master_clusters')
            ->find($rumahId);
    }

    public static function getAsetStandar($filters = [])
    {
        return RdpMasterClusterMasterAset::query()
            ->with([
                'rdp_master_clusters',
                'rdp_master_asets',
            ])
            ->when(!empty($filters['cluster_id']), function ($query) use ($filters) {
                $query->where('cluster_id', $filters['cluster_id']);
            })
            ->orderBy('cluster_id')
            ->orderBy('aset_id')
            ->get();
    }

    public static function getAsetRealisasi($rumahId)
    {
        return RdpMasterRumahAset::query()
            ->with([
                'rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_asets',
            ])
            ->where('rdp_master_rumah_id', $rumahId)
            ->orderBy('id')
            ->get();
    }

    public static function getAsetRealisasiSemuaRumah()
    {
        return RdpMasterRumah::query()
            ->with([
                'rdp_master_clusters',
                'rdp_master_rumah_asets.rdp_master_asets',
            ])
            ->orderBy('rdp_master_cluster_id')
            ->orderBy('block')
            ->orderBy('nomor')
            ->get();
    }

    public static function getPenempatan($filters = [])
    {
        return RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_organizations',
                'data_employees.master_positions',
                'data_employees.master_locations',
                'data_employees.master_functions',
                'rdp_master_rumahs.rdp_master_clusters',
            ])
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereNotNull('rdp_master_rumah_id')
            ->when(!empty($filters['cluster_id']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($filters) {
                    $q->where('rdp_master_cluster_id', $filters['cluster_id']);
                });
            })
            ->when(!empty($filters['status_rumah']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($filters) {
                    $q->where('status', $filters['status_rumah']);
                });
            })
            ->orderByDesc('id')
            ->get();
    }

    public static function unitRumahLabel($rumah)
    {
        if (!$rumah) {
            return '-';
        }

        return collect([
            $rumah->rdp_master_clusters?->nama_cluster,
            $rumah->block ? 'Blok ' . $rumah->block : null,
            $rumah->tipe ? 'Tipe ' . $rumah->tipe : null,
            $rumah->nomor ? 'Nomor ' . $rumah->nomor : null,
        ])->filter()->implode(' - ') ?: '-';
    }
}
