<?php

namespace App\Repositories;

use App\Models\DataIzin;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataLiburIzinRepo implements DataLiburIzinFace
{
    public function update($data)
    {

        try {
            // dd($data);
            if (isset($data['form']['bukti'])) {
                // dd(1);
                $file = $data['form']['bukti'];
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('bukti', $uniqueName, 'public');
                $data['form']['bukti'] = $uniqueName;
                if(!is_null($data['old_file'])){
                    Storage::disk('public')->delete('bukti/' . $data['old_file']);
                }
            }
            // dd($data);
            DataIzin::find($data['id'])->update($data['form']);
            return true;
        } catch (\Exception $e) {
            // dd(0);
            Log::error("Insert data_izins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function create($data)
    {
        // dd($data);
        try {
            if (isset($data['bukti'])) {
                $file = $data['bukti'];
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('bukti', $uniqueName, 'public');
                $data['bukti'] = $uniqueName;
            }

            DataIzin::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_izins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getDataIzinDT($data)
    {
        return DataIzin::query()
            ->select('data_izins.*')
            ->with([
                'data_employees',
                'data_employee_admins',
            ])
            ->orderBy('from', 'DESC')
        ;
    }

    public function getDataIzinByPengawasDT($data)
    {
        return DataIzin::query()
            ->select('data_izins.*')
            ->whereHas('data_employees.pengawas', function ($q) {
                $q->where('pengawas_id', Auth::user()->data_employees->id);
            })
            ->with([
                'data_employees',
                'data_employee_admins',
            ])
            ->orderBy('from', 'DESC')
        ;
    }

    public function getByCol($col, $val)
    {
        return DataIzin::query()
            ->where($col, $val)
            ->with(['data_employees'])
            ->first()
            ->toArray()
        ;
    }

    public function process($id, $data)
    {
        try {
            DataIzin::find($id)->update($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Update data_izins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $fileName = DataIzin::where('id',$id)->value('bukti');
            if($fileName){
                Storage::disk('public')->delete('bukti/' . $fileName);
            }
            DataIzin::find($id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_izins failed", ['error' => $e->getMessage()]);
            return false;
        }
        return false;
    }

    public function deleteMultiple($ids)
    {
        try {
            $fileNames = DataIzin::whereIn('id', $ids)
                ->pluck('bukti')
                ->filter() // otomatis buang null dan kosong
                ->values()
                ->map(fn($file) => 'bukti/' . $file)
                ->toArray();

            if(count($fileNames)>0){
                Storage::disk('public')->delete($fileNames);
            }

            DataIzin::whereIn('id', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete multiple data_izins failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getIzinList()
    {
        return DataIzin::izinList();
    }

}
