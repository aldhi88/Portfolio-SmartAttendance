<?php

namespace App\Http\Controllers;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburFace;
use DataTables;
use Illuminate\Http\Request;

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
        // dd($request->all());
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
        if($request->filter_name){
            $data->where('name', 'like', "%{$request->filter_name}%");
        }

        $dateInMonth = PublicHelper::dateInMonth($request->filter_month, $request->filter_year);
        // dd($dateInMonth);

        $tglMerah = $dataLiburRepo->getByDate($request->filter_month, $request->filter_year);
        // dd($dataLibur);

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






}

