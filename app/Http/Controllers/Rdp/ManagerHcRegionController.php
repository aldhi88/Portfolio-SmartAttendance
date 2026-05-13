<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpManagerHcRegionRepo;
use DataTables;

class ManagerHcRegionController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Manager HC Region | RDP";
        $data['page_title'] = "Data Manager HC Region";
        $data['page_desc'] = "Manajemen akun Manager HC Region untuk approval final SIP.";
        $data['lw'] = "rdp.manager-hc-region.manager-hc-region-data";

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        return DataTables::of(RdpManagerHcRegionRepo::getDT())
            ->toJson();
    }
}
