<?php

namespace App\Repositories;

use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterVendor;
use App\Models\RdpPengadaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RdpPengadaanRepo
{
    public const DEFAULT_STATUS = 'Diajukan';
    public const SPV_REJECTED_STATUS = 'Pengajuan Ditolak SPV, cek catatan';
    public const REVISION_STATUS = 'Pengajuan Revisi';
    public const VENDOR_ASSIGNED_STATUS = 'Vendor Ditugaskan, menunggu proposal vendor';
    public const PROPOSAL_SUBMITTED_STATUS = 'Proposal Vendor Diajukan, menunggu persetujuan Admin/SPV';
    public const PROPOSAL_SPV_APPROVED_STATUS = 'Proposal Disetujui SPV, menunggu Pimpinan';
    public const PROPOSAL_PIMPINAN_REJECTED_STATUS = 'Proposal Ditolak Pimpinan';
    public const SPK_READY_STATUS = 'Proposal Disetujui Pimpinan, Penerbitan SPK';
    public const WORK_RUNNING_STATUS = 'SPK Terbit, Pekerjaan Pengadaan Berjalan';
    public const VENDOR_FINISHED_STATUS = 'Pengadaan Selesai oleh Vendor, menunggu verifikasi Admin/SPV';
    public const RESULT_SPV_APPROVED_STATUS = 'Pengadaan Disetujui SPV, menunggu Pimpinan';
    public const FINISHED_STATUS = 'Pengadaan Selesai';
    public const CANCEL_STATUS = 'Pengadaan Dibatalkan';

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

    public const ADMIN_ACTIONABLE_STATUS = [
        self::DEFAULT_STATUS,
        self::REVISION_STATUS,
        self::PROPOSAL_SUBMITTED_STATUS,
        self::SPK_READY_STATUS,
        self::VENDOR_FINISHED_STATUS,
    ];

    public const PIMPINAN_ACTIONABLE_STATUS = [
        self::PROPOSAL_SPV_APPROVED_STATUS,
        self::RESULT_SPV_APPROVED_STATUS,
    ];

    public const VENDOR_ACTIONABLE_STATUS = [
        self::VENDOR_ASSIGNED_STATUS,
        self::WORK_RUNNING_STATUS,
    ];

    public const KARYAWAN_ACTIONABLE_STATUS = [
        self::SPV_REJECTED_STATUS,
    ];

    public const PIMPINAN_VISIBLE_STATUS = self::STATUS_LIST;
    public const FILE_DIR_PROPOSAL = 'rdp/pengadaan/proposal';
    public const FILE_DIR_HASIL = 'rdp/pengadaan/hasil';

    public static function getByKey($id)
    {
        return RdpPengadaan::with(self::relations())->find($id);
    }

    public static function getDT($data = [])
    {
        $query = RdpPengadaan::query()
            ->with([
                'rdp_karyawan_masuks.data_employees.master_positions',
                'rdp_karyawan_masuks.rdp_master_rumahs.rdp_master_clusters',
                'rdp_master_vendors',
            ])
            ->withCount('rdp_pengadaan_items');

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
        $query = RdpPengadaan::query();

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

    public static function getVendors()
    {
        return RdpMasterVendor::query()
            ->where('status', RdpMasterVendorRepo::DEFAULT_STATUS)
            ->orderBy('nama')
            ->get();
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

    public static function create($data, $items)
    {
        try {
            DB::transaction(function () use ($data, $items) {
                $payload = self::normalizePayload($data);
                if (!self::isActivePenempatan($payload['rdp_karyawan_masuk_id'] ?? null)) {
                    throw new \Exception('Penempatan tidak aktif.');
                }

                $pengadaan = RdpPengadaan::create($payload);
                self::syncItems($pengadaan, $items);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Insert rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function update($id, $data, $items = null, $allowForwardStatus = true)
    {
        try {
            DB::transaction(function () use ($id, $data, $items, $allowForwardStatus) {
                $pengadaan = RdpPengadaan::findOrFail($id);
                $payload = self::normalizePayload($data, $pengadaan);

                if (
                    !empty($payload['rdp_karyawan_masuk_id'])
                    && (int) $payload['rdp_karyawan_masuk_id'] !== (int) $pengadaan->rdp_karyawan_masuk_id
                    && !self::isActivePenempatan($payload['rdp_karyawan_masuk_id'])
                ) {
                    throw new \Exception('Penempatan tidak aktif.');
                }

                if (
                    !$allowForwardStatus
                    && isset($payload['status'])
                    && $payload['status'] !== $pengadaan->status
                    && !self::isBackwardOrSameStatus($pengadaan->status, $payload['status'])
                ) {
                    throw new \Exception('Status hanya boleh dimundurkan dari halaman edit admin.');
                }

                if (
                    !$allowForwardStatus
                    && isset($payload['status'])
                    && $payload['status'] !== $pengadaan->status
                    && self::isBackwardOrSameStatus($pengadaan->status, $payload['status'])
                ) {
                    self::rollbackProcessArtifacts($pengadaan, $payload['status'], $payload);
                }

                $pengadaan->update($payload);

                if ($items !== null) {
                    self::syncItems($pengadaan, $items);
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Update rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = RdpPengadaan::with('rdp_pengadaan_items')->findOrFail($id);
                if (self::statusIndex($item->status) >= self::statusIndex(self::PROPOSAL_SUBMITTED_STATUS)) {
                    throw new \Exception('Pengadaan sudah masuk proses vendor.');
                }

                self::deleteFiles($item);
                $item->delete();
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Delete rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function requestRevision($id, $catatan)
    {
        return self::transition($id, self::ADMIN_REVIEWABLE_STATUS, [
            'status' => self::SPV_REJECTED_STATUS,
            'catatan_revisi' => $catatan,
        ], 'Request revision rdp_pengadaans failed');
    }

    public static function submitProposal($id, $file, $items, $vendorId = null)
    {
        try {
            DB::transaction(function () use ($id, $file, $items, $vendorId) {
                $pengadaan = RdpPengadaan::with('rdp_pengadaan_items')->findOrFail($id);
                if ($pengadaan->status !== self::VENDOR_ASSIGNED_STATUS) {
                    throw new \Exception('Invalid status for proposal submission.');
                }

                if ($vendorId !== null && (int) $pengadaan->rdp_master_vendor_id !== (int) $vendorId) {
                    throw new \Exception('Invalid vendor.');
                }

                self::syncItems($pengadaan, $items);

                $pengadaan->update([
                    'file_proposal' => self::storeFile($file, self::FILE_DIR_PROPOSAL, $pengadaan->file_proposal),
                    'status' => self::PROPOSAL_SUBMITTED_STATUS,
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Submit proposal rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function requestProposalRevision($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = RdpPengadaan::findOrFail($id);
                if ($item->status !== self::PROPOSAL_SUBMITTED_STATUS) {
                    throw new \Exception('Invalid status.');
                }

                $payload = ['status' => self::VENDOR_ASSIGNED_STATUS];
                self::rollbackProcessArtifacts($item, self::VENDOR_ASSIGNED_STATUS, $payload);
                $item->update($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Request proposal revision rdp_pengadaans failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approveProposalAdmin($id)
    {
        return self::transition($id, [self::PROPOSAL_SUBMITTED_STATUS], [
            'status' => self::PROPOSAL_SPV_APPROVED_STATUS,
        ], 'Approve proposal admin rdp_pengadaans failed');
    }

    public static function approvePimpinan($id)
    {
        try {
            $item = RdpPengadaan::findOrFail($id);

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
            Log::error("Approve pimpinan rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function rejectPimpinan($id)
    {
        return self::transition($id, [self::PROPOSAL_SPV_APPROVED_STATUS], [
            'status' => self::PROPOSAL_PIMPINAN_REJECTED_STATUS,
        ], 'Reject pimpinan rdp_pengadaans failed');
    }

    public static function publishSpk($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = RdpPengadaan::whereKey($id)->lockForUpdate()->firstOrFail();
                if ($item->status !== self::SPK_READY_STATUS) {
                    throw new \Exception('Status tidak sesuai untuk penerbitan SPK.');
                }

                $payload = ['status' => self::WORK_RUNNING_STATUS];
                if (empty($item->nomor_spk_surat)) {
                    $payload = array_merge($payload, self::generateSpkNumberPayload());
                }

                $item->update($payload);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Publish SPK rdp_pengadaans failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function ensureSpkNumber(RdpPengadaan $item): RdpPengadaan
    {
        try {
            return DB::transaction(function () use ($item) {
                $locked = RdpPengadaan::whereKey($item->id)->lockForUpdate()->firstOrFail();

                if (!empty($locked->nomor_spk_surat)) {
                    return $locked;
                }

                $locked->update(self::generateSpkNumberPayload(self::documentDate($locked)));

                return $locked->refresh();
            });
        } catch (\Exception $e) {
            Log::error('Ensure nomor SPK rdp_pengadaans failed', ['error' => $e->getMessage()]);
            return $item;
        }
    }

    protected static function generateSpkNumberPayload($date = null): array
    {
        $date = $date ?: now()->toDateString();

        return [
            'nomor_spk_surat' => RdpSuratNumberRepo::nextSpkNumber($date),
            'tanggal_spk_surat' => $date,
        ];
    }

    protected static function documentDate(RdpPengadaan $item): string
    {
        return $item->tanggal_spk_surat
            ?: $item->created_at?->toDateString()
            ?: now()->toDateString();
    }

    public static function submitLaporan($id, $items, $vendorId = null)
    {
        try {
            DB::transaction(function () use ($id, $items, $vendorId) {
                $pengadaan = RdpPengadaan::with('rdp_pengadaan_items')->findOrFail($id);
                if ($pengadaan->status !== self::WORK_RUNNING_STATUS) {
                    throw new \Exception('Invalid status for report submission.');
                }

                if ($vendorId !== null && (int) $pengadaan->rdp_master_vendor_id !== (int) $vendorId) {
                    throw new \Exception('Invalid vendor.');
                }

                foreach ($pengadaan->rdp_pengadaan_items as $item) {
                    $itemData = $items[$item->id] ?? [];
                    $payload = [
                        'narasi_hasil_pengadaan' => $itemData['narasi_hasil_pengadaan'] ?? null,
                    ];

                    if (!empty($itemData['foto_hasil_pengadaan'])) {
                        $payload['foto_hasil_pengadaan'] = self::storeFile(
                            $itemData['foto_hasil_pengadaan'],
                            self::FILE_DIR_HASIL,
                            $item->foto_hasil_pengadaan
                        );
                    }

                    $item->update($payload);
                }

                $pengadaan->update(['status' => self::VENDOR_FINISHED_STATUS]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Submit laporan rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function approveLaporanAdmin($id)
    {
        return self::transition($id, [self::VENDOR_FINISHED_STATUS], [
            'status' => self::RESULT_SPV_APPROVED_STATUS,
        ], 'Approve laporan admin rdp_pengadaans failed');
    }

    public static function cancelByAdmin($id)
    {
        try {
            $item = RdpPengadaan::findOrFail($id);
            if (
                in_array($item->status, [self::FINISHED_STATUS, self::CANCEL_STATUS], true)
                || self::statusIndex($item->status) >= self::statusIndex(self::WORK_RUNNING_STATUS)
            ) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel admin rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function cancelByKaryawan($id, $employeeId)
    {
        try {
            $item = RdpPengadaan::whereHas('rdp_karyawan_masuks', function ($query) use ($employeeId) {
                $query->where('data_employee_id', $employeeId);
            })->findOrFail($id);

            if (!in_array($item->status, self::EDITABLE_STATUS, true)) {
                return false;
            }

            $item->update(['status' => self::CANCEL_STATUS]);
            return true;
        } catch (\Exception $e) {
            Log::error("Cancel karyawan rdp_pengadaans failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function transition($id, $allowedStatus, $payload, $logMessage)
    {
        try {
            $item = RdpPengadaan::findOrFail($id);
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

    protected static function statusIndex($status)
    {
        $index = array_search($status, self::STATUS_FLOW, true);

        return $index === false ? null : $index;
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

    protected static function rollbackProcessArtifacts($pengadaan, $targetStatus, &$payload)
    {
        $targetIndex = self::statusIndex($targetStatus);
        if ($targetIndex === null) {
            return;
        }

        if ($targetIndex < self::statusIndex(self::PROPOSAL_SUBMITTED_STATUS)) {
            self::deleteFile(self::FILE_DIR_PROPOSAL, $pengadaan->file_proposal);
            $payload['file_proposal'] = null;
        }

        if ($targetIndex < self::statusIndex(self::VENDOR_ASSIGNED_STATUS)) {
            $payload['rdp_master_vendor_id'] = null;
        }

        if ($targetIndex < self::statusIndex(self::VENDOR_FINISHED_STATUS)) {
            $pengadaan->rdp_pengadaan_items()->get()->each(function ($item) {
                self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_pengadaan);
                $item->update([
                    'narasi_hasil_pengadaan' => null,
                    'foto_hasil_pengadaan' => null,
                ]);
            });
        }
    }

    protected static function syncItems($pengadaan, $items)
    {
        $existingItems = $pengadaan->rdp_pengadaan_items()->get()->keyBy('id');
        $keptIds = [];

        foreach ($items as $item) {
            $payload = [
                'nama_item' => $item['nama_item'] ?? null,
                'deskripsi_item' => $item['deskripsi_item'] ?? null,
                'jumlah' => $item['jumlah'] ?? null,
                'satuan' => $item['satuan'] ?? null,
            ];

            $existing = !empty($item['id']) ? $existingItems->get((int) $item['id']) : null;
            if ($existing) {
                $existing->update($payload);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = $pengadaan->rdp_pengadaan_items()->create($payload)->id;
            }
        }

        $existingItems->except($keptIds)->each(function ($item) {
            self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_pengadaan);
            $item->delete();
        });
    }

    protected static function deleteFiles($pengadaan)
    {
        self::deleteFile(self::FILE_DIR_PROPOSAL, $pengadaan->file_proposal);
        foreach ($pengadaan->rdp_pengadaan_items as $item) {
            self::deleteFile(self::FILE_DIR_HASIL, $item->foto_hasil_pengadaan);
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
            'rdp_pengadaan_items',
        ];
    }
}
