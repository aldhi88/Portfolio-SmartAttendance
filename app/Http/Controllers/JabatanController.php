<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Jabatan | ".config('app.name');
        $data['page_title'] = "Data Jabatan";
        $data['page_desc'] = "Manajemen data jabatan.";
        $data['lw'] = "jabatan.jabatan-data";
        return view('jabatan.index', compact('data'));
    }
}
