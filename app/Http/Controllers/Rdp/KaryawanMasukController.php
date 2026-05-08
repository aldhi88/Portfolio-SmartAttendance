<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpKaryawanMasukRepo;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Support\Facades\Auth;

class KaryawanMasukController extends Controller
{
    public function adminIndex()
    {
        $data['tab_title'] = "Izin Penempatan RDP | RDP";
        $data['page_title'] = "Izin Penempatan RDP";
        $data['page_desc'] = "Manajemen izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.admin-data";

        return view('rdp.index', compact('data'));
    }

    public function adminCreate()
    {
        $data['tab_title'] = "Tambah Izin Penempatan RDP | RDP";
        $data['page_title'] = "Tambah Izin Penempatan RDP";
        $data['page_desc'] = "Tambah data izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.admin-create";

        return view('rdp.index', compact('data'));
    }

    public function adminEdit($id)
    {
        $data['tab_title'] = "Edit Izin Penempatan RDP | RDP";
        $data['page_title'] = "Edit Izin Penempatan RDP";
        $data['page_desc'] = "Ubah data izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.admin-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminDetail($id)
    {
        $data['tab_title'] = "Detail Izin Penempatan RDP | RDP";
        $data['page_title'] = "Detail Izin Penempatan RDP";
        $data['page_desc'] = "Detail data izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.admin-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminPendataanAset($id)
    {
        $data['tab_title'] = "Pendataan Aset Izin Penempatan RDP | RDP";
        $data['page_title'] = "Pendataan Aset Izin Penempatan RDP";
        $data['page_desc'] = "Pendataan aset rumah dinas oleh admin.";
        $data['lw'] = "rdp.karyawan-masuk.admin-pendataan-aset";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminIndexDT()
    {
        return DataTables::of(RdpKaryawanMasukRepo::getDT())
            ->filterColumn('data_employees.name', function ($query, $keyword) {
                $query->whereHas('data_employees', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('data_employees.number', function ($query, $keyword) {
                $query->whereHas('data_employees', function ($q) use ($keyword) {
                    $q->where('number', 'like', "%$keyword%");
                });
            })
            ->filterColumn('data_employees.master_positions.name', function ($query, $keyword) {
                $query->whereHas('data_employees.master_positions', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.block', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('block', 'like', "%$keyword%")
                        ->orWhere('tipe', 'like', "%$keyword%")
                        ->orWhere('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }

    public function sipPdf($id)
    {
        $item = RdpKaryawanMasukRepo::getByKey($id);

        abort_if(!$item, 404);
        abort_if($item->status !== RdpKaryawanMasukRepo::FINISHED_STATUS, 404);

        $pdf = Pdf::loadView('rdp.karyawan_masuk.pdf.sip', compact('item'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('sip-' . $item->id . '.pdf');
    }

    public function karyawanIndex()
    {
        $data['tab_title'] = "Pengajuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Pengajuan Izin Penempatan RDP";
        $data['page_desc'] = "Pengajuan izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.karyawan-data";

        return view('rdp.index', compact('data'));
    }

    public function karyawanCreate()
    {
        $data['tab_title'] = "Tambah Pengajuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Tambah Pengajuan Izin Penempatan RDP";
        $data['page_desc'] = "Tambah pengajuan izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.karyawan-create";

        return view('rdp.index', compact('data'));
    }

    public function karyawanEdit($id)
    {
        $data['tab_title'] = "Edit Pengajuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Edit Pengajuan Izin Penempatan RDP";
        $data['page_desc'] = "Ubah pengajuan izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.karyawan-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanDetail($id)
    {
        $data['tab_title'] = "Detail Pengajuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Detail Pengajuan Izin Penempatan RDP";
        $data['page_desc'] = "Detail pengajuan izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.karyawan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanPendataanAset($id)
    {
        $data['tab_title'] = "Pendataan Aset Izin Penempatan RDP | RDP";
        $data['page_title'] = "Pendataan Aset Izin Penempatan RDP";
        $data['page_desc'] = "Pendataan aset rumah dinas oleh karyawan.";
        $data['lw'] = "rdp.karyawan-masuk.karyawan-pendataan-aset";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanIndexDT()
    {
        return DataTables::of(RdpKaryawanMasukRepo::getDT([
            'data_employee_id' => Auth::user()->data_employees?->id,
        ]))
            ->filterColumn('rdp_master_rumahs.block', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('block', 'like', "%$keyword%")
                        ->orWhere('tipe', 'like', "%$keyword%")
                        ->orWhere('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }

    public function pimpinanIndex()
    {
        $data['tab_title'] = "Persetujuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Persetujuan Izin Penempatan RDP";
        $data['page_desc'] = "Persetujuan pimpinan untuk izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.pimpinan-data";

        return view('rdp.index', compact('data'));
    }

    public function pimpinanDetail($id)
    {
        $data['tab_title'] = "Detail Persetujuan Izin Penempatan RDP | RDP";
        $data['page_title'] = "Detail Persetujuan Izin Penempatan RDP";
        $data['page_desc'] = "Detail data persetujuan izin penempatan rumah dinas.";
        $data['lw'] = "rdp.karyawan-masuk.pimpinan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function pimpinanIndexDT()
    {
        return DataTables::of(RdpKaryawanMasukRepo::getDT([
            'status_in' => RdpKaryawanMasukRepo::PIMPINAN_VISIBLE_STATUS,
        ]))
            ->filterColumn('data_employees.name', function ($query, $keyword) {
                $query->whereHas('data_employees', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('data_employees.number', function ($query, $keyword) {
                $query->whereHas('data_employees', function ($q) use ($keyword) {
                    $q->where('number', 'like', "%$keyword%");
                });
            })
            ->filterColumn('data_employees.master_positions.name', function ($query, $keyword) {
                $query->whereHas('data_employees.master_positions', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.block', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('block', 'like', "%$keyword%")
                        ->orWhere('tipe', 'like', "%$keyword%")
                        ->orWhere('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }
}
