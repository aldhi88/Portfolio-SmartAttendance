<?php

namespace App\Http\Controllers;

use App\Models\DataLov;
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
        // Ambil data area dari DataLov
        $areas = DataLov::where('key', 'koordinat')->first()?->getAttribute('value');

        // Fungsi Haversine untuk hitung jarak dalam meter
        $haversine = function ($lat1, $lon1, $lat2, $lon2) {
            $R = 6371000; // radius bumi (meter)
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $R * $c;
        };

        // Query utama
        $data = LogGps::query()
            ->select('log_gps.*')
            ->with(['data_employees']);

        return DataTables::of($data)
            ->addColumn('area_name', function ($data) use ($areas, $haversine) {
                $lat = floatval($data->latitude);
                $lng = floatval($data->longitude);
                $foundArea = '-';

                foreach ($areas as $area) {
                    $distance = $haversine($lat, $lng, floatval($area['lat']), floatval($area['lng']));
                    if ($distance <= $area['radius']) {
                        $foundArea = $area['name'] . ' (' . round($distance, 1) . ' m)';
                        break;
                    }
                }

                return $foundArea;
            })

            ->addColumn('action', function ($data) {
                $return = '
                <div class="btn-group">
                    <a href="javascript:void(0)" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="mdi mdi-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#"><i class="fas fa-unlock-alt fa-fw"></i> Reset Password</a>
                    </div>
                </div>
            ';
                return $return;
            })

            ->rawColumns(['action'])
            ->toJson();
    }
}
