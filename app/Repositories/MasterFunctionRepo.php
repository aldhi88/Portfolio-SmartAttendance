<?php

namespace App\Repositories;

use App\Models\MasterFunction;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use Illuminate\Support\Facades\Log;

class MasterFunctionRepo implements MasterFunctionFace
{
    protected $dataEmployee;

    public function __construct(DataEmployeeFace $dataEmployee)
    {
        $this->dataEmployee = $dataEmployee;
    }

    public function getByKey($id)
    {
        return MasterFunction::find($id);
    }

    public function create($data)
    {
        try {
            if(MasterFunction::withTrashed()->where('name', $data['name'])->exists()){
                MasterFunction::where('name', $data['name'])->restore();
            }else{
                MasterFunction::create($data);
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Insert master_functions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDT($data)
    {
        return MasterFunction::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if(!$this->dataEmployee->isExistByCol('master_function_id', $id)){
            try {
                MasterFunction::find($id)->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Delete master_functions failed", ['error' => $e->getMessage()]);
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
                if (!$this->dataEmployee->isExistByCol('master_function_id', $id)) {
                    $allowDeleteId[] = $id;
                }
            }
            if (!empty($allowDeleteId)) {
                MasterFunction::whereIn('id', $allowDeleteId)->delete();
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple master_functions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id,$data)
    {
        try {
            MasterFunction::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update master_functions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
