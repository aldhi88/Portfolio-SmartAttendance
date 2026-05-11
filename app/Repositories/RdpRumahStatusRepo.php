<?php

namespace App\Repositories;

use App\Models\RdpKaryawanKeluar;
use App\Models\RdpKaryawanMasuk;
use App\Models\RdpMasterRumah;

class RdpRumahStatusRepo
{
    public const TERISI = 'Terisi';
    public const KOSONG = 'Kosong';
    public const OCCUPANCY_STATUS = [
        self::TERISI,
        self::KOSONG,
    ];

    public static function sync($rumahId)
    {
        if (empty($rumahId)) {
            return false;
        }

        $rumah = RdpMasterRumah::find($rumahId);
        if (!$rumah) {
            return false;
        }

        $latestMasuk = RdpKaryawanMasuk::query()
            ->where('rdp_master_rumah_id', $rumahId)
            ->where('status', RdpKaryawanMasukRepo::FINISHED_STATUS)
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->first();

        if ($latestMasuk && !self::hasFinishedKeluarAfterMasuk($latestMasuk)) {
            return self::updateStatus($rumah, self::TERISI);
        }

        if (in_array($rumah->status, self::OCCUPANCY_STATUS, true)) {
            return self::updateStatus($rumah, self::KOSONG);
        }

        return true;
    }

    public static function syncMany($rumahIds)
    {
        collect($rumahIds)
            ->filter()
            ->unique()
            ->each(fn ($rumahId) => self::sync($rumahId));
    }

    protected static function hasFinishedKeluarAfterMasuk($masuk)
    {
        return RdpKaryawanKeluar::query()
            ->where('rdp_master_rumah_id', $masuk->rdp_master_rumah_id)
            ->where('data_employee_id', $masuk->data_employee_id)
            ->where('status', RdpKaryawanKeluarRepo::FINISHED_STATUS)
            ->whereDate('tanggal_keluar', '>=', $masuk->tanggal_mulai)
            ->exists();
    }

    protected static function updateStatus($rumah, $status)
    {
        if ($rumah->status !== $status) {
            $rumah->update(['status' => $status]);
        }

        return true;
    }
}
