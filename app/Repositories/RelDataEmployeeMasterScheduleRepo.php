<?php

namespace App\Repositories;

use App\Models\RelDataEmployeeMasterSchedule;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;

class RelDataEmployeeMasterScheduleRepo implements RelDataEmployeeMasterScheduleFace
{
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
