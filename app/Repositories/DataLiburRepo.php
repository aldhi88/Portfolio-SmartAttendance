<?php

namespace App\Repositories;

use App\Models\DataLibur;
use App\Repositories\Interfaces\DataLiburFace;
use Illuminate\Support\Facades\Log;

class DataLiburRepo implements DataLiburFace
{

    public function getByDate($month, $year)
    {
        $query = DataLibur::select('date')
            ->whereYear('date', $year);
        if(!is_null($month)){
            $query->whereMonth('date', $month);
        }
        return $query->pluck('date')->toArray();
    }

    public function insert($data)
    {
        try {
            DataLibur::create($data);
            return true;
        } catch (\Exception $e) {
            Log::error("Insert data_liburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function remove($tgl)
    {
        try {
            DataLibur::where('date', $tgl)->forceDelete();
            return true;
        } catch (\Exception $e) {
            Log::error("Delete data_liburs failed", ['error' => $e->getMessage()]);
            return false;
        }
    }

}
