<?php

namespace App\Repositories;

use App\Models\DataIzin;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataLemburFace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataLemburRepo implements DataLemburFace
{
    public function searchPekerjaan($keyword)
    {
        return DataLembur::query()
            ->select('pekerjaan')
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', 'like', "%{$keyword}%")
            ->groupBy('pekerjaan')
            ->limit(10)
            ->pluck('pekerjaan')
            ->toArray();
    }

    public function update($data)
    {
        try {
            DataLembur::find($data['id'])->update($data['form']);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_lemburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function create($data)
    {
        try {
            DataLembur::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_leburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDataDT($data)
    {
        return DataLembur::query()
            ->with([
                'data_employees',
                'pengawas1:id,name',
                'pengawas2:id,name',
                'security:id,name',
                'data_employee_admins',
            ])
        ;
    }

    public function getDataByPengawas($data)
    {
        return DataLembur::query()
            ->whereHas('data_employees.pengawas', function ($q) {
                $q->where('pengawas_id', Auth::user()->data_employees->id);
            })
            ->with([
                'data_employees',
                'data_employee_admins',
            ])
        ;
    }

    public function getByCol($col, $val)
    {
        return DataLembur::query()
            ->where($col, $val)
            ->with(['data_employees'])
            ->first()
            ->toArray()
        ;
    }

    public function process($id, $data)
    {
        try {
            DataLembur::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update data_lemburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function delete($id)
    {
        try {
            DataLembur::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_lemburs failed", ['error' => $e->getMessage()]);
            return false;
        }
        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            DataLembur::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple data_lemburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
