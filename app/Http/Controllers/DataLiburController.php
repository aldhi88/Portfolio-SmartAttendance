<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Http\Request;
use DataTables;

class DataLiburController extends Controller
{
    public function indexMerah()
    {
        $data['tab_title'] = "Data Tanggal Merah | ".config('app.name');
        $data['page_title'] = "Data Tanggal Merah";
        $data['page_desc'] = "Manajemen data tanggal merah";
        $data['lw'] = "libur.tgl-merah";
        return view('index', compact('data'));
    }
    public function indexIzin()
    {
        $data['tab_title'] = "Data Izin Libur | ".config('app.name');
        $data['page_title'] = "Data Izin Libur";
        $data['page_desc'] = "Manajemen data izin libur karyawan";
        $data['lw'] = "libur.data-izin";
        return view('index', compact('data'));
    }
    public function indexIzinDT(DataLiburIzinFace $dataLiburIzinRepo)
    {
        $data = $dataLiburIzinRepo->getDataIzinDT(0);

        return DataTables::of($data)
            ->toJson();
    }
    public function izinCreate()
    {
        $data['tab_title'] = "Form Izin Libur Karyawan | ".config('app.name');
        $data['page_title'] = "Form Izin Libur Karyawan";
        $data['page_desc'] = "Menambah data izin libur karyawan";
        $data['lw'] = "libur.create-izin";
        return view('index', compact('data'));
    }

    public function izinEdit($id)
    {
        $data['id'] = $id;
        $data['tab_title'] = "Form Edit Izin Libur Karyawan | ".config('app.name');
        $data['page_title'] = "Form Edit Izin Libur Karyawan";
        $data['page_desc'] = "Edit data izin libur karyawan";
        $data['lw'] = "libur.edit-izin";
        return view('index', compact('data'));
    }
}
