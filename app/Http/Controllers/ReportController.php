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
    )
    {
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
                'master_schedules:id,type,kode,checkin_time,work_time,checkin_deadline_time,checkout_time,day_work',
                'log_attendances' => function ($q) use ($request) {
                    $q->select('data_employee_id', 'time')
                    ->whereYear('time', $request->filter_year)
                    ->whereMonth('time', $request->filter_month);
                },
                'data_izins' => function ($q) use ($request) {
                    $q->select('id','data_employee_id','jenis','from','to','desc')
                        ->where('status', 'Disetujui');
                },
            ])
        ;

        if($request->filter_master_organization_id){
            $data->where('master_organization_id', $request->filter_master_organization_id);
        }
        if($request->filter_master_position_id){
            $data->where('master_position_id', $request->filter_master_position_id);
        }
        // if($request->filter_name){
        //     $data->where('name', 'like', "%{$request->filter_name}%");
        // }

        $dateInMonth = PublicHelper::dateInMonth($request->filter_month, $request->filter_year);
        // dd($dateInMonth);

        $tglMerah = $dataLiburRepo->getByDate($request->filter_month, $request->filter_year);
        // dd($dataLibur);

        // dd($data->get()->toArray());

        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->addColumn('absensi', function ($data) use($dateInMonth, $tglMerah) {
                return PublicHelper::getDtAbsen(
                    $dateInMonth,
                    $data->log_attendances->toArray(),
                    $data->master_schedules->toArray(),
                    $data->data_izins->toArray(),
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
    )
    {
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
                'master_schedules:id,type,kode,checkin_time,work_time,checkin_deadline_time,checkout_time,day_work',
                'log_attendances' => function ($q) use ($request) {
                    $q->select('data_employee_id', 'time')
                    ->whereYear('time', $request->filter_year)
                    ->whereMonth('time', $request->filter_month);
                },
                'data_izins' => function ($q) use ($request) {
                    $q->select('id','data_employee_id','jenis','from','to','desc')
                        ->where('status', 'Disetujui');
                },
            ])
        ;

        if($request->filter_master_organization_id){
            $data->where('master_organization_id', $request->filter_master_organization_id);
        }
        if($request->filter_master_position_id){
            $data->where('master_position_id', $request->filter_master_position_id);
        }

        $dateInMonth = PublicHelper::dateInMonth($request->filter_month, $request->filter_year);
        // dd($dateInMonth);

        $tglMerah = $dataLiburRepo->getByDate($request->filter_month, $request->filter_year);
        // dd($dataLibur);

        // dd($data->get()->toArray());

        return DataTables::of($data)
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->addColumn('akumulasi', function ($data) use($dateInMonth, $tglMerah) {
                return PublicHelper::getAkumulasi(
                    $dateInMonth,
                    $data->log_attendances->toArray(),
                    $data->master_schedules->toArray(),
                    $data->data_izins->toArray(),
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
                $request->filter_master_organization_id,
                $request->filter_master_position_id,
                // $request->filter_name,
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
                'log_attendances' => function ($q) use ($year, $month) {
                    $q->select('data_employee_id', 'time')
                        ->whereYear('time', $year)
                        ->whereMonth('time', $month);
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
            ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->orderBy('id');

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
        $dateInMonth = PublicHelper::dateInMonth($month, $year);

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

        $pdf = Pdf::loadView('report.export.report_export_pdf', [
            'data' => $data,
            'tglCol' => $tglCol,
            'year' => $year,
            'month' => $month
        ])->setPaper('A4', 'landscape');

        $timestamp = now()->format('Hisv'); // jammenitdetikmilisecond
        $bulan = now()->locale('id')->translatedFormat('F'); // Juni
        $tahun = now()->year;
        $filename = 'report-absen-' . strtolower($bulan) . '-' . $tahun . '-' . $timestamp . '.pdf';

        return $pdf->download($filename);
    }






}

