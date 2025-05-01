<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterLocationFace;
use DataTables;

class MasterLocationController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Lokasi | ".config('app.name');
        $data['page_title'] = "Data Lokasi";
        $data['page_desc'] = "Manajemen data lokasi";
        $data['lw'] = "location.location-data";
        return view('index', compact('data'));
    }

    public function indexDT(MasterLocationFace $masterLocationRepo)
    {
        $data = $masterLocationRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
