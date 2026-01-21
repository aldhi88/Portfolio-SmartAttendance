<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataEmployeeFace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataEmployeeRepo implements DataEmployeeFace
{

    public static function getManager()
    {
        return DataEmployee::query()
            ->where('master_position_id', 14)
            ->with('master_positions')
            ->first()
            ->toArray();
    }

    public function apiGetById($request)
    {
        $dt = DataEmployee::query()
            ->with([
                'master_schedules',
                'data_lemburs' => function ($q) use ($request) {
                    $q->select('*')
                        ->where('status', 'Disetujui')
                        ->where('tanggal', $request->date);
                },
                'data_izins' => function ($q) use ($request) {
                    $q->select('*')
                        ->where('status', 'Disetujui')
                        ->whereDate('from', '<=', $request->date)
                        ->whereDate('to', '>=', $request->date);
                },
            ])
            ->find($request->user_id)
            ->toArray();
        return $dt;
    }


    public function pengawasCheck($id)
    {

        return DataEmployee::find($id)?->pengawas->first()->id ?? NULL;
    }

    public function delMember($data)
    {
        try {
            DataEmployee::where('id', $data['pengawas'])->first()?->member()->detach($data['member']);
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data rel_pengawas_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
    public function addMember($data)
    {
        try {
            DataEmployee::where('id', $data['pengawas'])->first()?->member()->attach($data['member']);
            return true;
        } catch (\Exception $e) {
            Log::error("Create ke  rel_pengawas_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getPengawas()
    {
        return DataEmployee::select('id', 'name')
            ->whereHas('user_logins.user_roles', function ($q) {
                $q->select('id', 'name')
                    ->whereIn('id', [200, 400]);
            })
            ->get()
            ->toArray();
    }
    public function getNonMember($data)
    {
        return DataEmployee::whereDoesntHave('pengawas', function ($q) use ($data) {
            $q->where('pengawas_id', $data['id']);
        })
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
            ])
        ;
    }

    public function getMember($data)
    {
        return DataEmployee::whereHas('pengawas', function ($q) use ($data) {
            $q->where('pengawas_id', $data['id']);
        })
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
            ])
        ;
    }

    public function setStatusMultiple($data, $status)
    {
        try {
            DataEmployee::whereIn('id', $data)
                ->where('status', '!=', $status)
                ->whereNotNull('user_login_id')
                ->whereHas('master_schedules')
                ->update(['status' => $status]);
            return true;
        } catch (\Exception $e) {
            Log::error("Set status multiple data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function searchByName($name)
    {

        if (Auth::user()->is_pengawas) {
            $pengawasId = Auth::user()->data_employees->id;
            return DataEmployee::select('id', 'name','master_organization_id')
                ->where('name', 'like', '%' . $name . '%')
                ->where(function ($q) use ($pengawasId) {
                    $q->whereHas('pengawas', function ($q2) use ($pengawasId) {
                        $q2->where('pengawas_id', $pengawasId);
                    });
                })
                ->limit(10)
                ->get()
                ->toArray();
        }

        return DataEmployee::select('id', 'name','master_organization_id')
            ->where('name', 'like', '%' . $name . '%')
            ->limit(10)
            ->get()
            ->toArray();
    }
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
        // dd($id);
        try {
            DataEmployee::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
    public function deleteMulti($id)
    {
        // dd($id);
        try {
            DataEmployee::whereIn('id', $id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_employees failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByKey($id)
    {
        return DataEmployee::where('id', $id)
            ->with([
                'master_schedules',
                'master_schedules.data_schedule_bebas',
                'user_logins.user_roles',
            ])
            ->first()
        ;
    }

    public function getColValByCol($col, $val, $get)
    {
        return DataEmployee::where($col, $val)->value($get);
    }
    public function getColValByColMulti($col, $val, $get)
    {
        return DataEmployee::whereIn($col, $val)->pluck($get)->toArray();
    }

    public function getDTKaryawan($data)
    {
        return DataEmployee::query()
            ->select('data_employees.*')
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
                'user_logins.user_roles',
                'master_schedules'
            ])
        ;
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
                'log_attendances',
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

    public function getReportDashboardDT($data)
    {
        return DataEmployee::query()
            ->with([
                'master_organizations:id,name',
                // 'master_locations:id,name',
                // 'master_functions:id,name',
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

    public function getPengawasLembur()
    {
        $kodePengawas = [200, 400, 500];
        return DataEmployee::select('id', 'name')
            ->whereHas('user_logins.user_roles', function ($q) use ($kodePengawas) {
                $q->select('id', 'name')
                    ->whereIn('id', $kodePengawas);
            })
            ->get()
            ->toArray();
    }
    public function getSecurityLembur()
    {
        $kodeSecurity = [100, 101];
        return DataEmployee::query()
            ->select('id', 'name')
            ->whereIn('master_position_id', $kodeSecurity)
            ->get()
            ->toArray();
    }
}
