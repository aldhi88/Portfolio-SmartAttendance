<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Lokasi | ".config('app.name');
        $data['page_title'] = "Data Lokasi";
        $data['page_desc'] = "Manajemen data location.";
        $data['lw'] = "location.location-data";
        return view('location.index', compact('data'));
    }
}
