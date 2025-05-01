<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterPositionFace;
use DataTables;

class MasterPositionController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Jabatan | ".config('app.name');
        $data['page_title'] = "Data Jabatan";
        $data['page_desc'] = "Manajemen data jabatan";
        $data['lw'] = "position.position-data";
        return view('index', compact('data'));
    }

    public function indexDT(MasterPositionFace $masterPositionRepo)
    {
        $data = $masterPositionRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
