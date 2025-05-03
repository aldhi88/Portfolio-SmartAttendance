<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Models\MasterOrganization;
use App\Repositories\Interfaces\DataEmployeeFace;
use Illuminate\Support\Facades\Log;

class DataEmployeeRepo implements DataEmployeeFace
{
    public function isExistByCol($col, $val)
    {
        return DataEmployee::where($col, $val)->exists();
    }

    public function getMultiByCol($col, $val)
    {
        return DataEmployee::whereIn($col, $val)
                    ->pluck($col)
                    ->toArray();
    }


}
