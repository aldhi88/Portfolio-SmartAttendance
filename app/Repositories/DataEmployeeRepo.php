<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataEmployeeFace;
use Illuminate\Support\Facades\Log;

class DataEmployeeRepo implements DataEmployeeFace
{

    public function createForm($data)
    {
        // dd($data);
        try {
            DataEmployee::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function insertAPI($data)
    {
        DataEmployee::insert($data);
        return true;
    }

    public function update($id, $data)
    {
        try {
            DataEmployee::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function delete($id)
    {
        try {
            DataEmployee::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByKey($id)
    {
        return DataEmployee::where('id',$id)
            ->with([
                'master_schedules',
                'user_logins'
            ])
            ->first()
        ;
    }

    public function getColValByCol($col, $val, $get)
    {
        return DataEmployee::where($col, $val)->value($get);
    }

    public function getDT($data)
    {
        return DataEmployee::query()
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
                'master_schedules',
                'log_attendances'
            ])
        ;
    }

    public function getReportDT($data)
    {
        return DataEmployee::query()
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
            ])
        ;
    }

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
