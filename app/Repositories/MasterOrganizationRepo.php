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

    public static function getOrgLemburBulanan($param)
    {
        return MasterOrganization::query()
            ->withCount([
                'data_employees as data_lembur_employee_count' => function ($q) use($param) {
                    $q->whereHas('data_lemburs', function ($l) use($param) {
                        $l->whereNotNull('status_pengawas1')
                            ->where(function ($w) {
                                $w->whereNull('pengawas2')
                                    ->orWhereNotNull('status_pengawas2');
                            })
                            ->whereMonth('tanggal', $param['month'])
                            ->whereYear('tanggal', $param['year']);
                            ;
                    });
                }
            ]);
    }

    public static function allOrg()
    {
        return MasterOrganization::all();
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

    public function getAll()
    {
        return MasterOrganization::all();
    }

    public function getDT($data)
    {
        return MasterOrganization::query()
            ->with(['data_employees'])
        ;
    }

    public function delete($id)
    {
        if (!$this->dataEmployee->isExistByCol('master_organization_id', $id)) {
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

    public function update($id, $data)
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
