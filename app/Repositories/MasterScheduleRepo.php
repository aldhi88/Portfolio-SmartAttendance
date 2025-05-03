<?php

namespace App\Repositories;

use App\Models\MasterSchedule;
use App\Models\RelDataEmployeeMasterSchedule;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use Illuminate\Support\Facades\Log;

class MasterScheduleRepo implements MasterScheduleFace
{
    protected $relDataEmployeeMasterSchedule;

    public function __construct(RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterSchedule)
    {
        $this->relDataEmployeeMasterSchedule = $relDataEmployeeMasterSchedule;
    }

    public function getHariIndo()
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    }

    public function getByKey($id)
    {
        return MasterSchedule::find($id);
    }

    public function create($data)
    {
        try {
            MasterSchedule::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDT($data)
    {
        return MasterSchedule::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if(!$this->relDataEmployeeMasterSchedule->isExistByCol('master_schedule_id', $id)){
            try {
                MasterSchedule::find($id)->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Delete master_schedules failed", ['error' => $e->getMessage()]);
                return false;
            }
        }

        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $usedIds = $this->relDataEmployeeMasterSchedule->getMultiByCol('master_schedule_id', $ids);
            $allowDeleteId = array_diff($ids, $usedIds);
            if (!empty($allowDeleteId)) {
                MasterSchedule::whereIn('id', $allowDeleteId)->delete();
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id,$data)
    {
        try {
            MasterSchedule::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update master_schedules failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
