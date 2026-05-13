<?php

namespace App\Repositories;

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpKaryawanKeluar;
use App\Models\RdpMasterAset;
use App\Models\RdpMasterCluster;
use App\Models\RdpMasterClusterMasterAset;
use App\Models\RdpMasterRumah;
use App\Models\RdpMasterRumahAset;
use App\Models\RdpMasterVendor;
use App\Models\RdpPengadaan;
use App\Models\RdpPengadaanItem;
use App\Models\RdpPerbaikan;
use App\Models\RdpPerbaikanItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RdpReportRepo
{
    public const STATUS_RUMAH_LIST = [
        'Terisi',
        'Kosong',
    ];
    public const ASET_STATUS_LIST = [
        'Ada',
        'Tidak Ada',
    ];
    public const PENEMPATAN_VARIANTS = [
        'penempatan-aktif' => 'Daftar Penempatan Aktif',
        'monitoring-sip' => 'Monitoring Proses SIP',
        'rekap-status-sip' => 'Rekap Status SIP',
        'okupansi-cluster' => 'Okupansi Unit per Cluster',
    ];
    public const PERBAIKAN_VARIANTS = [
        'monitoring-perbaikan' => 'Monitoring Proses Perbaikan',
        'rekap-status-perbaikan' => 'Rekap Status Perbaikan',
        'rekap-vendor-perbaikan' => 'Rekap Beban Vendor Perbaikan',
        'rekap-item-perbaikan' => 'Rekap Item Perbaikan',
    ];
    public const PENGADAAN_VARIANTS = [
        'monitoring-pengadaan' => 'Monitoring Proses Pengadaan',
        'rekap-status-pengadaan' => 'Rekap Status Pengadaan',
        'rekap-vendor-pengadaan' => 'Rekap Beban Vendor Pengadaan',
        'rekap-item-pengadaan' => 'Rekap Item Pengadaan',
    ];
    public const ASET_VARIANTS = [
        'inventaris-unit' => 'Inventaris Aset per Unit',
        'rekap-cluster' => 'Rekap Aset per Cluster',
        'deviasi-standar' => 'Deviasi Aset vs Standar',
        'perubahan-penempatan' => 'Perubahan Aset Penempatan',
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

    public static function getVendors()
    {
        return RdpMasterVendor::query()
            ->orderBy('nama')
            ->get();
    }

    public static function getAsets()
    {
        return RdpMasterAset::query()
            ->orderBy('perlengkapan')
            ->get();
    }

    public static function variants($module)
    {
        return match ($module) {
            'penempatan' => self::PENEMPATAN_VARIANTS,
            'perbaikan' => self::PERBAIKAN_VARIANTS,
            'pengadaan' => self::PENGADAAN_VARIANTS,
            'aset' => self::ASET_VARIANTS,
            default => [],
        };
    }

    public static function statuses($module)
    {
        return match ($module) {
            'penempatan' => RdpKaryawanMasukRepo::STATUS_LIST,
            'perbaikan' => RdpPerbaikanRepo::STATUS_LIST,
            'pengadaan' => RdpPengadaanRepo::STATUS_LIST,
            default => [],
        };
    }

    public static function moduleTitle($module)
    {
        return match ($module) {
            'penempatan' => 'Laporan Penempatan SIP',
            'perbaikan' => 'Laporan Perbaikan',
            'pengadaan' => 'Laporan Pengadaan',
            'aset' => 'Laporan Aset',
            default => 'Laporan RDP',
        };
    }

    public static function moduleDescription($module)
    {
        return match ($module) {
            'penempatan' => 'Monitoring penempatan, proses SIP, status approval, dan okupansi rumah.',
            'perbaikan' => 'Monitoring proses perbaikan, status pekerjaan, vendor, dan item kerusakan.',
            'pengadaan' => 'Monitoring proses pengadaan, status pekerjaan, vendor, dan item kebutuhan.',
            'aset' => 'Monitoring aset standar, aset realisasi unit, kondisi aset, dan deviasi aset terhadap standar cluster.',
            default => 'Monitoring data Rumah Dinas Pertamina.',
        };
    }

    public static function buildModuleReport($module, $filters = [])
    {
        return match ($module) {
            'penempatan' => self::buildPenempatanReport($filters),
            'perbaikan' => self::buildPerbaikanReport($filters),
            'pengadaan' => self::buildPengadaanReport($filters),
            'aset' => self::buildAsetReport($filters),
            default => self::emptyReport($module, $filters),
        };
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

    protected static function buildPenempatanReport($filters = [])
    {
        $variant = $filters['variant'] ?? array_key_first(self::PENEMPATAN_VARIANTS);

        if ($variant === 'monitoring-sip') {
            $items = self::penempatanBaseQuery($filters, 'created_at')
                ->orderByDesc('id')
                ->get();

            return self::reportPayload('penempatan', $filters, [
                ['key' => 'tanggal_pengajuan', 'label' => 'Tanggal Pengajuan', 'align' => 'center'],
                ['key' => 'nama', 'label' => 'Nama Karyawan'],
                ['key' => 'nopek', 'label' => 'NOPek'],
                ['key' => 'jabatan', 'label' => 'Jabatan'],
                ['key' => 'unit', 'label' => 'Unit Rumah'],
                ['key' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'align' => 'center'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'umur', 'label' => 'Umur Data', 'align' => 'center'],
            ], $items->map(function ($item) {
                $employee = $item->data_employees;

                return [
                    'tanggal_pengajuan' => self::formatDate($item->created_at),
                    'nama' => $employee?->name ?: '-',
                    'nopek' => $employee?->number ?: '-',
                    'jabatan' => $employee?->master_positions?->name ?: '-',
                    'unit' => self::unitRumahLabel($item->rdp_master_rumahs),
                    'tanggal_mulai' => self::formatDate($item->tanggal_mulai),
                    'status' => $item->status ?: '-',
                    'umur' => self::ageLabel($item->created_at),
                ];
            }));
        }

        if ($variant === 'rekap-status-sip') {
            $items = self::penempatanBaseQuery($filters, 'created_at')
                ->select('status')
                ->get()
                ->groupBy('status');
            $total = $items->flatten(1)->count();

            return self::reportPayload('penempatan', $filters, [
                ['key' => 'status', 'label' => 'Status SIP'],
                ['key' => 'jumlah', 'label' => 'Jumlah', 'align' => 'center'],
                ['key' => 'persentase', 'label' => 'Persentase', 'align' => 'center'],
            ], $items->map(function ($rows, $status) use ($total) {
                $count = $rows->count();

                return [
                    'status' => $status ?: '-',
                    'jumlah' => $count,
                    'persentase' => self::percentLabel($count, $total),
                ];
            })->values());
        }

        if ($variant === 'okupansi-cluster') {
            $items = RdpMasterRumah::query()
                ->with('rdp_master_clusters')
                ->when(!empty($filters['cluster_id']), fn ($query) => $query->where('rdp_master_cluster_id', $filters['cluster_id']))
                ->when(!empty($filters['status_rumah']), fn ($query) => $query->where('status', $filters['status_rumah']))
                ->get()
                ->groupBy(fn ($rumah) => $rumah->rdp_master_clusters?->nama_cluster ?: '-');

            return self::reportPayload('penempatan', $filters, [
                ['key' => 'cluster', 'label' => 'Cluster'],
                ['key' => 'total_unit', 'label' => 'Total Unit', 'align' => 'center'],
                ['key' => 'terisi', 'label' => 'Terisi', 'align' => 'center'],
                ['key' => 'kosong', 'label' => 'Kosong', 'align' => 'center'],
                ['key' => 'okupansi', 'label' => 'Okupansi', 'align' => 'center'],
            ], $items->map(function ($rows, $cluster) {
                $total = $rows->count();
                $terisi = $rows->where('status', 'Terisi')->count();

                return [
                    'cluster' => $cluster,
                    'total_unit' => $total,
                    'terisi' => $terisi,
                    'kosong' => $rows->where('status', 'Kosong')->count(),
                    'okupansi' => self::percentLabel($terisi, $total),
                ];
            })->values());
        }

        $items = self::penempatanBaseQuery($filters, 'tanggal_mulai')
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereNotNull('rdp_master_rumah_id')
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->get();

        return self::reportPayload('penempatan', $filters, [
            ['key' => 'nama', 'label' => 'Nama Karyawan'],
            ['key' => 'nopek', 'label' => 'NOPek'],
            ['key' => 'organisasi', 'label' => 'Organisasi'],
            ['key' => 'jabatan', 'label' => 'Jabatan'],
            ['key' => 'unit', 'label' => 'Unit Rumah'],
            ['key' => 'status_rumah', 'label' => 'Status Rumah', 'align' => 'center'],
            ['key' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'align' => 'center'],
            ['key' => 'nomor_sk', 'label' => 'Nomor SK Mutasi'],
            ['key' => 'tanggal_keluar', 'label' => 'Tanggal Keluar', 'align' => 'center'],
            ['key' => 'nomor_sk_keluar', 'label' => 'Nomor SK Keluar'],
            ['key' => 'status_keluar', 'label' => 'Status Keluar'],
        ], $items->map(function ($item) {
            $employee = $item->data_employees;
            $rumah = $item->rdp_master_rumahs;
            $keluar = self::latestKeluarForPenempatan($item);

            return [
                'nama' => $employee?->name ?: '-',
                'nopek' => $employee?->number ?: '-',
                'organisasi' => $employee?->master_organizations?->name ?: '-',
                'jabatan' => $employee?->master_positions?->name ?: '-',
                'unit' => self::unitRumahLabel($rumah),
                'status_rumah' => $rumah?->status ?: '-',
                'tanggal_mulai' => self::formatDate($item->tanggal_mulai),
                'nomor_sk' => $item->nomor_sk_mutasi ?: '-',
                'tanggal_keluar' => $keluar ? self::formatDate($keluar->tanggal_keluar) : '',
                'nomor_sk_keluar' => $keluar?->nomor_sk_keluar ?: '',
                'status_keluar' => $keluar?->status ?: '',
            ];
        }));
    }

    protected static function buildPerbaikanReport($filters = [])
    {
        $variant = $filters['variant'] ?? array_key_first(self::PERBAIKAN_VARIANTS);

        if ($variant === 'rekap-status-perbaikan') {
            return self::statusReport('perbaikan', self::perbaikanBaseQuery($filters), $filters);
        }

        if ($variant === 'rekap-vendor-perbaikan') {
            return self::vendorReport('perbaikan', self::perbaikanBaseQuery($filters), $filters);
        }

        if ($variant === 'rekap-item-perbaikan') {
            $items = RdpPerbaikanItem::query()
                ->with('rdp_perbaikans.rdp_master_vendors')
                ->whereHas('rdp_perbaikans', fn ($query) => self::applyPerbaikanFilters($query, $filters))
                ->get()
                ->groupBy(fn ($item) => trim($item->nama_item) ?: '-');

            return self::reportPayload('perbaikan', $filters, [
                ['key' => 'item', 'label' => 'Item Perbaikan'],
                ['key' => 'jumlah_pengajuan', 'label' => 'Jumlah Pengajuan', 'align' => 'center'],
                ['key' => 'selesai', 'label' => 'Selesai', 'align' => 'center'],
                ['key' => 'berjalan', 'label' => 'Berjalan', 'align' => 'center'],
                ['key' => 'pending', 'label' => 'Pending', 'align' => 'center'],
            ], $items->map(fn ($rows, $item) => self::itemBucketRow($item, $rows, 'perbaikan'))->values());
        }

        $items = self::perbaikanBaseQuery($filters)
            ->withCount('rdp_perbaikan_items')
            ->orderByDesc('id')
            ->get();

        return self::detailWorkReport('perbaikan', $filters, $items, 'rdp_perbaikan_items_count');
    }

    protected static function buildPengadaanReport($filters = [])
    {
        $variant = $filters['variant'] ?? array_key_first(self::PENGADAAN_VARIANTS);

        if ($variant === 'rekap-status-pengadaan') {
            return self::statusReport('pengadaan', self::pengadaanBaseQuery($filters), $filters);
        }

        if ($variant === 'rekap-vendor-pengadaan') {
            return self::vendorReport('pengadaan', self::pengadaanBaseQuery($filters), $filters);
        }

        if ($variant === 'rekap-item-pengadaan') {
            $items = RdpPengadaanItem::query()
                ->with('rdp_pengadaans.rdp_master_vendors')
                ->whereHas('rdp_pengadaans', fn ($query) => self::applyPengadaanFilters($query, $filters))
                ->get()
                ->groupBy(fn ($item) => trim($item->nama_item) ?: '-');

            return self::reportPayload('pengadaan', $filters, [
                ['key' => 'item', 'label' => 'Item Pengadaan'],
                ['key' => 'jumlah_pengajuan', 'label' => 'Jumlah Baris', 'align' => 'center'],
                ['key' => 'total_jumlah', 'label' => 'Total Jumlah', 'align' => 'center'],
                ['key' => 'selesai', 'label' => 'Selesai', 'align' => 'center'],
                ['key' => 'berjalan', 'label' => 'Berjalan', 'align' => 'center'],
                ['key' => 'pending', 'label' => 'Pending', 'align' => 'center'],
            ], $items->map(function ($rows, $item) {
                return self::itemBucketRow($item, $rows, 'pengadaan') + [
                    'total_jumlah' => $rows->sum(fn ($row) => (int) $row->jumlah),
                ];
            })->values());
        }

        $items = self::pengadaanBaseQuery($filters)
            ->withCount('rdp_pengadaan_items')
            ->orderByDesc('id')
            ->get();

        return self::detailWorkReport('pengadaan', $filters, $items, 'rdp_pengadaan_items_count');
    }

    protected static function buildAsetReport($filters = [])
    {
        $variant = $filters['variant'] ?? array_key_first(self::ASET_VARIANTS);

        if ($variant === 'rekap-cluster') {
            $items = self::asetItemBaseQuery($filters)
                ->orderBy('rdp_master_rumah_id')
                ->get()
                ->groupBy(function ($item) {
                    $rumah = $item->rdp_master_rumahs;

                    return implode('|', [
                        $rumah?->rdp_master_clusters?->nama_cluster ?: '-',
                        $item->rdp_master_asets?->perlengkapan ?: '-',
                        $item->jenis ?: '-',
                        $item->satuan ?: '-',
                    ]);
                });

            return self::reportPayload('aset', $filters, [
                ['key' => 'cluster', 'label' => 'Cluster'],
                ['key' => 'aset', 'label' => 'Aset'],
                ['key' => 'jenis', 'label' => 'Jenis'],
                ['key' => 'total_unit', 'label' => 'Total Unit', 'align' => 'center'],
                ['key' => 'total_jumlah', 'label' => 'Total Jumlah', 'align' => 'center'],
                ['key' => 'ada', 'label' => 'Ada', 'align' => 'center'],
                ['key' => 'tidak_ada', 'label' => 'Tidak Ada', 'align' => 'center'],
                ['key' => 'satuan', 'label' => 'Satuan', 'align' => 'center'],
            ], $items->map(function ($rows, $key) {
                [$cluster, $aset, $jenis, $satuan] = explode('|', $key);

                return [
                    'cluster' => $cluster,
                    'aset' => $aset,
                    'jenis' => $jenis,
                    'total_unit' => $rows->pluck('rdp_master_rumah_id')->unique()->count(),
                    'total_jumlah' => self::formatQuantity($rows->sum(fn ($row) => self::quantityToNumber($row->jumlah))),
                    'ada' => $rows->where('status', 'Ada')->count(),
                    'tidak_ada' => $rows->where('status', 'Tidak Ada')->count(),
                    'satuan' => $satuan,
                ];
            })->values());
        }

        if ($variant === 'deviasi-standar') {
            $rows = self::asetDeviationRows($filters);

            return self::reportPayload('aset', $filters, [
                ['key' => 'cluster', 'label' => 'Cluster'],
                ['key' => 'unit', 'label' => 'Unit Rumah'],
                ['key' => 'status_rumah', 'label' => 'Status Rumah', 'align' => 'center'],
                ['key' => 'aset', 'label' => 'Aset'],
                ['key' => 'standar', 'label' => 'Standar', 'align' => 'center'],
                ['key' => 'realisasi', 'label' => 'Realisasi', 'align' => 'center'],
                ['key' => 'selisih', 'label' => 'Selisih', 'align' => 'center'],
                ['key' => 'status_deviasi', 'label' => 'Status Deviasi'],
            ], $rows);
        }

        if ($variant === 'perubahan-penempatan') {
            $items = self::penempatanBaseQuery($filters, 'tanggal_mulai')
                ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
                ->whereNotNull('rdp_master_rumah_id')
                ->with('rdp_master_rumahs.rdp_master_rumah_asets.rdp_master_asets')
                ->orderByDesc('tanggal_mulai')
                ->orderByDesc('id')
                ->get();

            return self::reportPayload('aset', $filters, [
                ['key' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'align' => 'center'],
                ['key' => 'tanggal_keluar', 'label' => 'Tanggal Keluar', 'align' => 'center'],
                ['key' => 'nama', 'label' => 'Nama Karyawan'],
                ['key' => 'unit', 'label' => 'Unit Rumah'],
                ['key' => 'total_standar', 'label' => 'Total Standar', 'align' => 'center'],
                ['key' => 'total_realisasi', 'label' => 'Total Realisasi', 'align' => 'center'],
                ['key' => 'sesuai', 'label' => 'Sesuai', 'align' => 'center'],
                ['key' => 'kurang', 'label' => 'Kurang', 'align' => 'center'],
                ['key' => 'lebih', 'label' => 'Lebih', 'align' => 'center'],
                ['key' => 'status_aset', 'label' => 'Status Aset'],
            ], $items->map(function ($item) {
                $keluar = self::latestKeluarForPenempatan($item);
                $summary = self::asetDeviationSummary($item->rdp_master_rumahs);

                return [
                    'tanggal_mulai' => self::formatDate($item->tanggal_mulai),
                    'tanggal_keluar' => $keluar ? self::formatDate($keluar->tanggal_keluar) : '',
                    'nama' => $item->data_employees?->name ?: '-',
                    'unit' => self::unitRumahLabel($item->rdp_master_rumahs),
                    'total_standar' => self::formatQuantity($summary['total_standar']),
                    'total_realisasi' => self::formatQuantity($summary['total_realisasi']),
                    'sesuai' => $summary['sesuai'],
                    'kurang' => $summary['kurang'],
                    'lebih' => $summary['lebih'],
                    'status_aset' => $summary['label'],
                ];
            }));
        }

        $items = self::asetItemBaseQuery($filters)
            ->orderBy('rdp_master_rumah_id')
            ->orderBy('rdp_master_aset_id')
            ->get();

        return self::reportPayload('aset', $filters, [
            ['key' => 'cluster', 'label' => 'Cluster'],
            ['key' => 'unit', 'label' => 'Unit Rumah'],
            ['key' => 'status_rumah', 'label' => 'Status Rumah', 'align' => 'center'],
            ['key' => 'aset', 'label' => 'Aset'],
            ['key' => 'jenis', 'label' => 'Jenis'],
            ['key' => 'jumlah', 'label' => 'Jumlah', 'align' => 'center'],
            ['key' => 'satuan', 'label' => 'Satuan', 'align' => 'center'],
            ['key' => 'status_aset', 'label' => 'Status Aset', 'align' => 'center'],
            ['key' => 'catatan', 'label' => 'Catatan'],
            ['key' => 'update_terakhir', 'label' => 'Update Terakhir', 'align' => 'center'],
        ], $items->map(function ($item) {
            $rumah = $item->rdp_master_rumahs;

            return [
                'cluster' => $rumah?->rdp_master_clusters?->nama_cluster ?: '-',
                'unit' => self::unitRumahLabel($rumah),
                'status_rumah' => $rumah?->status ?: '-',
                'aset' => $item->rdp_master_asets?->perlengkapan ?: '-',
                'jenis' => $item->jenis ?: '-',
                'jumlah' => $item->jumlah ?: '-',
                'satuan' => $item->satuan ?: '-',
                'status_aset' => $item->status ?: '-',
                'catatan' => $item->catatan ?: '-',
                'update_terakhir' => self::formatDate($item->updated_at),
            ];
        }));
    }

    protected static function penempatanBaseQuery($filters = [], $dateColumn = 'created_at')
    {
        $query = RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_organizations',
                'data_employees.master_positions',
                'data_employees.master_locations',
                'data_employees.master_functions',
                'rdp_master_rumahs.rdp_master_clusters',
            ])
            ->when(!empty($filters['cluster_id']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', fn ($q) => $q->where('rdp_master_cluster_id', $filters['cluster_id']));
            })
            ->when(!empty($filters['status_rumah']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', fn ($q) => $q->where('status', $filters['status_rumah']));
            })
            ->when(!empty($filters['rumah_id']), fn ($query) => $query->where('rdp_master_rumah_id', $filters['rumah_id']))
            ->when(!empty($filters['status']), fn ($query) => $query->where('status', $filters['status']));

        return self::applyDateFilters($query, $filters, $dateColumn);
    }

    protected static function perbaikanBaseQuery($filters = [])
    {
        return self::applyPerbaikanFilters(RdpPerbaikan::query()
            ->with([
                'rdp_karyawan_masuks.data_employees.master_positions',
                'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_vendors',
            ]), $filters);
    }

    protected static function pengadaanBaseQuery($filters = [])
    {
        return self::applyPengadaanFilters(RdpPengadaan::query()
            ->with([
                'rdp_karyawan_masuks.data_employees.master_positions',
                'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_vendors',
            ]), $filters);
    }

    protected static function applyPerbaikanFilters(Builder $query, $filters = [])
    {
        $query
            ->when(!empty($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['vendor_id']), fn ($q) => $q->where('rdp_master_vendor_id', $filters['vendor_id']))
            ->when(!empty($filters['cluster_id']), function ($q) use ($filters) {
                $q->whereHas('rdp_karyawan_masuks.rdp_master_rumahs', fn ($sub) => $sub->where('rdp_master_cluster_id', $filters['cluster_id']));
            });

        return self::applyDateFilters($query, $filters, 'created_at');
    }

    protected static function applyPengadaanFilters(Builder $query, $filters = [])
    {
        $query
            ->when(!empty($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['vendor_id']), fn ($q) => $q->where('rdp_master_vendor_id', $filters['vendor_id']))
            ->when(!empty($filters['cluster_id']), function ($q) use ($filters) {
                $q->whereHas('rdp_karyawan_masuks.rdp_master_rumahs', fn ($sub) => $sub->where('rdp_master_cluster_id', $filters['cluster_id']));
            });

        return self::applyDateFilters($query, $filters, 'created_at');
    }

    protected static function asetItemBaseQuery($filters = [])
    {
        $query = RdpMasterRumahAset::query()
            ->with([
                'rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_asets',
            ])
            ->when(!empty($filters['cluster_id']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', fn ($q) => $q->where('rdp_master_cluster_id', $filters['cluster_id']));
            })
            ->when(!empty($filters['status_rumah']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumahs', fn ($q) => $q->where('status', $filters['status_rumah']));
            })
            ->when(!empty($filters['rumah_id']), fn ($query) => $query->where('rdp_master_rumah_id', $filters['rumah_id']))
            ->when(!empty($filters['aset_id']), fn ($query) => $query->where('rdp_master_aset_id', $filters['aset_id']))
            ->when(!empty($filters['status_aset']), fn ($query) => $query->where('status', $filters['status_aset']));

        return self::applyDateFilters($query, $filters, 'updated_at');
    }

    protected static function asetRumahBaseQuery($filters = [])
    {
        return RdpMasterRumah::query()
            ->with([
                'rdp_master_clusters',
                'rdp_master_rumah_asets.rdp_master_asets',
            ])
            ->when(!empty($filters['cluster_id']), fn ($query) => $query->where('rdp_master_cluster_id', $filters['cluster_id']))
            ->when(!empty($filters['status_rumah']), fn ($query) => $query->where('status', $filters['status_rumah']))
            ->when(!empty($filters['rumah_id']), fn ($query) => $query->where('id', $filters['rumah_id']))
            ->when(!empty($filters['aset_id']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumah_asets', fn ($q) => $q->where('rdp_master_aset_id', $filters['aset_id']));
            })
            ->when(!empty($filters['status_aset']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumah_asets', fn ($q) => $q->where('status', $filters['status_aset']));
            })
            ->when(!empty($filters['date_from']) || !empty($filters['date_to']), function ($query) use ($filters) {
                $query->whereHas('rdp_master_rumah_asets', fn ($q) => self::applyDateFilters($q, $filters, 'updated_at'));
            })
            ->orderBy('rdp_master_cluster_id')
            ->orderBy('block')
            ->orderBy('nomor');
    }

    protected static function asetDeviationRows($filters = [])
    {
        $rumahs = self::asetRumahBaseQuery($filters)->get();
        $standards = RdpMasterClusterMasterAset::query()
            ->with('rdp_master_asets')
            ->when(!empty($filters['cluster_id']), fn ($query) => $query->where('cluster_id', $filters['cluster_id']))
            ->when(!empty($filters['aset_id']), fn ($query) => $query->where('aset_id', $filters['aset_id']))
            ->get()
            ->groupBy('cluster_id');

        return $rumahs->flatMap(function ($rumah) use ($standards, $filters) {
            $standardRows = $standards->get($rumah->rdp_master_cluster_id, collect());
            $realisasiRows = $rumah->rdp_master_rumah_asets
                ->when(!empty($filters['aset_id']), fn ($rows) => $rows->where('rdp_master_aset_id', (int) $filters['aset_id']))
                ->when(!empty($filters['status_aset']), fn ($rows) => $rows->where('status', $filters['status_aset']));
            $standardByAset = $standardRows->keyBy('aset_id');
            $realisasiByAset = $realisasiRows->keyBy('rdp_master_aset_id');
            $asetIds = $standardByAset->keys()->merge($realisasiByAset->keys())->unique()->values();

            return $asetIds->map(function ($asetId) use ($rumah, $standardByAset, $realisasiByAset) {
                $standard = $standardByAset->get($asetId);
                $realisasi = $realisasiByAset->get($asetId);
                $standardQty = self::quantityToNumber($standard?->jumlah);
                $realQty = $realisasi && $realisasi->status !== 'Tidak Ada'
                    ? self::quantityToNumber($realisasi->jumlah)
                    : 0;
                $diff = $realQty - $standardQty;

                return [
                    'cluster' => $rumah->rdp_master_clusters?->nama_cluster ?: '-',
                    'unit' => self::unitRumahLabel($rumah),
                    'status_rumah' => $rumah->status ?: '-',
                    'aset' => $standard?->rdp_master_asets?->perlengkapan ?: $realisasi?->rdp_master_asets?->perlengkapan ?: '-',
                    'standar' => self::formatQuantity($standardQty),
                    'realisasi' => self::formatQuantity($realQty),
                    'selisih' => self::formatQuantity($diff),
                    'status_deviasi' => self::deviationLabel($standard, $realisasi, $diff),
                ];
            });
        })->values();
    }

    protected static function applyDateFilters(Builder $query, $filters, $column)
    {
        return $query
            ->when(!empty($filters['date_from']), fn ($q) => $q->whereDate($column, '>=', $filters['date_from']))
            ->when(!empty($filters['date_to']), fn ($q) => $q->whereDate($column, '<=', $filters['date_to']));
    }

    protected static function detailWorkReport($module, $filters, Collection $items, $itemCountColumn)
    {
        return self::reportPayload($module, $filters, [
            ['key' => 'tanggal_pengajuan', 'label' => 'Tanggal Pengajuan', 'align' => 'center'],
            ['key' => 'nama', 'label' => 'Nama Karyawan'],
            ['key' => 'unit', 'label' => 'Unit Rumah'],
            ['key' => 'vendor', 'label' => 'Vendor'],
            ['key' => 'jumlah_item', 'label' => 'Jumlah Item', 'align' => 'center'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'umur', 'label' => 'Umur Data', 'align' => 'center'],
        ], $items->map(function ($item) use ($itemCountColumn) {
            $penempatan = $item->rdp_karyawan_masuks;
            $employee = $penempatan?->data_employees;

            return [
                'tanggal_pengajuan' => self::formatDate($item->created_at),
                'nama' => $employee?->name ?: '-',
                'unit' => self::unitRumahLabel($penempatan?->rdp_master_rumahs),
                'vendor' => $item->rdp_master_vendors?->nama ?: '-',
                'jumlah_item' => $item->{$itemCountColumn} ?? 0,
                'status' => $item->status ?: '-',
                'umur' => self::ageLabel($item->created_at),
            ];
        }));
    }

    protected static function statusReport($module, Builder $query, $filters)
    {
        $items = $query->select('status')->get()->groupBy('status');
        $total = $items->flatten(1)->count();

        return self::reportPayload($module, $filters, [
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'jumlah', 'label' => 'Jumlah', 'align' => 'center'],
            ['key' => 'persentase', 'label' => 'Persentase', 'align' => 'center'],
        ], $items->map(function ($rows, $status) use ($total) {
            $count = $rows->count();

            return [
                'status' => $status ?: '-',
                'jumlah' => $count,
                'persentase' => self::percentLabel($count, $total),
            ];
        })->values());
    }

    protected static function vendorReport($module, Builder $query, $filters)
    {
        $items = $query
            ->with($module === 'perbaikan' ? 'rdp_perbaikan_items' : 'rdp_pengadaan_items')
            ->get()
            ->groupBy(fn ($item) => $item->rdp_master_vendors?->nama ?: 'Belum ada vendor');

        return self::reportPayload($module, $filters, [
            ['key' => 'vendor', 'label' => 'Vendor'],
            ['key' => 'total_pengajuan', 'label' => 'Total Pengajuan', 'align' => 'center'],
            ['key' => 'total_item', 'label' => 'Total Item', 'align' => 'center'],
            ['key' => 'selesai', 'label' => 'Selesai', 'align' => 'center'],
            ['key' => 'berjalan', 'label' => 'Berjalan', 'align' => 'center'],
            ['key' => 'pending', 'label' => 'Pending', 'align' => 'center'],
            ['key' => 'batal', 'label' => 'Batal/Tolak', 'align' => 'center'],
        ], $items->map(function ($rows, $vendor) use ($module) {
            return [
                'vendor' => $vendor,
                'total_pengajuan' => $rows->count(),
                'total_item' => $rows->sum(fn ($row) => $module === 'perbaikan' ? $row->rdp_perbaikan_items->count() : $row->rdp_pengadaan_items->count()),
                'selesai' => $rows->filter(fn ($row) => self::statusBucket($module, $row->status) === 'selesai')->count(),
                'berjalan' => $rows->filter(fn ($row) => self::statusBucket($module, $row->status) === 'berjalan')->count(),
                'pending' => $rows->filter(fn ($row) => self::statusBucket($module, $row->status) === 'pending')->count(),
                'batal' => $rows->filter(fn ($row) => self::statusBucket($module, $row->status) === 'batal')->count(),
            ];
        })->values());
    }

    protected static function itemBucketRow($item, Collection $rows, $module)
    {
        return [
            'item' => $item,
            'jumlah_pengajuan' => $rows->count(),
            'selesai' => $rows->filter(fn ($row) => self::statusBucket($module, $module === 'perbaikan' ? $row->rdp_perbaikans?->status : $row->rdp_pengadaans?->status) === 'selesai')->count(),
            'berjalan' => $rows->filter(fn ($row) => self::statusBucket($module, $module === 'perbaikan' ? $row->rdp_perbaikans?->status : $row->rdp_pengadaans?->status) === 'berjalan')->count(),
            'pending' => $rows->filter(fn ($row) => self::statusBucket($module, $module === 'perbaikan' ? $row->rdp_perbaikans?->status : $row->rdp_pengadaans?->status) === 'pending')->count(),
        ];
    }

    protected static function statusBucket($module, $status)
    {
        if ($module === 'perbaikan') {
            return match ($status) {
                RdpPerbaikanRepo::FINISHED_STATUS => 'selesai',
                RdpPerbaikanRepo::CANCEL_STATUS,
                RdpPerbaikanRepo::SPV_REJECTED_STATUS,
                RdpPerbaikanRepo::PROPOSAL_PIMPINAN_REJECTED_STATUS,
                RdpPerbaikanRepo::PROPOSAL_ASET_REGION_REJECTED_STATUS => 'batal',
                RdpPerbaikanRepo::WORK_RUNNING_STATUS,
                RdpPerbaikanRepo::VENDOR_FINISHED_STATUS,
                RdpPerbaikanRepo::RESULT_SPV_APPROVED_STATUS => 'berjalan',
                default => 'pending',
            };
        }

        return match ($status) {
            RdpPengadaanRepo::FINISHED_STATUS => 'selesai',
            RdpPengadaanRepo::CANCEL_STATUS,
            RdpPengadaanRepo::SPV_REJECTED_STATUS,
            RdpPengadaanRepo::PROPOSAL_PIMPINAN_REJECTED_STATUS => 'batal',
            RdpPengadaanRepo::WORK_RUNNING_STATUS,
            RdpPengadaanRepo::VENDOR_FINISHED_STATUS,
            RdpPengadaanRepo::RESULT_SPV_APPROVED_STATUS => 'berjalan',
            default => 'pending',
        };
    }

    protected static function reportPayload($module, $filters, array $columns, Collection $rows)
    {
        $variant = $filters['variant'] ?? array_key_first(self::variants($module));

        return [
            'module' => $module,
            'title' => self::moduleTitle($module),
            'description' => self::moduleDescription($module),
            'variant' => $variant,
            'variant_label' => self::variants($module)[$variant] ?? '-',
            'columns' => $columns,
            'rows' => $rows->values(),
        ];
    }

    protected static function emptyReport($module, $filters)
    {
        return self::reportPayload($module, $filters, [], collect());
    }

    protected static function formatDate($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : '-';
    }

    protected static function ageLabel($value)
    {
        if (!$value) {
            return '-';
        }

        $days = Carbon::parse($value)->startOfDay()->diffInDays(now()->startOfDay());

        return $days . ' hari';
    }

    protected static function percentLabel($value, $total)
    {
        if ($total <= 0) {
            return '0%';
        }

        return number_format(($value / $total) * 100, 1, ',', '.') . '%';
    }

    protected static function latestKeluarForPenempatan($penempatan)
    {
        if (!$penempatan?->data_employee_id || !$penempatan?->rdp_master_rumah_id) {
            return null;
        }

        return RdpKaryawanKeluar::query()
            ->where('data_employee_id', $penempatan->data_employee_id)
            ->where('rdp_master_rumah_id', $penempatan->rdp_master_rumah_id)
            ->where('status', RdpKaryawanKeluarRepo::FINISHED_STATUS)
            ->when($penempatan->tanggal_mulai, fn ($query) => $query->whereDate('tanggal_keluar', '>=', $penempatan->tanggal_mulai))
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('id')
            ->first();
    }

    protected static function asetDeviationSummary($rumah)
    {
        if (!$rumah) {
            return [
                'total_standar' => 0,
                'total_realisasi' => 0,
                'sesuai' => 0,
                'kurang' => 0,
                'lebih' => 0,
                'label' => 'Belum Ada Unit',
            ];
        }

        $standards = RdpMasterClusterMasterAset::query()
            ->where('cluster_id', $rumah->rdp_master_cluster_id)
            ->get()
            ->keyBy('aset_id');
        $realisasis = $rumah->rdp_master_rumah_asets->keyBy('rdp_master_aset_id');
        $asetIds = $standards->keys()->merge($realisasis->keys())->unique();
        $summary = [
            'total_standar' => 0,
            'total_realisasi' => 0,
            'sesuai' => 0,
            'kurang' => 0,
            'lebih' => 0,
        ];

        foreach ($asetIds as $asetId) {
            $standardQty = self::quantityToNumber($standards->get($asetId)?->jumlah);
            $realisasi = $realisasis->get($asetId);
            $realQty = $realisasi && $realisasi->status !== 'Tidak Ada'
                ? self::quantityToNumber($realisasi->jumlah)
                : 0;
            $diff = $realQty - $standardQty;
            $summary['total_standar'] += $standardQty;
            $summary['total_realisasi'] += $realQty;

            if ($diff < 0) {
                $summary['kurang']++;
            } elseif ($diff > 0) {
                $summary['lebih']++;
            } else {
                $summary['sesuai']++;
            }
        }

        $summary['label'] = $summary['kurang'] > 0
            ? 'Ada Kekurangan'
            : ($summary['lebih'] > 0 ? 'Ada Kelebihan' : 'Sesuai Standar');

        return $summary;
    }

    protected static function deviationLabel($standard, $realisasi, $diff)
    {
        if (!$standard && $realisasi) {
            return 'Di Luar Standar';
        }

        if ($standard && !$realisasi) {
            return 'Belum Didata';
        }

        if ($diff < 0) {
            return 'Kurang';
        }

        if ($diff > 0) {
            return 'Lebih';
        }

        return 'Sesuai';
    }

    protected static function quantityToNumber($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $normalized = str_replace(',', '.', preg_replace('/[^0-9,\.-]/', '', (string) $value));

        return is_numeric($normalized) ? (float) $normalized : 0;
    }

    protected static function formatQuantity($value)
    {
        $value = (float) $value;

        return fmod($value, 1.0) === 0.0
            ? (string) (int) $value
            : rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',');
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
