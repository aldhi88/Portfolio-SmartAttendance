<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpPermintaanRepo;
use DataTables;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    public function adminIndex()
    {
        $data['tab_title'] = 'Permintaan RDP | RDP';
        $data['page_title'] = 'Permintaan RDP';
        $data['page_desc'] = 'Monitoring permintaan rutin karyawan rumah dinas.';
        $data['lw'] = 'rdp.permintaan.admin-data';

        return view('rdp.index', compact('data'));
    }

    public function adminCreate()
    {
        $data['tab_title'] = 'Tambah Permintaan RDP | RDP';
        $data['page_title'] = 'Tambah Permintaan RDP';
        $data['page_desc'] = 'Tambah permintaan rutin untuk karyawan penghuni aktif RDP.';
        $data['lw'] = 'rdp.permintaan.admin-create';

        return view('rdp.index', compact('data'));
    }

    public function adminDetail($id)
    {
        $data['tab_title'] = 'Detail Permintaan RDP | RDP';
        $data['page_title'] = 'Detail Permintaan RDP';
        $data['page_desc'] = 'Detail permintaan rutin karyawan rumah dinas.';
        $data['lw'] = 'rdp.permintaan.admin-detail';
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminIndexDT()
    {
        return $this->datatable(RdpPermintaanRepo::getDT())->toJson();
    }

    public function karyawanIndex()
    {
        $data['tab_title'] = 'Permintaan Saya | RDP';
        $data['page_title'] = 'Permintaan Saya';
        $data['page_desc'] = 'Pengajuan permintaan rutin rumah dinas.';
        $data['lw'] = 'rdp.permintaan.karyawan-data';

        return view('rdp.index', compact('data'));
    }

    public function karyawanCreate()
    {
        $data['tab_title'] = 'Ajukan Permintaan | RDP';
        $data['page_title'] = 'Ajukan Permintaan';
        $data['page_desc'] = 'Ajukan permintaan rutin rumah dinas.';
        $data['lw'] = 'rdp.permintaan.karyawan-create';

        return view('rdp.index', compact('data'));
    }

    public function karyawanDetail($id)
    {
        $data['tab_title'] = 'Detail Permintaan Saya | RDP';
        $data['page_title'] = 'Detail Permintaan Saya';
        $data['page_desc'] = 'Detail permintaan rutin rumah dinas.';
        $data['lw'] = 'rdp.permintaan.karyawan-detail';
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanIndexDT()
    {
        return $this->datatable(RdpPermintaanRepo::getDT([
            'data_employee_id' => Auth::user()->data_employees?->id,
        ]))->toJson();
    }

    protected function datatable($query)
    {
        return DataTables::of($query)
            ->filterColumn('rdp_karyawan_masuks.data_employees.name', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.data_employees', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_karyawan_masuks.data_employees.number', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.data_employees', function ($q) use ($keyword) {
                    $q->where('number', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_karyawan_masuks.data_employees.master_positions.name', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.data_employees.master_positions', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_karyawan_masuks.rdp_master_rumahs.block', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('block', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_karyawan_masuks.rdp_master_rumahs.tipe', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('tipe', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_karyawan_masuks.rdp_master_rumahs.nomor', function ($query, $keyword) {
                $query->whereHas('rdp_karyawan_masuks.rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('nomor', 'like', "%$keyword%");
                });
            });
    }
}
