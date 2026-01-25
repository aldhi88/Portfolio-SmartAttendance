<?php

namespace App\Http\Controllers;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburFace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Dashboard | " . config('app.name');
        $data['page_title'] = "Dashboard";
        $data['page_desc'] = "Quick summary data.";
        $data['lw'] = "dashboard.insight";

        return view('dashboard.index', compact('data'));
    }

    public function getSummaryRank(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
    ) {
        $data = $dataEmployeeRepo->getReportDashboardDT(0);

        $start = Carbon::create(date('Y'), date('m'), 1)->startOfMonth()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');


        $data->select([
            'data_employees.id',
            'data_employees.name',
            'data_employees.status',
            'data_employees.master_organization_id',
            'data_employees.master_position_id',
            'data_employees.master_location_id',
            'data_employees.master_function_id',
        ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->with([
                'master_schedules:id,type,kode,day_work',
                'log_attendances' => function ($q) {
                    $q->select('data_employee_id', 'time')
                        ->whereYear('time', date('Y'))
                        ->whereMonth('time', date('m'));
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
                'data_attendance_claims' => function ($q) use ($start, $end) {
                    $q->where('type', 'Normal')
                        ->whereBetween('absen_date', [$start, $end])
                    ;
                },
            ])
        ;

        $dateInMonth = PublicHelper::dateInMonth($start, $end);
        $tglMerah = $dataLiburRepo->getByDate(date('m'), date('Y'));

        $results = $data->get()->map(function ($item) use ($dateInMonth, $tglMerah) {
            $item->akumulasi = PublicHelper::getAkumulasi(
                $dateInMonth,
                $item->log_attendances->toArray(),
                $item->master_schedules->toArray(),
                $item->data_izins->toArray(),
                $item->data_lemburs->toArray(),
                $item->data_attendance_claims->toArray(),
                $tglMerah
            );

            unset(
                $item->log_attendances,
                $item->data_izins,
                $item->master_schedules,
            );
            return $item;
        });

        $param['jlh_karyawan'] = count($results);

        return response()->json([
            'status' => 'success',
            'data' => $results,
            'param' => $param,
        ]);
    }

    public function getSummaryAttd(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
    ) {
        $data = $dataEmployeeRepo->getReportDashboardDT(0);
        $data->select([
            'data_employees.id',
            'data_employees.name',
            'data_employees.status',
            'data_employees.master_organization_id',
            'data_employees.master_position_id',
            'data_employees.master_location_id',
            'data_employees.master_function_id',
        ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->with([
                'master_schedules:id,type,kode,day_work',
                'log_attendances' => function ($q) {
                    $q->select('data_employee_id', 'time')
                        ->whereDate('time', date('Y-m-d'));
                    // ->whereYear('time', date('Y'))
                    // ->whereMonth('time', date('m'));
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
                'data_attendance_claims' => function ($q) {
                    $q->where('type', 'Normal')
                        ->where('absen_date', date('Y-m-d'))
                    ;
                },
            ])
        ;

        $dateInMonth = PublicHelper::dateInMonth(date('Y-m-d'), date('Y-m-d'));
        $tglMerah = $dataLiburRepo->getByDate(date('m'), date('Y'));

        $results = $data->get()->map(function ($item) use ($dateInMonth, $tglMerah) {
            $item->akumulasi = PublicHelper::getAkumulasi(
                $dateInMonth,
                $item->log_attendances->toArray(),
                $item->master_schedules->toArray(),
                $item->data_izins->toArray(),
                $item->data_lemburs->toArray(),
                $item->data_attendance_claims->toArray(),
                $tglMerah
            );

            unset(
                $item->log_attendances,
                $item->data_izins,
                $item->master_schedules,
            );
            return $item;
        });

        $param['jlh_karyawan'] = count($results);

        // dd($param, $results->toArray());

        return response()->json([
            'status' => 'success',
            'data' => $results,
            'param' => $param,
        ]);
    }

    public function getMonthlyLateSummary(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
    ) {
        $now = now();

        $year  = $now->year;
        $month = $now->month;

        $startDate = $now->copy()->startOfMonth()->startOfDay();
        $endDate   = $now->copy()->endOfDay();

        $startDateIzin = $now->copy()->startOfMonth()->startOfDay();
        $endDateIzin   = $now->copy()->endOfDay();

        $employees = $dataEmployeeRepo->getReportDashboardDT(0)
            ->select([
                'data_employees.id',
                'data_employees.name',
                'data_employees.status',
            ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->with([
                'master_schedules:id,type,kode,day_work',
                'log_attendances' => function ($q) use ($year, $month) {
                    $q->select('data_employee_id', 'time')
                        ->whereYear('time', $year)
                        ->whereMonth('time', $month);
                },
                'data_izins' => function ($q) use ($startDateIzin, $endDateIzin) {
                    $q->where('status', 'Disetujui')
                        ->where(function ($qq) use ($startDateIzin, $endDateIzin) {
                            $qq->whereDate('from', '<=', $endDateIzin)
                                ->whereDate('to', '>=', $startDateIzin);
                        });
                },
                'data_lemburs',
                'data_attendance_claims' => function ($q) use ($startDateIzin, $endDateIzin) {
                    $q->where('type', 'Normal')
                        ->whereBetween('absen_date', [
                            $startDateIzin->toDateString(),
                            $endDateIzin->toDateString()
                        ])
                    ;
                },
            ])
            ->get();

        $tglMerah = $dataLiburRepo->getByDate($month, $year);

        $results = $employees->map(function ($emp) use ($startDate, $endDate, $tglMerah) {
            // dd($emp->toArray());

            $totalTerlambat = 0;
            $totalAlpa = 0;

            $dates = PublicHelper::dateInMonth(
                $startDate->toDateString(),
                $endDate->toDateString()
            );

            foreach ($dates as $date) {

                $logHariIni = collect($emp->log_attendances)
                    ->filter(
                        fn($l) =>
                        date('Y-m-d', strtotime($l['time'])) === $date
                    )
                    ->values()
                    ->toArray();

                $akumulasiHarian = PublicHelper::getAkumulasi(
                    [$date],
                    $logHariIni,
                    $emp->master_schedules->toArray(),
                    $emp->data_izins->toArray(),
                    $emp->data_lemburs->toArray(),
                    $emp->data_attendance_claims->toArray(),
                    $tglMerah
                );

                $totalTerlambat += $akumulasiHarian['terlambat'] ?? 0;
                $totalAlpa      += $akumulasiHarian['alpa'] ?? 0;
            }

            return [
                'id' => $emp->id,
                'name' => $emp->name,
                'total_terlambat' => $totalTerlambat,
                'total_alpa'      => $totalAlpa,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);
    }
}
