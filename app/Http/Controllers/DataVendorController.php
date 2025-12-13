<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DataVendorFace;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataVendorController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Vendor | ".config('app.name');
        $data['page_title'] = "Data Vendor";
        $data['page_desc'] = "Manajemen data vendor.";
        $data['lw'] = "vendor.vendor-data";
        return view('index', compact('data'));
    }

    public function indexDT(DataVendorFace $dataVendorRepo)
    {
        $data = $dataVendorRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
