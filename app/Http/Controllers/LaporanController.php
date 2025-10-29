<?php

namespace App\Http\Controllers;

use App\Models\LogAttendance;
use App\Models\LogGps;
use Illuminate\Http\Request;
use DataTables;

class LaporanController extends Controller
{
    public function indexLogAbsen()
    {
        $data['tab_title'] = "Log Absensi | " . config('app.name');
        $data['page_title'] = "Log Absensi";
        $data['page_desc'] = "Data absen dari mesin absensi.";
        $data['lw'] = "laporan.log-absen-data";
        return view('laporan.index', compact('data'));
    }

    public function indexLogAbsenDt()
    {
        $data = LogAttendance::query()
            ->select(
                "log_attendances.*",
            )
            ->with([
                'master_machines',
                'master_machines.master_locations',
                'master_minors',
            ]);

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $return = '
                <div class="btn-group">
                    <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu" style="">
                ';

                $return .= '
                    <a class="dropdown-item" href="#"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                ';

                return $return;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function indexLogGps()
    {
        $data['tab_title'] = "Log GPS | " . config('app.name');
        $data['page_title'] = "Log GPS";
        $data['page_desc'] = "Data gps dari mesin absensi.";
        $data['lw'] = "laporan.log-gps-data";
        return view('laporan.index', compact('data'));
    }

    public function indexLogGpsDt()
    {
        $data = LogGps::query()
            ->select(
                "log_gps.*",
            )
            ->with([
                'data_employees',
            ]);

        return DataTables::of($data)
            ->addColumn('google_map', function ($data) {
                return "<a href='https://www.google.com/maps?q={$data->latitude},{$data->longitude}' target='_blank' class='btn btn-sm btn-primary'>Lihat Map</a>";
            })
            ->addColumn('action', function ($data) {
                $return = '
                <div class="btn-group">
                    <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu" style="">
                ';

                $return .= '
                    <a class="dropdown-item" href="#"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                ';

                return $return;
            })
            ->rawColumns(['action', 'google_map'])
            ->toJson();
    }
}
