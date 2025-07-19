<?php

namespace App\Http\Controllers;

use App\Exports\ReportAbsenExport;
use App\Helpers\PublicHelper;
use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburFace;
use DataTables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function absen()
    {
        $data['tab_title'] = "Rekap Absensi | " . config('app.name');
        $data['page_title'] = "Rekap Absensi";
        $data['page_desc'] = "Menamplikan rekap absensi.";
        $data['lw'] = "report.report-absen";
        return view('index', compact('data'));
    }

    public function absenDT(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
        Request $request
    ) {
        $data = $dataEmployeeRepo->getReportDT(0);
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
        ;

        $range['start_cast'] = Carbon::parse($request->filter_start)->startOfDay();
        $range['end_cast'] = Carbon::parse($request->filter_end)->addDay()->endOfDay();

        $data->with([
            'master_schedules:id,type,kode,day_work',
            'log_attendances' => function ($q) use ($range) {
                $q->select('data_employee_id', 'time')
                    ->whereBetween('time', [
                        $range['start_cast'],
                        $range['end_cast']
                    ])
                ;
            },
            'data_izins' => function ($q) use ($range) {
                $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                    ->where('status', 'Disetujui')
                    ->where(function ($sub) use ($range) {
                        $sub->whereBetween('from', [$range['start_cast'], $range['end_cast']])
                            ->orWhereBetween('to', [$range['start_cast'], $range['end_cast']]);
                    });
            },
            'data_lemburs' => function ($q) use ($range) {
                $q->select('id', 'data_employee_id', 'tanggal')
                    ->where('status', 'Disetujui')
                    ->whereBetween('tanggal', [
                        $range['start_cast']->toDateString(),
                        $range['end_cast']->toDateString(),
                    ]);
            },
        ]);

        if ($request->filter_master_organization_id) {
            $data->where('master_organization_id', $request->filter_master_organization_id);
        }
        if ($request->filter_master_position_id) {
            $data->where('master_position_id', $request->filter_master_position_id);
        }

        $dateInMonth = PublicHelper::dateInMonth($request->filter_start, $request->filter_end);
        // dd($dateInMonth);

        $tglMerah = $dataLiburRepo->getByDate($request->filter_month, $request->filter_year);
        // dd($dataLibur);

        // dd($data->get()->toArray());

        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->addColumn('absensi', function ($data) use ($dateInMonth, $tglMerah) {
                $param['dateInMonth'] = $dateInMonth;
                $param['tglMerah'] = $tglMerah;
                $param['izin'] = $data->data_izins->toArray();
                $param['lembur'] = $data->data_lemburs->toArray();
                $param['log'] = $data->log_attendances->toArray();
                $param['jadwal'] = $data->master_schedules->toArray();
                return PublicHelper::getDtAbsen($param);
            })
            ->addColumn('akumulasi', function ($data) use ($dateInMonth, $tglMerah) {
                return PublicHelper::getAkumulasi(
                    $dateInMonth,
                    $data->log_attendances->toArray(),
                    $data->master_schedules->toArray(),
                    $data->data_izins->toArray(),
                    $data->data_lemburs->toArray(),
                    $tglMerah,
                );
            })
            ->smart(false)
            ->toJson()
        ;
    }

    public function rank()
    {
        $data['tab_title'] = "Rekap Passing Grade | " . config('app.name');
        $data['page_title'] = "Rekap Passing Grade";
        $data['page_desc'] = "Menamplikan rekap penilaian passing grade.";
        $data['lw'] = "report.report-rank";
        return view('index', compact('data'));
    }

    public function rankDT(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburFace $dataLiburRepo,
        Request $request
    ) {
        $data = $dataEmployeeRepo->getReportDT(0);
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
                'log_attendances' => function ($q) use ($request) {
                    $q->select('data_employee_id', 'time')
                        ->whereBetween('time', [
                            $request->filter_start,
                            Carbon::parse($request->filter_end)->addDay()->format('Y-m-d 23:59:59')
                        ])
                        // ->whereBetween('time', [$request->filter_start, Carbon::parse($request->filter_end)->addDay()->format('Y-m-d')])
                        // ->whereYear('time', $request->filter_year)
                        // ->whereMonth('time', $request->filter_month)
                    ;
                },
                'data_izins' => function ($q) use ($request) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
            ])
        ;

        if ($request->filter_master_organization_id) {
            $data->where('master_organization_id', $request->filter_master_organization_id);
        }
        if ($request->filter_master_position_id) {
            $data->where('master_position_id', $request->filter_master_position_id);
        }

        $dateInMonth = PublicHelper::dateInMonth($request->filter_start, $request->filter_end);
        // dd($dateInMonth);

        $tglMerah = $dataLiburRepo->getByDate($request->filter_month, $request->filter_year);
        // dd($dataLibur);

        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->addColumn('akumulasi', function ($data) use ($dateInMonth, $tglMerah) {
                return PublicHelper::getAkumulasi(
                    $dateInMonth,
                    $data->log_attendances->toArray(),
                    $data->master_schedules->toArray(),
                    $data->data_izins->toArray(),
                    $data->data_lemburs->toArray(),
                    $tglMerah,
                );
            })
            ->smart(false)
            ->toJson()
        ;
    }

    public function exportExcel(Request $request, DataLiburFace $dataLiburRepo)
    {
        $timestamp = now()->format('Hisv');
        $bulan = Carbon::create($request->filter_year, $request->filter_month)->locale('id')->translatedFormat('F');
        $tahun = $request->filter_year;
        $filename = 'report-absen-' . strtolower($bulan) . '-' . $tahun . '-' . $timestamp . '.xlsx';

        return Excel::download(
            new ReportAbsenExport(
                $request->filter_year,
                $request->filter_month,
                $request->filter_start,
                $request->filter_end,
                $request->filter_master_organization_id,
                $request->filter_master_position_id,
                $dataLiburRepo,
            ),
            $filename
        );
    }


    public function exportPdf(Request $request, DataLiburFace $dataLiburRepo)
    {
        $year = $request->filter_year;
        $month = $request->filter_month;
        $org = $request->filter_master_organization_id;
        $pos = $request->filter_master_position_id;
        // $name = $request->filter_name;

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = ($year == now()->year && $month == now()->month)
            ? now()->startOfDay()
            : $start->copy()->endOfMonth();

        $tglCol = [];
        while ($start->lte($end)) {
            $tglCol[] = [
                'col_date' => $start->format('d'),
                'col_day' => $start->locale('id')->translatedFormat('l'),
            ];
            $start->addDay();
        }

        $query = DataEmployee::query()
            ->select([
                'data_employees.id',
                'data_employees.name',
                'data_employees.status',
                'data_employees.master_organization_id',
                'data_employees.master_position_id',
                'data_employees.master_location_id',
                'data_employees.master_function_id',
            ])
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
                'log_attendances' => function ($q) use ($year, $month, $request) {
                    $q->select('data_employee_id', 'time')
                        ->whereBetween('time', [$request->filter_start, Carbon::parse($request->filter_end)->addDay()->format('Y-m-d')])
                        // ->whereYear('time', $year)
                        // ->whereMonth('time', $month)
                    ;
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
            ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->orderBy('name');

        if ($org) {
            $query->where('master_organization_id', $org);
        }
        if ($pos) {
            $query->where('master_position_id', $pos);
        }
        // if ($name) {
        //     $query->where('name', 'like', "%{$name}%");
        // }

        $tglMerah = $dataLiburRepo->getByDate($month, $year);
        $dateInMonth = PublicHelper::dateInMonth($request->filter_start, $request->filter_end);

        $data = $query->get()->map(function ($row) use ($dateInMonth, $tglMerah) {
            $row->absensi = PublicHelper::getDtAbsen(
                $dateInMonth,
                $row->log_attendances->toArray(),
                $row->master_schedules->toArray(),
                $row->data_izins->toArray(),
                $tglMerah
            );
            return $row;
        })->toArray();

        $manajer = DataEmployee::whereHas('user_logins', function ($query) {
            $query->where('user_role_id', 500);
        })
            ->pluck('name')
            ->first() ?? 'Manajer belum dipilih';

        $pdf = Pdf::loadView('report.export.report_export_pdf', [
            'data' => $data,
            'tglCol' => $tglCol,
            'year' => $year,
            'month' => $month,
            'manajer' => $manajer
        ])->setPaper('A4', 'landscape');

        $timestamp = now()->format('Hisv'); // jammenitdetikmilisecond
        $bulan = now()->locale('id')->translatedFormat('F'); // Juni
        $tahun = now()->year;
        $filename = 'report-absen-' . strtolower($bulan) . '-' . $tahun . '-' . $timestamp . '.pdf';

        return $pdf->download($filename);
    }
}
