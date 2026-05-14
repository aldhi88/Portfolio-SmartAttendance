<?php

namespace App\Repositories;

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpPermintaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdpPermintaanRepo
{
    public const DEFAULT_STATUS = 'Diajukan';
    public const FINISHED_STATUS = 'Selesai';

    public const STATUS_LIST = [
        self::DEFAULT_STATUS,
        self::FINISHED_STATUS,
    ];

    public const ADMIN_ACTIONABLE_STATUS = [
        self::DEFAULT_STATUS,
    ];

    public static function getByKey($id)
    {
        return RdpPermintaan::with(self::relations())->find($id);
    }

    public static function getDT($data = [])
    {
        $query = RdpPermintaan::query()
            ->with([
                'rdp_karyawan_masuks.data_employees.master_positions',
                'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
            ])
            ->withCount('rdp_permintaan_items');

        if (array_key_exists('data_employee_id', $data)) {
            if (empty($data['data_employee_id'])) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('rdp_karyawan_masuks', function ($q) use ($data) {
                    $q->where('data_employee_id', $data['data_employee_id']);
                });
            }
        }

        if (!empty($data['status_in'])) {
            $query->whereIn('status', $data['status_in']);
        }

        return $query;
    }

    public static function countActionable($role, $id = null)
    {
        $query = RdpPermintaan::query();

        if ($role === 'admin') {
            return $query->whereIn('status', self::ADMIN_ACTIONABLE_STATUS)->count();
        }

        if ($role === 'karyawan') {
            if (empty($id)) {
                return 0;
            }

            return $query
                ->whereIn('status', self::ADMIN_ACTIONABLE_STATUS)
                ->whereHas('rdp_karyawan_masuks', function ($q) use ($id) {
                    $q->where('data_employee_id', $id);
                })
                ->count();
        }

        return 0;
    }

    public static function getCurrentPenempatanByEmployee($employeeId)
    {
        if (empty($employeeId)) {
            return null;
        }

        return RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_organizations:id,name,is_rdp_eligible',
                'data_employees.master_positions:id,name',
                'rdp_master_rumahs.rdp_master_clusters:id,nama_cluster',
            ])
            ->where('data_employee_id', $employeeId)
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('data_employees.master_organizations', function ($query) {
                $query->where('is_rdp_eligible', true);
            })
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', RdpRumahStatusRepo::TERISI);
            })
            ->latest('id')
            ->first();
    }

    public static function getActivePenempatans()
    {
        return RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_organizations:id,name,is_rdp_eligible',
                'data_employees.master_positions:id,name',
                'rdp_master_rumahs.rdp_master_clusters:id,nama_cluster',
            ])
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('data_employees.master_organizations', function ($query) {
                $query->where('is_rdp_eligible', true);
            })
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', RdpRumahStatusRepo::TERISI);
            })
            ->orderByDesc('id')
            ->get();
    }

    public static function create($data, $items)
    {
        try {
            DB::transaction(function () use ($data, $items) {
                $payload = self::normalizePayload($data);
                if (!self::isActivePenempatan($payload['rdp_karyawan_masuk_id'] ?? null)) {
                    throw new \Exception('Penempatan tidak aktif.');
                }

                $permintaan = RdpPermintaan::create($payload);
                self::syncItems($permintaan, $items);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Insert rdp_permintaans failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function markFinished($id)
    {
        try {
            $item = RdpPermintaan::findOrFail($id);
            if ($item->status !== self::DEFAULT_STATUS) {
                return false;
            }

            $item->update([
                'status' => self::FINISHED_STATUS,
                'tanggal_selesai' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Finish rdp_permintaans failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function isActivePenempatan($penempatanId)
    {
        if (empty($penempatanId)) {
            return false;
        }

        return RdpKaryawanMasuk::query()
            ->whereKey($penempatanId)
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('data_employees.master_organizations', function ($query) {
                $query->where('is_rdp_eligible', true);
            })
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', RdpRumahStatusRepo::TERISI);
            })
            ->exists();
    }

    protected static function normalizePayload($data)
    {
        $payload = collect($data)->only([
            'rdp_karyawan_masuk_id',
            'catatan_admin',
            'status',
        ])->toArray();

        if (empty($payload['status'])) {
            $payload['status'] = self::DEFAULT_STATUS;
        }

        if ($payload['status'] === self::FINISHED_STATUS) {
            $payload['tanggal_selesai'] = now();
        }

        return $payload;
    }

    protected static function syncItems($permintaan, $items)
    {
        foreach ($items as $item) {
            $permintaan->rdp_permintaan_items()->create([
                'nama_item' => $item['nama_item'] ?? null,
                'deskripsi_item' => $item['deskripsi_item'] ?? null,
                'jumlah' => $item['jumlah'] ?? null,
                'satuan' => $item['satuan'] ?? null,
            ]);
        }
    }

    protected static function relations()
    {
        return [
            'rdp_karyawan_masuks.data_employees.master_organizations',
            'rdp_karyawan_masuks.data_employees.master_positions',
            'rdp_karyawan_masuks.data_employees.master_locations',
            'rdp_karyawan_masuks.data_employees.master_functions',
            'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
            'rdp_permintaan_items',
        ];
    }
}
