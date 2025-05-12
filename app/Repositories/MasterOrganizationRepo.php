<?php

namespace App\Repositories;

use App\Models\MasterOrganization;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use Illuminate\Support\Facades\Log;

class MasterOrganizationRepo implements MasterOrganizationFace
{
    protected $dataEmployee;

    public function __construct(DataEmployeeFace $dataEmployee)
    {
        $this->dataEmployee = $dataEmployee;
    }

    public function getByKey($id)
    {
        return MasterOrganization::find($id);
    }

    public function create($data)
    {
        try {
            MasterOrganization::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert master_organizations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDT($data)
    {
        return MasterOrganization::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if(!$this->dataEmployee->isExistByCol('master_organization_id', $id)){
            try {
                MasterOrganization::find($id)->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Delete master_organizations failed", ['error' => $e->getMessage()]);
                return false;
            }
        }

        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $usedIds = $this->dataEmployee->getMultiByCol('master_organization_id', $ids);
            $allowDeleteId = array_diff($ids, $usedIds);
            if (!empty($allowDeleteId)) {
                MasterOrganization::whereIn('id', $allowDeleteId)->delete();
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple master_organizations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id,$data)
    {
        try {
            MasterOrganization::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update master_organizations failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
