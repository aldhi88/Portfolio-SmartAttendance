<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterOrganizationFace;
use DataTables;

class MasterOrganizationController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Perusahaan | ".config('app.name');
        $data['page_title'] = "Data Perusahaan";
        $data['page_desc'] = "Manajemen data organization.";
        $data['lw'] = "organization.organization-data";
        return view('index', compact('data'));
    }

    public function indexDT(MasterOrganizationFace $masterOrganizationRepo)
    {
        $data = $masterOrganizationRepo->getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
