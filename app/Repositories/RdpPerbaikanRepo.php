<?php

namespace App\Repositories;

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterVendor;
use App\Models\RdpPerbaikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RdpPerbaikanRepo
{
    public const DEFAULT_STATUS = 'Diajukan';
    public const SPV_REJECTED_STATUS = 'Pengajuan Ditolak SPV, cek catatan';
    public const REVISION_STATUS = 'Pengajuan Revisi';
    public const VENDOR_ASSIGNED_STATUS = 'Vendor Ditugaskan, menunggu proposal vendor';
    public const PROPOSAL_SUBMITTED_STATUS = 'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV';
    public const PROPOSAL_SPV_APPROVED_STATUS = 'Proposal Disetujui SPV, menunggu Pimpinan';
    public const PROPOSAL_PIMPINAN_REJECTED_STATUS = 'Proposal Ditolak Pimpinan';
    public const SPK_READY_STATUS = 'Proposal Disetujui Pimpinan, Penerbitan SPK';
    public const WORK_RUNNING_STATUS = 'SPK Terbit, Pekerjaan Perbaikan Berjalan';
    public const VENDOR_FINISHED_STATUS = 'Perbaikan Selesai oleh Vendor, menunggu verifikasi Admin/SPV';
    public const RESULT_SPV_APPROVED_STATUS = 'Perbaikan Disetujui SPV, menunggu Pimpinan';
    public const FINISHED_STATUS = 'Perbaikan Selesai';
    public const CANCEL_STATUS = 'Perbaikan Dibatalkan';

    public const STATUS_LIST = [
        self::DEFAULT_STATUS,
        self::SPV_REJECTED_STATUS,
        self::REVISION_STATUS,
        self::VENDOR_ASSIGNED_STATUS,
        self::PROPOSAL_SUBMITTED_STATUS,
        self::PROPOSAL_SPV_APPROVED_STATUS,
        self::PROPOSAL_PIMPINAN_REJECTED_STATUS,
        self::SPK_READY_STATUS,
        self::WORK_RUNNING_STATUS,
        self::VENDOR_FINISHED_STATUS,
        self::RESULT_SPV_APPROVED_STATUS,
        self::FINISHED_STATUS,
        self::CANCEL_STATUS,
    ];
    public const STATUS_FLOW = self::STATUS_LIST;

    public const EDITABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::SPV_REJECTED_STATUS,
        self::REVISION_STATUS,
    ];

    public const ADMIN_REVIEWABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::REVISION_STATUS,
    ];

    public const PIMPINAN_ACTIONABLE_STATUS = [
        self::PROPOSAL_SPV_APPROVED_STATUS,
        self::RESULT_SPV_APPROVED_STATUS,
    ];

    public const ADMIN_ACTIONABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::REVISION_STATUS,
        self::PROPOSAL_SUBMITTED_STATUS,
        self::SPK_READY_STATUS,
        self::VENDOR_FINISHED_STATUS,
    ];

    public const KARYAWAN_ACTIONABLE_STATUS = [
        self::SPV_REJECTED_STATUS,
    ];

    public const VENDOR_ACTIONABLE_STATUS = [
        self::VENDOR_ASSIGNED_STATUS,
        self::WORK_RUNNING_STATUS,
    ];

    public const PIMPINAN_VISIBLE_STATUS = self::STATUS_LIST;
    public const FILE_DIR_KERUSAKAN = 'rdp/perbaikan/kerusakan';
    public const FILE_DIR_PROPOSAL = 'rdp/perbaikan/proposal';
    public const FILE_DIR_HASIL = 'rdp/perbaikan/hasil';

    public static function getByKey($id)
    {
        return RdpPerbaikan::with(self::relations())->find($id);
    }

    public static function getDT($data = [])
    {
        $query = RdpPerbaikan::query()
            ->with([
                'rdp_karyawan_masuks.data_employees.master_positions',
                'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_vendors',
            ])
            ->withCount('rdp_perbaikan_items');

        if (array_key_exists('data_employee_id', $data)) {
            if (empty($data['data_employee_id'])) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('rdp_karyawan_masuks', function ($q) use ($data) {
                    $q->where('data_employee_id', $data['data_employee_id']);
                });
            }
        }

        if (array_key_exists('vendor_id', $data)) {
            if (empty($data['vendor_id'])) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('rdp_master_vendor_id', $data['vendor_id']);
            }
        }

        if (!empty($data['status_in'])) {
            $query->whereIn('status', $data['status_in']);
        }

        return $query;
    }

    public static function countActionable($role, $id = null)
    {
        $query = RdpPerbaikan::query();

        if ($role === 'admin') {
            return $query->whereIn('status', self::ADMIN_ACTIONABLE_STATUS)->count();
        }

        if ($role === 'karyawan') {
            if (empty($id)) {
                return 0;
            }

            return $query
                ->whereIn('status', self::KARYAWAN_ACTIONABLE_STATUS)
                ->whereHas('rdp_karyawan_masuks', function ($q) use ($id) {
                    $q->where('data_employee_id', $id);
                })
                ->count();
        }

        if ($role === 'pimpinan') {
            return $query->whereIn('status', self::PIMPINAN_ACTIONABLE_STATUS)->count();
        }

        if ($role === 'vendor') {
            if (empty($id)) {
                return 0;
            }

            return $query
                ->where('rdp_master_vendor_id', $id)
                ->whereIn('status', self::VENDOR_ACTIONABLE_STATUS)
                ->count();
        }

        return 0;
    }

    public static function getActivePenempatans()
    {
        return RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_positions:id,name',
                'rdp_master_rumahs.rdp_master_clusters:id,nama_cluster',
            ])
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', 'Terisi');
            })
            ->orderByDesc('id')
            ->get();
    }

    public static function getCurrentPenempatanByEmployee($employeeId)
    {
        if (empty($employeeId)) {
            return null;
        }

        return RdpKaryawanMasuk::query()
            ->with([
                'data_employees.master_positions:id,name',
                'rdp_master_rumahs.rdp_master_clusters:id,nama_cluster',
            ])
            ->where('data_employee_id', $employeeId)
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', 'Terisi');
            })
            ->latest('id')
            ->first();
    }

    public static function getVendors()
    {
        return RdpMasterVendor::query()
            ->where('status', RdpMasterVendorRepo::DEFAULT_STATUS)
            ->orderBy('nama')
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

                $perbaikan = RdpPerbaikan::create($payload);
                self::syncItems($perbaikan, $items);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data, $items = null, $allowForwardStatus = true)
    {
        try {
            DB::transaction(function () use ($id, $data, $items, $allowForwardStatus) {
                $perbaikan = RdpPerbaikan::findOrFail($id);
                $payload = self::normalizePayload($data, $perbaikan);

                if (
                    !$allowForwardStatus
                    && isset($payload['status'])
                    && $payload['status'] !== $perbaikan->status
                    && !self::isBackwardOrSameStatus($perbaikan->status, $payload['status'])
                ) {
                    throw new \Exception('Status hanya boleh dimundurkan dari halaman edit admin.');
                }

                if (
                    !empty($payload['rdp_karyawan_masuk_id'])
                    && (int) $payload['rdp_karyawan_masuk_id'] !== (int) $perbaikan->rdp_karyawan_masuk_id
                    && !self::isActivePenempatan($payload['rdp_karyawan_masuk_id'])
                ) {
                    throw new \Exception('Penempatan tidak aktif.');
                }

                if (
                    !$allowForwardStatus
                    && isset($payload['status'])
                    && $payload['status'] !== $perbaikan->status
                    && self::isBackwardOrSameStatus($perbaikan->status, $payload['status'])
                ) {
                    self::rollbackProcessArtifacts($perbaikan, $payload['status'], $payload);
                }

                $perbaikan->update($payload);

                if ($items !== null) {
                    self::syncItems($perbaikan, $items);
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = RdpPerbaikan::with('rdp_perbaikan_items')->findOrFail($id);
                if (self::statusIndex($item->status) >= self::statusIndex(self::PROPOSAL_SUBMITTED_STATUS)) {
                    throw new \Exception('Perbaikan sudah masuk proses vendor.');
                }

                self::deleteFiles($item);
                $item->delete();
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function requestRevision($id, $catatan)
    {
        return self::transition($id, self::ADMIN_REVIEWABLE_STATUS, [
            'status' => self::SPV_REJECTED_STATUS,
            'catatan_revisi' => $catatan,
        ], 'Request revision rdp_perbaikans failed');
    }

    public static function assignVendor($id, $vendorId)
    {
        if (empty($vendorId)) {
            return false;
        }

        return self::transition($id, self::ADMIN_REVIEWABLE_STATUS, [
            'rdp_master_vendor_id' => $vendorId,
            'status' => self::VENDOR_ASSIGNED_STATUS,
            'catatan_revisi' => null,
        ], 'Assign vendor rdp_perbaikans failed');
    }

    public static function submitProposal($id, $file, $vendorId = null)
    {
        try {
            $item = RdpPerbaikan::findOrFail($id);
            if ($item->status !== self::VENDOR_ASSIGNED_STATUS) {
                return false;
            }

            if ($vendorId !== null && (int) $item->rdp_master_vendor_id !== (int) $vendorId) {
                return false;
            }

            $item->update([
                'file_proposal' => self::storeFile($file, self::FILE_DIR_PROPOSAL, $item->file_proposal),
                'status' => self::PROPOSAL_SUBMITTED_STATUS,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Submit proposal rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function requestProposalRevision($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = RdpPerbaikan::findOrFail($id);
                if ($item->status !== self::PROPOSAL_SUBMITTED_STATUS) {
                    throw new \Exception('Invalid status.');
                }

                $payload = ['status' => self::VENDOR_ASSIGNED_STATUS];
                self::rollbackProcessArtifacts($item, self::VENDOR_ASSIGNED_STATUS, $payload);
                $item->update($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Request proposal revision rdp_perbaikans failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approveProposalAdmin($id)
    {
        return self::transition($id, [self::PROPOSAL_SUBMITTED_STATUS], [
            'status' => self::PROPOSAL_SPV_APPROVED_STATUS,
        ], 'Approve proposal admin rdp_perbaikans failed');
    }

    public static function approvePimpinan($id)
    {
        try {
            $item = RdpPerbaikan::findOrFail($id);

            if ($item->status === self::PROPOSAL_SPV_APPROVED_STATUS) {
                $item->update(['status' => self::SPK_READY_STATUS]);
                return true;
            }

            if ($item->status === self::RESULT_SPV_APPROVED_STATUS) {
                $item->update(['status' => self::FINISHED_STATUS]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Approve pimpinan rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function rejectPimpinan($id)
    {
        return self::transition($id, [self::PROPOSAL_SPV_APPROVED_STATUS], [
            'status' => self::PROPOSAL_PIMPINAN_REJECTED_STATUS,
        ], 'Reject pimpinan rdp_perbaikans failed');
    }

    public static function publishSpk($id)
    {
        return self::transition($id, [self::SPK_READY_STATUS], [
            'status' => self::WORK_RUNNING_STATUS,
        ], 'Publish SPK rdp_perbaikans failed');
    }

    public static function submitLaporan($id, $items, $vendorId = null)
    {
        try {
            DB::transaction(function () use ($id, $items, $vendorId) {
                $perbaikan = RdpPerbaikan::with('rdp_perbaikan_items')->findOrFail($id);
                if ($perbaikan->status !== self::WORK_RUNNING_STATUS) {
                    throw new \Exception('Invalid status for report submission.');
                }

                if ($vendorId !== null && (int) $perbaikan->rdp_master_vendor_id !== (int) $vendorId) {
                    throw new \Exception('Invalid vendor.');
                }

                foreach ($perbaikan->rdp_perbaikan_items as $item) {
                    $itemData = $items[$item->id] ?? [];
                    $payload = [
                        'narasi_hasil_perbaikan' => $itemData['narasi_hasil_perbaikan'] ?? null,
                    ];

                    if (!empty($itemData['foto_hasil_perbaikan'])) {
                        $payload['foto_hasil_perbaikan'] = self::storeFile(
                            $itemData['foto_hasil_perbaikan'],
                            self::FILE_DIR_HASIL,
                            $item->foto_hasil_perbaikan
                        );
                    }

                    $item->update($payload);
                }

                $perbaikan->update(['status' => self::VENDOR_FINISHED_STATUS]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Submit laporan rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approveLaporanAdmin($id)
    {
        return self::transition($id, [self::VENDOR_FINISHED_STATUS], [
            'status' => self::RESULT_SPV_APPROVED_STATUS,
        ], 'Approve laporan admin rdp_perbaikans failed');
    }

    public static function cancelByAdmin($id)
    {
        try {
            $item = RdpPerbaikan::findOrFail($id);
            if (
                in_array($item->status, [self::FINISHED_STATUS, self::CANCEL_STATUS], true)
                || self::statusIndex($item->status) >= self::statusIndex(self::WORK_RUNNING_STATUS)
            ) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel admin rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function cancelByKaryawan($id, $employeeId)
    {
        try {
            $item = RdpPerbaikan::whereHas('rdp_karyawan_masuks', function ($query) use ($employeeId) {
                $query->where('data_employee_id', $employeeId);
            })->findOrFail($id);

            if (!in_array($item->status, self::EDITABLE_STATUS, true)) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel karyawan rdp_perbaikans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function transition($id, $allowedStatus, $payload, $logMessage)
    {
        try {
            $item = RdpPerbaikan::findOrFail($id);
            if (!in_array($item->status, $allowedStatus, true)) {
                return false;
            }

            $item->update($payload);
            return true;
        } catch (\Exception $e) {
            Log::error($logMessage, ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function normalizePayload($data, $existing = null)
    {
        $payload = collect($data)->only([
            'rdp_karyawan_masuk_id',
            'rdp_master_vendor_id',
            'catatan_revisi',
            'status',
        ])->toArray();

        if (empty($payload['status'])) {
            $payload['status'] = $existing?->status ?: self::DEFAULT_STATUS;
        }

        return $payload;
    }

    public static function isBackwardOrSameStatus($fromStatus, $toStatus)
    {
        if ($fromStatus === self::CANCEL_STATUS || $toStatus === self::CANCEL_STATUS) {
            return $fromStatus === $toStatus;
        }

        $fromIndex = array_search($fromStatus, self::STATUS_FLOW, true);
        $toIndex = array_search($toStatus, self::STATUS_FLOW, true);

        if ($fromIndex === false || $toIndex === false) {
            return false;
        }

        return $toIndex <= $fromIndex;
    }

    public static function isActivePenempatan($penempatanId)
    {
        if (empty($penempatanId)) {
            return false;
        }

        return RdpKaryawanMasuk::query()
            ->whereKey($penempatanId)
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->whereHas('rdp_master_rumahs', function ($query) {
                $query->where('status', RdpRumahStatusRepo::TERISI);
            })
            ->exists();
    }

    protected static function statusIndex($status)
    {
        $index = array_search($status, self::STATUS_FLOW, true);

        return $index === false ? null : $index;
    }

    protected static function rollbackProcessArtifacts($perbaikan, $targetStatus, &$payload)
    {
        $targetIndex = self::statusIndex($targetStatus);
        if ($targetIndex === null) {
            return;
        }

        if ($targetIndex < self::statusIndex(self::VENDOR_ASSIGNED_STATUS)) {
            $payload['rdp_master_vendor_id'] = null;
        }

        if ($targetIndex < self::statusIndex(self::PROPOSAL_SUBMITTED_STATUS)) {
            self::deleteFile(self::FILE_DIR_PROPOSAL, $perbaikan->file_proposal);
            $payload['file_proposal'] = null;
        }

        if ($targetIndex < self::statusIndex(self::VENDOR_FINISHED_STATUS)) {
            $perbaikan->rdp_perbaikan_items()->get()->each(function ($item) {
                self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_perbaikan);
                $item->update([
                    'narasi_hasil_perbaikan' => null,
                    'foto_hasil_perbaikan' => null,
                ]);
            });
        }
    }

    protected static function syncItems($perbaikan, $items)
    {
        $existingItems = $perbaikan->rdp_perbaikan_items()->get()->keyBy('id');
        $keptIds = [];

        foreach ($items as $item) {
            $payload = [
                'nama_item' => $item['nama_item'] ?? null,
                'deskripsi_kerusakan' => $item['deskripsi_kerusakan'] ?? null,
            ];

            $existing = !empty($item['id']) ? $existingItems->get((int) $item['id']) : null;
            if (!empty($item['foto_kerusakan'])) {
                $payload['foto_kerusakan'] = self::storeFile(
                    $item['foto_kerusakan'],
                    self::FILE_DIR_KERUSAKAN,
                    $existing?->foto_kerusakan
                );
            } elseif ($existing) {
                $payload['foto_kerusakan'] = $existing->foto_kerusakan;
            }

            if ($existing) {
                $existing->update($payload);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = $perbaikan->rdp_perbaikan_items()->create($payload)->id;
            }
        }

        $existingItems->except($keptIds)->each(function ($item) {
            self::deleteFile(self::FILE_DIR_KERUSAKAN, $item->foto_kerusakan);
            self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_perbaikan);
            $item->delete();
        });
    }

    protected static function deleteFiles($perbaikan)
    {
        self::deleteFile(self::FILE_DIR_PROPOSAL, $perbaikan->file_proposal);
        foreach ($perbaikan->rdp_perbaikan_items as $item) {
            self::deleteFile(self::FILE_DIR_KERUSAKAN, $item->foto_kerusakan);
            self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_perbaikan);
        }
    }

    protected static function storeFile($file, $dir, $oldFile = null)
    {
        if ($oldFile) {
            self::deleteFile($dir, $oldFile);
        }

        $path = $file->store($dir, 'public');
        return basename($path);
    }

    protected static function deleteFile($dir, $file)
    {
        if ($file) {
            Storage::disk('public')->delete($dir . '/' . $file);
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
            'rdp_master_vendors',
            'rdp_perbaikan_items',
        ];
    }
}
