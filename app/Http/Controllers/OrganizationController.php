<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Perusahaan | ".config('app.name');
        $data['page_title'] = "Data Perusahaan";
        $data['page_desc'] = "Manajemen data organization.";
        $data['lw'] = "organization.organization-data";
        return view('organization.index', compact('data'));
    }
    public function create()
    {
        $data['tab_title'] = "Buat Perusahaan Baru | ".config('app.name');
        $data['page_title'] = "Buat Perusahaan Baru";
        $data['page_desc'] = "Form pembuatan data perusahaan baru.";
        $data['lw'] = "organization.organization-create";
        return view('organization.index', compact('data'));
    }
}
