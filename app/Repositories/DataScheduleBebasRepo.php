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
        $data = collect($data)->map(function ($row) {
            if (isset($row['day_work']) && is_array($row['day_work'])) {
                $row['day_work'] = json_encode($row['day_work']);
            }
            $row['updated_at'] = now();
            $row['created_at'] = $row['created_at'] ?? now();
            return $row;
        })->toArray();

        try {
            DataSchedulesBebas::upsert(
                $data,
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
