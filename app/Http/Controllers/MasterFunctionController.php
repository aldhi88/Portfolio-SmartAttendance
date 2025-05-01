<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterFunctionFace;
use DataTables;

class MasterFunctionController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Fungsi | ".config('app.name');
        $data['page_title'] = "Data Fungsi";
        $data['page_desc'] = "Manajemen data fungsi";
        $data['lw'] = "functions.function-data";
        return view('index', compact('data'));
    }

    public function indexDT(MasterFunctionFace $masterFunctionRepo)
    {
        $data = $masterFunctionRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
