<?php

namespace App\Repositories;

use App\Models\MasterLocation;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterLocationFace;
use Illuminate\Support\Facades\Log;

class MasterLocationRepo implements MasterLocationFace
{
    protected $dataEmployee;

    public function __construct(DataEmployeeFace $dataEmployee)
    {
        $this->dataEmployee = $dataEmployee;
    }

    public function getByKey($id)
    {
        return MasterLocation::find($id);
    }

    public function create($data)
    {
        try {
            if(MasterLocation::withTrashed()->where('name', $data['name'])->exists()){
                MasterLocation::where('name', $data['name'])->restore();
            }else{
                MasterLocation::create($data);
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Insert master_locations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDT($data)
    {
        return MasterLocation::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if(!$this->dataEmployee->isExistByCol('master_location_id', $id)){
            try {
                MasterLocation::find($id)->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Delete master_locations failed", ['error' => $e->getMessage()]);
                return false;
            }
        }

        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $allowDeleteId = [];
            foreach ($ids as $id) {
                if (!$this->dataEmployee->isExistByCol('master_location_id', $id)) {
                    $allowDeleteId[] = $id;
                }
            }
            if (!empty($allowDeleteId)) {
                MasterLocation::whereIn('id', $allowDeleteId)->delete();
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple master_locations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id,$data)
    {
        try {
            MasterLocation::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update master_locations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
