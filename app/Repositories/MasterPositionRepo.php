<?php

namespace App\Repositories;

use App\Models\MasterPosition;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterPositionFace;
use Illuminate\Support\Facades\Log;

class MasterPositionRepo implements MasterPositionFace
{
    protected $dataEmployee;

    public function __construct(DataEmployeeFace $dataEmployee)
    {
        $this->dataEmployee = $dataEmployee;
    }

    public function getByKey($id)
    {
        return MasterPosition::find($id);
    }

    public function create($data)
    {
        try {
            MasterPosition::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert master_positions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDT($data)
    {
        return MasterPosition::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if(!$this->dataEmployee->isExistByCol('master_position_id', $id)){
            try {
                MasterPosition::find($id)->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Delete master_positions failed", ['error' => $e->getMessage()]);
                return false;
            }
        }

        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $usedIds = $this->dataEmployee->getMultiByCol('master_position_id', $ids);
            $allowDeleteId = array_diff($ids, $usedIds);
            if (!empty($allowDeleteId)) {
                MasterPosition::whereIn('id', $allowDeleteId)->delete();
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple master_positions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id,$data)
    {
        try {
            MasterPosition::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update master_positions failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
