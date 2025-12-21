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

class DataLemburController extends Controller
{
    public function indexLembur()
    {
        $data['tab_title'] = "Data Lembur | ".config('app.name');
        $data['page_title'] = "Data Lembur";
        $data['page_desc'] = "Manajemen data lembur";
        $data['lw'] = "lembur.lembur-data";
        return view('index', compact('data'));
    }

    public function indexLemburDT(DataLemburFace $dataLemburRepo)
    {
        if(Auth::user()->is_pengawas){
            $data = $dataLemburRepo->getDataByPengawas(0);
        }else{
            $data = $dataLemburRepo->getDataDT(0);
        }

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
    public function lemburCreate()
    {
        $data['tab_title'] = "Form Data Lembur | ".config('app.name');
        $data['page_title'] = "Form Data Lembur";
        $data['page_desc'] = "Menambah data izin libur karyawan";
        $data['lw'] = "lembur.data-lembur-create";
        return view('index', compact('data'));
    }

    public function lemburEdit($id)
    {
        $data['id'] = $id;
        $data['tab_title'] = "Form Edit Data Lembur | ".config('app.name');
        $data['page_title'] = "Form Edit Data Lembur";
        $data['page_desc'] = "Edit data lembur karyawan";
        $data['lw'] = "lembur.data-lembur-edit";
        return view('index', compact('data'));
    }

    public function printPdf(
        $id,
        DataEmployeeFace $dataEmployeeRepo
    )
    {

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
        $formatPdf = [
            1 => 'format_patra_niaga',
            2 => 'format_ptc',
            3 => 'format_ptc',
            9 => 'format_ptc',
        ];

        $view = 'lembur.pdf.'.$formatPdf[$data['data_employees']['master_organization_id']];
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
