<?php

namespace App\Repositories;

use App\Models\DataSchedulesBebas;
use App\Repositories\Interfaces\DataScheduleBebasFace;
use Illuminate\Support\Facades\Log;

class DataScheduleBebasRepo implements DataScheduleBebasFace
{
    protected $relDataEmployeeMasterSchedule;


    public function bulkCreate($data): bool
    {
        // dd($data);
        $data = collect($data)->map(function ($row) {
            if (isset($row['day_work']) && is_array($row['day_work'])) {
                $row['day_work'] = json_encode($row['day_work']);
            }
            $row['updated_at'] = now();
            $row['created_at'] = $row['created_at'] ?? now();
            return $row;
        });

        // Ambil semua master_schedule_id dari data baru
        $allMasterIds = $data->pluck('master_schedule_id')->unique()->values();
        $masterScheduleId = $data->first()['master_schedule_id'] ?? null;

        if (!$masterScheduleId) {
            Log::error("bulkCreate gagal: master_schedule_id tidak ditemukan");
            return false;
        }

        try {
            // Hapus semua schedule ID yang sudah tidak dikirim sama sekali
            DataSchedulesBebas::whereNotIn('master_schedule_id', $allMasterIds)->forceDelete();

            // Hapus tanggal yang sudah tidak ada di data baru untuk ID saat ini
            DataSchedulesBebas::where('master_schedule_id', $masterScheduleId)
                ->whereNotIn('tanggal', $data->pluck('tanggal'))
                ->forceDelete();

            // Insert atau update sisanya
            DataSchedulesBebas::upsert(
                $data->toArray(),
                ['master_schedule_id', 'tanggal'],
                ['day_work', 'updated_at']
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Upsert data_schedule_bebas failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
