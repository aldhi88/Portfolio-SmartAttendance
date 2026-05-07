<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpMasterAsetRepo;
use DataTables;

class MasterAsetController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Aset | RDP";
        $data['page_title'] = "Data Aset";
        $data['page_desc'] = "Semua data aset rumah dinas";
        $data['lw'] = "rdp.master-aset.master-aset-data";

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        $data = RdpMasterAsetRepo::getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
