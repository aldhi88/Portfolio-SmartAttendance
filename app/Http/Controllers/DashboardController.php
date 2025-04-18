<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Dashboard | ".config('app.name');
        $data['page_title'] = "Dashboard";
        $data['page_desc'] = "Quick summary data.";
        $data['lw'] = "dashboard.insight";

        return view('dashboard.index', compact('data'));
    }
}
