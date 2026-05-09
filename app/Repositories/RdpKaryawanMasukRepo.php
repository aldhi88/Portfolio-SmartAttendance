<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterRumah;
use App\Models\RdpMasterRumahAset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RdpKaryawanMasukRepo
{
    public const DEFAULT_STATUS = 'Diajukan';
    public const SPV_APPROVED_STATUS = 'Berkas Disetujui SPV, menuggu Pimpinan';
    public const SPV_REJECTED_STATUS = 'Berkas Ditolak SPV, cek catatan';
    public const REVISION_STATUS = 'Pengajuan Revisi';
    public const PIMPINAN_APPROVED_STATUS = 'Berkas Disetujui Pimpinan, menuggu pendataan aset';
    public const ASSET_SUBMITTED_STATUS = 'Pengajuan Pendataan Aset';
    public const ASSET_SPV_APPROVED_STATUS = 'Pendataan Disetujui SPV, menuggu Pimpinan';
    public const FINISHED_STATUS = 'Penempatan Selesai';
    public const CANCEL_STATUS = 'Penempatan Dibatalkan';
    public const ASSET_STATUS_LIST = ['Ada', 'Tidak Ada'];
    public const EDITABLE_STATUS = [
        'Diajukan',
        'Berkas Ditolak SPV, cek catatan',
        'Pengajuan Revisi',
    ];
    public const STATUS_LIST = [
        'Diajukan',
        'Berkas Disetujui SPV, menuggu Pimpinan',
        'Berkas Ditolak SPV, cek catatan',
        'Pengajuan Revisi',
        'Berkas Disetujui Pimpinan, menuggu pendataan aset',
        'Pengajuan Pendataan Aset',
        'Pendataan Disetujui SPV, menuggu Pimpinan',
        'Penempatan Selesai',
        'Penempatan Dibatalkan',
    ];
    public const PIMPINAN_VISIBLE_STATUS = self::STATUS_LIST;
    public const ADMIN_REVIEWABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::REVISION_STATUS,
    ];
    public const ADMIN_ACTIONABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::REVISION_STATUS,
        self::PIMPINAN_APPROVED_STATUS,
        self::ASSET_SUBMITTED_STATUS,
    ];
    public const KARYAWAN_ACTIONABLE_STATUS = [
        self::SPV_REJECTED_STATUS,
        self::PIMPINAN_APPROVED_STATUS,
    ];
    public const PIMPINAN_ACTIONABLE_STATUS = [
        self::SPV_APPROVED_STATUS,
        self::ASSET_SPV_APPROVED_STATUS,
    ];
    public const FILE_DIR = 'rdp/izin-penempatan';

    public static function getByKey($id)
    {
        return RdpKaryawanMasuk::with([
            'data_employees.master_organizations',
            'data_employees.master_positions',
            'data_employees.master_locations',
            'data_employees.master_functions',
            'rdp_master_rumahs.rdp_master_clusters',
            'rdp_master_rumahs.rdp_master_rumah_asets.rdp_master_asets',
        ])->find($id);
    }

    public static function getDT($data = [])
    {
        $query = RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_positions',
                'rdp_master_rumahs.rdp_master_clusters',
            ]);

        if (array_key_exists('data_employee_id', $data)) {
            if (empty($data['data_employee_id'])) {
                $query->whereRaw('1 = 0');
            }

            $query->where('data_employee_id', $data['data_employee_id']);
        }

        if (!empty($data['status_in'])) {
            $query->whereIn('status', $data['status_in']);
        }

        return $query;
    }

    public static function countActionable($role, $id = null)
    {
        $query = RdpKaryawanMasuk::query();

        if ($role === 'admin') {
            return $query->whereIn('status', self::ADMIN_ACTIONABLE_STATUS)->count();
        }

        if ($role === 'karyawan') {
            if (empty($id)) {
                return 0;
            }

            return $query
                ->where('data_employee_id', $id)
                ->whereIn('status', self::KARYAWAN_ACTIONABLE_STATUS)
                ->count();
        }

        if ($role === 'pimpinan') {
            return $query->whereIn('status', self::PIMPINAN_ACTIONABLE_STATUS)->count();
        }

        return 0;
    }

    public static function getEmployees()
    {
        return DataEmployee::query()
            ->with([
                'master_organizations:id,name',
                'master_positions:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
            ])
            ->orderBy('name')
            ->get();
    }

    public static function getRumahs($selectedRumahId = null)
    {
        return RdpMasterRumah::query()
            ->with('rdp_master_clusters:id,nama_cluster')
            ->where(function ($query) use ($selectedRumahId) {
                $query->where('status', 'Kosong');

                if (!empty($selectedRumahId)) {
                    $query->orWhere('id', $selectedRumahId);
                }
            })
            ->orderBy('block')
            ->orderBy('nomor')
            ->get();
    }

    public static function getRumahAsets($rumahId)
    {
        if (empty($rumahId)) {
            return collect();
        }

        return RdpMasterRumahAset::with('rdp_master_asets')
            ->where('rdp_master_rumah_id', $rumahId)
            ->orderBy('id')
            ->get();
    }

    public static function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                $payload = self::normalizePayload($data);
                RdpKaryawanMasuk::create($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function createWithAsets($data, $asetData)
    {
        try {
            DB::transaction(function () use ($data, $asetData) {
                $payload = self::normalizePayload($data);
                RdpKaryawanMasuk::create($payload);
                self::updateRumahAsets($payload['rdp_master_rumah_id'] ?? null, $asetData);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_karyawan_masuks with aset failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                $item = RdpKaryawanMasuk::findOrFail($id);
                $payload = self::normalizePayload($data, $item);
                $item->update($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            RdpKaryawanMasuk::findOrFail($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function cancel($id)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if (!in_array($item->status, self::EDITABLE_STATUS)) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approveBerkasAdmin($id, $rumahId)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if (!in_array($item->status, self::ADMIN_REVIEWABLE_STATUS, true)) {
                return false;
            }

            if (empty($rumahId)) {
                return false;
            }

            $item->update([
                'rdp_master_rumah_id' => $rumahId,
                'status' => self::SPV_APPROVED_STATUS,
                'catatan_revisi_berkas' => null,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Approve berkas rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function requestRevisionBerkasAdmin($id, $catatan)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if (!in_array($item->status, self::ADMIN_REVIEWABLE_STATUS, true)) {
                return false;
            }

            $item->update([
                'status' => self::SPV_REJECTED_STATUS,
                'catatan_revisi_berkas' => $catatan,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Request revision berkas rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function cancelByAdmin($id)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if ($item->status === self::FINISHED_STATUS) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel by admin rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approvePimpinan($id)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if ($item->status !== self::SPV_APPROVED_STATUS) {
                if ($item->status !== self::ASSET_SPV_APPROVED_STATUS) {
                    return false;
                }

                DB::transaction(function () use ($item) {
                    $item->update(['status' => self::FINISHED_STATUS]);
                    $item->rdp_master_rumahs?->update(['status' => 'Terisi']);
                });

                return true;
            }

            $item->update(['status' => self::PIMPINAN_APPROVED_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Approve pimpinan rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function rejectPimpinan($id)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if (!in_array($item->status, [self::SPV_APPROVED_STATUS, self::ASSET_SPV_APPROVED_STATUS])) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Reject pimpinan rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function getPendataanAsets($id)
    {
        $item = RdpKaryawanMasuk::findOrFail($id);

        if (empty($item->rdp_master_rumah_id)) {
            return collect();
        }

        return RdpMasterRumahAset::with('rdp_master_asets')
            ->where('rdp_master_rumah_id', $item->rdp_master_rumah_id)
            ->orderBy('id')
            ->get();
    }

    public static function submitPendataanAsets($id, $asetData, $nextStatus = self::ASSET_SUBMITTED_STATUS)
    {
        try {
            DB::transaction(function () use ($id, $asetData, $nextStatus) {
                $item = RdpKaryawanMasuk::findOrFail($id);
                if ($item->status !== self::PIMPINAN_APPROVED_STATUS) {
                    throw new \Exception('Invalid status for asset submission.');
                }

                if (empty($item->rdp_master_rumah_id)) {
                    throw new \Exception('Rumah belum dipilih.');
                }

                self::updateRumahAsets($item->rdp_master_rumah_id, $asetData);

                $item->update(['status' => $nextStatus]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Submit pendataan aset rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approvePendataanAset($id)
    {
        try {
            $item = RdpKaryawanMasuk::findOrFail($id);
            if ($item->status !== self::ASSET_SUBMITTED_STATUS) {
                return false;
            }

            $item->update(['status' => self::ASSET_SPV_APPROVED_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Approve pendataan aset rdp_karyawan_masuks failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function normalizePayload($data, $existing = null)
    {
        $payload = collect($data)->only([
            'data_employee_id',
            'rdp_master_rumah_id',
            'nomor_sk_mutasi',
            'tanggal_sk_mutasi',
            'tanggal_mulai',
            'catatan_revisi_berkas',
            'status',
        ])->toArray();

        if (!empty($data['file_sk_mutasi'])) {
            $payload['file_sk_mutasi'] = self::storeFile($data['file_sk_mutasi'], $existing?->file_sk_mutasi);
        }

        if (empty($payload['status'])) {
            $payload['status'] = self::DEFAULT_STATUS;
        }

        return $payload;
    }

    protected static function updateRumahAsets($rumahId, $asetData)
    {
        if (empty($rumahId)) {
            throw new \Exception('Rumah belum dipilih.');
        }

        $validAsetIds = RdpMasterRumahAset::where('rdp_master_rumah_id', $rumahId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        foreach ($asetData as $key => $data) {
            if (!in_array((int) $key, $validAsetIds, true)) {
                continue;
            }

            RdpMasterRumahAset::where('rdp_master_rumah_id', $rumahId)
                ->where('id', $key)
                ->update([
                    'jenis' => $data['jenis'] ?? null,
                    'jumlah' => $data['jumlah'] ?? null,
                    'satuan' => $data['satuan'] ?? null,
                    'status' => $data['status'] ?? 'Ada',
                    'catatan' => $data['catatan'] ?? null,
                ]);
        }
    }

    protected static function storeFile($file, $oldFile = null)
    {
        $fileName = uniqid('sk_mutasi_', true) . '.' . $file->extension();
        $file->storeAs(self::FILE_DIR, $fileName, 'public');

        if (!empty($oldFile)) {
            Storage::disk('public')->delete(self::FILE_DIR . '/' . $oldFile);
        }

        return $fileName;
    }
}
