<?php

namespace App\Http\Controllers;

use App\Helpers\ReportLemburHelper;
use App\Repositories\Interfaces\DataLemburFace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
