<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Models\DataIzin;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataEmployeeFace;
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
        $orgId = DataEmployee::where('id', $data['data_employee_id'])
            ->value('master_organization_id');

        $year = now()->year;
        $format = DataLembur::formatOrg($orgId);

        // 🎯 tentukan LIKE pattern & posisi sequence
        if (in_array($format, ['format_ptc', 'format_ptc_security'])) {
            $like = "%/PND448000/{$year}-S8";
            $seqFromFront = true;
        } elseif ($format === 'format_patra_niaga') {
            $like = "%/PND448000/IV/{$year}-SO";
            $seqFromFront = true;
        } else {
            $like = "{$year}-%";
            $seqFromFront = false;
        }

        // 🔍 ambil nomor terakhir
        $lastNomor = DataLembur::withTrashed()
            ->where('nomor', 'like', $like)
            ->orderBy('nomor', 'desc')
            ->value('nomor');

        // 🔢 ambil sequence lama
        if ($lastNomor) {
            if ($seqFromFront) {
                $lastSeq = (int) explode('/', $lastNomor)[0];
            } else {
                $lastSeq = (int) explode('-', $lastNomor)[1];
            }
        } else {
            $lastSeq = 0;
        }

        $seq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);

        // 🧾 bentuk nomor baru
        if (in_array($format, ['format_ptc', 'format_ptc_security'])) {
            $nomor = "{$seq}/PND448000/{$year}-S8";
        } elseif ($format === 'format_patra_niaga') {
            $nomor = "{$seq}/PND448000/IV/{$year}-SO";
        } else {
            $nomor = "{$year}-{$seq}";
        }

        $data['nomor'] = $nomor;

        try {
            DataLembur::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Insert data_lemburs failed', ['error' => $e->getMessage()]);
            return false;
        }
    }



    public function getDataDT($data)
    {
        return DataLembur::query()
            ->with([
                'data_employees',
                'data_employees.master_organizations:id,name',
                'pengawas1:id,name',
                'pengawas2:id,name',
                'security:id,name',
                'data_employee_admins',
            ])
        ;
    }

    public function getDataByPengawas($data)
    {
        $employeeId = Auth::user()->data_employees->id;
        return DataLembur::query()
            ->where(function ($q) use ($employeeId) {
                $q->where('pengawas1', $employeeId)
                    ->orWhere('pengawas2', $employeeId);
            })
            ->with([
                'data_employees',
                'data_employees.master_organizations:id,name',
                'pengawas1:id,name',
                'pengawas2:id,name',
                'security:id,name',
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
