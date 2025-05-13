<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function absen()
    {
        $data['tab_title'] = "Rekap Absensi | ".config('app.name');
        $data['page_title'] = "Rekap Absensi";
        $data['page_desc'] = "Menamplikan rekap absensi.";
        $data['lw'] = "report.report-absen";
        return view('index', compact('data'));
    }
}
