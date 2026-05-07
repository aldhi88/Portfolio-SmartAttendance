<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpMasterVendorRepo;
use DataTables;

class MasterVendorController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Vendor RDP | RDP";
        $data['page_title'] = "Data Vendor RDP";
        $data['page_desc'] = "Manajemen data vendor rumah dinas.";
        $data['lw'] = "rdp.master-vendor.master-vendor-data";

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        $data = RdpMasterVendorRepo::getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
