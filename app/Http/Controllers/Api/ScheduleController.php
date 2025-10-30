<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PublicHelper;
use App\Http\Controllers\Controller;
use App\Models\DataEmployee;
use App\Models\MasterSchedule;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburFace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getById(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
        Request $request
    ) {

        $start = null;
        $end = null;
        $response = [
            'checkin_time' => $start,
            'checkout_deadline_time' => $end
        ];

        $tglMerah = $dataLiburRepo->isDateLibur($request->date);
        if ($tglMerah) {
            return response()->json($response, 201);
        }

        $dt = $dataEmployeeRepo->apiGetById($request);
        // dd($dt);

        $listJadwal = collect($dt['master_schedules']);
        $tglCekCarbon = Carbon::parse($request->date);
        $jadwalAktif = PublicHelper::getJadwalAktifByDate($listJadwal, $tglCekCarbon);
        if (!$jadwalAktif) {
            return response()->json($response, 201);
        }

        // cek izin
        if (count($dt['data_izins']) > 0) {
            $timeRule = PublicHelper::getTimeRuleTetap($jadwalAktif['day_work']['time'], $tglCekCarbon);
            $dtIzin = PublicHelper::checkIzin($dt['data_izins'], $timeRule, []);
            // jika masuk dan keluar ternyata izin, maka jangan direkam gps
            if ($dtIzin['status']['kenaIzinMasuk'] && $dtIzin['status']['kenaIzinKeluar']) {
                return response()->json($response, 201);
            }
        }

        // jika jadwal Tetap
        if ($jadwalAktif['type'] == 'Tetap') {
            $start = $request->date . ' ' . $jadwalAktif['day_work']['time']['checkin_time'];
            $end = $request->date . ' ' . $jadwalAktif['day_work']['time']['checkout_deadline_time'];

            if (count($dt['data_lemburs']) > 0) {
                $dtLembur = [
                    "checkin_time" => $dt['data_lemburs'][0]['checkin_time_lembur'],
                    "work_time" => $dt['data_lemburs'][0]['work_time_lembur'],
                    "checkin_deadline_time" => $dt['data_lemburs'][0]['checkin_deadline_time_lembur'],
                    "checkout_time" => $dt['data_lemburs'][0]['checkout_time_lembur'],
                    "checkout_deadline_time" => $dt['data_lemburs'][0]['checkout_deadline_time_lembur'],
                ];

                $timeRuleLembur = PublicHelper::getTimeRuleTetap($dtLembur, $tglCekCarbon);

                $start = Carbon::parse($start);
                $end = Carbon::parse($end);

                foreach ($timeRuleLembur as $key => $waktu) {
                    if ($waktu->lt($start)) {
                        $start = $waktu; // ganti jika lebih awal
                    }
                    if ($waktu->gt($end)) {
                        $end = $waktu;   // ganti jika lebih akhir
                    }
                }

                $start = $start->format('Y-m-d H:i:s');
                $end = $end->format('Y-m-d H:i:s');
            }
        }

        // jika jadwal Rotasi dan Hybrid
        if ($listJadwal['type'] == 'Rotasi' && $listJadwal['type'] == 'Hybrid') {
            $startDate = Carbon::parse($jadwalAktif['day_work']['start_date'])->startOfDay();
            $workDay = (int) $jadwalAktif['day_work']['work_day'];
            $offDay = (int) $jadwalAktif['day_work']['off_day'];
            $totalShift = count($jadwalAktif['day_work']['time']);
            $diff = $startDate->diffInDays($tglCekCarbon);
            $jumlahHariSiklus = ($workDay + $offDay) * $totalShift;
            $hariKeBrpDalamSiklus = $diff % $jumlahHariSiklus;
            $shiftIndex = intdiv($hariKeBrpDalamSiklus, ($workDay + $offDay));
            if ($shiftIndex >= 0) {
            }
        }

        if (count($dt['data_lemburs']) > 0) {
        }

        return response()->json($response, 201);
    }
}
