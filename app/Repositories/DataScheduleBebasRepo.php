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
        $dataUpdate = [];
        $dataInsert = [];
        $dataDelete = [];

        $payload = collect($data)->values();

        // (Opsional) buang duplikat key payload biar rapi
        $payload = $payload->unique(
            fn($r) =>
            $r['master_schedule_id'] . '|' . $r['data_employee_id'] . '|' . $r['tanggal']
        )->values();

        // 1) Ambil kandidat unik dari payload
        $masterIds = $payload->pluck('master_schedule_id')->unique()->values()->all();
        $empIds    = $payload->pluck('data_employee_id')->unique()->values()->all();
        $dates     = $payload->pluck('tanggal')->unique()->values()->all();

        // 2) Existing yang match dengan payload (untuk split insert/update)
        $existing = DataSchedulesBebas::query()
            ->whereIn('master_schedule_id', $masterIds)
            ->whereIn('data_employee_id', $empIds)
            ->whereIn('tanggal', $dates)
            ->get();

        $existingMap = $existing->keyBy(
            fn($m) =>
            $m->master_schedule_id . '|' . $m->data_employee_id . '|' . $m->tanggal
        );

        foreach ($payload as $value) {
            $key = $value['master_schedule_id'] . '|' . $value['data_employee_id'] . '|' . $value['tanggal'];

            if (isset($existingMap[$key])) {
                $dataUpdate[] = $value;
            } else {
                $dataInsert[] = $value;
            }
        }

        $groups = $payload->groupBy(fn($r) => $r['master_schedule_id'] . '|' . $r['data_employee_id']);
        foreach ($groups as $groupKey => $groupRows) {
            [$masterId, $empId] = explode('|', $groupKey);
            $tanggalList = $groupRows->pluck('tanggal')->unique()->values()->all();

            $ids = DataSchedulesBebas::query()
                ->where('master_schedule_id', $masterId)
                ->where('data_employee_id', $empId)
                ->whereNotIn('tanggal', $tanggalList)
                ->pluck('id')          // ⬅️ langsung ambil id saja
                ->toArray();

            // gabungkan jadi array flat: [1,2,3,...]
            $dataDelete = array_merge($dataDelete, $ids);
        }

        // === mapping seperti sebelumnya ===

        $dataInsert = collect($dataInsert)->map(function ($row) {
            $row['day_work'] = json_encode($row['day_work']);
            $row['updated_at'] = now();
            $row['created_at'] = $row['created_at'] ?? now();
            return $row;
        });

        $dataUpdate = collect($dataUpdate)->map(function ($row) {
            $row['day_work'] = json_encode($row['day_work']);
            unset($row['created_at'], $row['updated_at'], $row['deleted_at']);
            return $row;
        });

        // dd($dataInsert, $dataUpdate, $dataDelete);

        try {
            if (!empty($dataDelete)) {
                DataSchedulesBebas::whereIn('id', $dataDelete)->forceDelete();
            }

            foreach ($dataUpdate as $row) {
                DataSchedulesBebas::where('master_schedule_id', $row['master_schedule_id'])
                    ->where('data_employee_id', $row['data_employee_id'])
                    ->where('tanggal', $row['tanggal'])
                    ->update([
                        'day_work' => $row['day_work'],
                        'updated_at' => now(),
                    ]);;
            }

            if (!empty($dataInsert)) {
                DataSchedulesBebas::insert($dataInsert->toArray());
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Upsert data_schedule_bebas failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
