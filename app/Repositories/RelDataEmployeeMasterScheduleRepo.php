<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Models\RelDataEmployeeMasterSchedule;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use Illuminate\Support\Facades\Log;

class RelDataEmployeeMasterScheduleRepo implements RelDataEmployeeMasterScheduleFace
{

    public function insert($data)
    {
        // dd($data);
        try {
            RelDataEmployeeMasterSchedule::insert($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert rel_data_employee_master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
    public function update($employeeId, $data)
    {
        try {
            $syncData = [];
            foreach ($data as $item) {
                $scheduleId = $item['master_schedule_id'];
                $syncData[$scheduleId] = [
                    'effective_at' => $item['effective_at'],
                    'expired_at'   => $item['expired_at']!=""?$item['expired_at']:null,
                ];
            }

            // dd($syncData);

            DataEmployee::findOrFail($employeeId)
                ->master_schedules()
                ->sync($syncData);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert rel_data_employee_master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function delByCol($col, $val)
    {
        // dd($col, $val);
        try {
            RelDataEmployeeMasterSchedule::where($col, $val)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rel_data_employee_master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
    public function delByColMulti($col, $val)
    {
        // dd($col, $val);
        try {
            RelDataEmployeeMasterSchedule::whereIn($col, $val)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete rel_data_employee_master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function isExistByCol($col, $val)
    {
        return RelDataEmployeeMasterSchedule::where($col, $val)->exists();
    }

    public function getMultiByCol($col, $val)
    {
        return RelDataEmployeeMasterSchedule::whereIn($col, $val)
                    ->pluck($col)
                    ->toArray();
    }


}
