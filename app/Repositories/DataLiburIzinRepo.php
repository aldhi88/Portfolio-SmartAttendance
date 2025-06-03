<?php

namespace App\Repositories;

use App\Models\DataIzin;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataLiburIzinRepo implements DataLiburIzinFace
{
    public function create($data)
    {
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
            ->with([
                'data_employees',
                'data_employee_admins',
            ])
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

}
