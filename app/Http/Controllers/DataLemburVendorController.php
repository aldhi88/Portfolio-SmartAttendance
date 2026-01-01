<?php

namespace App\Http\Controllers;

use App\Helpers\ReportLemburHelper;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;

class DataLemburVendorController extends Controller
{
    public function indexLembur()
    {
        $data['tab_title'] = "Data Lembur | ".config('app.name');
        $data['page_title'] = "Data Lembur";
        $data['page_desc'] = "Manajemen data lembur";
        $data['lw'] = "lembur-vendor.lembur-vendor-data";
        return view('index', compact('data'));
    }

    public function indexLemburDT(
        DataLemburFace $dataLemburRepo,
        Request $request
    )
    {
        $data = $dataLemburRepo->getDataDT(0);
        if(isset($request->month)){
            if($request->month != ''){
                $data->whereMonth('tanggal', $request->month);
            }
        }
        if(isset($request->year)){
            if($request->year != ''){
                $data->whereYear('tanggal', $request->year);
            }
        }

        $orgId = Auth::user()->data_vendors->master_organization_id;
        $data->whereHas('data_employees', function($q) use ($orgId){
            $q->where('master_organization_id', $orgId);
        });

        // dd($data->get()->toArray());

        return DataTables::of($data)
            ->addColumn('laporan_lembur_checkin', function ($data) {
                return ReportLemburHelper::getLemburCheckin($data->toArray());
            })
            ->addColumn('laporan_lembur_checkout', function ($data) {
                return ReportLemburHelper::getLemburCheckout($data->toArray());
            })
            ->toJson();
    }

    public function printPdf(
        $id,
        DataEmployeeFace $dataEmployeeRepo
    ){

        // dd($id);
        $data = DataLembur::query()
            ->where('id', $id)
            ->with([
                'pengawas1',
                'pengawas1.master_positions:id,name',
                'pengawas2',
                'pengawas2.master_positions:id,name',
                'security',
                'security.master_positions:id,name',
                'data_employees',
                'data_employees.master_organizations:id,name',
                'data_employees.master_positions:id,name',
            ])
            ->first()
            ->toArray();
        // dd($data);
        $bladeView = DataLembur::formatOrg($data['data_employees']['master_organization_id']);

        $view = 'lembur.pdf.'.$bladeView;
        // dd($view);
        // $pdf = Pdf::loadView($view)
        //     ->setPaper('A4', 'portrait');

        $pdf = Pdf::loadView($view, compact('data'))
            ->setPaper('A4', 'portrait')
        ;

        // return $pdf->download(uniqid().'.pdf');
        return $pdf->stream(uniqid().'.pdf');
    }
}
