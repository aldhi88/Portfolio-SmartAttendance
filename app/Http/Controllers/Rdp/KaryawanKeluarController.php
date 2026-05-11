<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Helpers\RdpAccess;
use App\Repositories\RdpKaryawanKeluarRepo;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Support\Facades\Auth;

class KaryawanKeluarController extends Controller
{
    public function adminIndex()
    {
        $data['tab_title'] = "Izin Keluar RDP | RDP";
        $data['page_title'] = "Izin Keluar RDP";
        $data['page_desc'] = "Manajemen izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.admin-data";

        return view('rdp.index', compact('data'));
    }

    public function adminCreate()
    {
        $data['tab_title'] = "Tambah Izin Keluar RDP | RDP";
        $data['page_title'] = "Tambah Izin Keluar RDP";
        $data['page_desc'] = "Tambah data izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.admin-create";

        return view('rdp.index', compact('data'));
    }

    public function adminEdit($id)
    {
        $data['tab_title'] = "Edit Izin Keluar RDP | RDP";
        $data['page_title'] = "Edit Izin Keluar RDP";
        $data['page_desc'] = "Ubah data izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.admin-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminDetail($id)
    {
        $data['tab_title'] = "Detail Izin Keluar RDP | RDP";
        $data['page_title'] = "Detail Izin Keluar RDP";
        $data['page_desc'] = "Detail data izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.admin-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminPendataanAset($id)
    {
        $data['tab_title'] = "Pendataan Aset Izin Keluar RDP | RDP";
        $data['page_title'] = "Pendataan Aset Izin Keluar RDP";
        $data['page_desc'] = "Pendataan aset rumah dinas oleh admin.";
        $data['lw'] = "rdp.karyawan-keluar.admin-pendataan-aset";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminIndexDT()
    {
        return DataTables::of(RdpKaryawanKeluarRepo::getDT())
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
                    $q->where('block', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.tipe', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('tipe', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.nomor', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }

    public function sikPdf($id)
    {
        $item = RdpKaryawanKeluarRepo::getByKey($id);

        abort_if(!$item, 404);
        abort_if($item->status !== RdpKaryawanKeluarRepo::FINISHED_STATUS, 404);
        abort_if(!(
            RdpAccess::isAdmin()
            || RdpAccess::isPimpinan()
            || (RdpAccess::isEmployee() && (int) $item->data_employee_id === (int) RdpAccess::employeeId())
        ), 404);

        $pdf = Pdf::loadView('rdp.karyawan_keluar.pdf.sik', compact('item'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('sik-' . $item->id . '.pdf');
    }

    public function karyawanIndex()
    {
        $data['tab_title'] = "Pengajuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Pengajuan Izin Keluar RDP";
        $data['page_desc'] = "Pengajuan izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.karyawan-data";

        return view('rdp.index', compact('data'));
    }

    public function karyawanCreate()
    {
        $data['tab_title'] = "Tambah Pengajuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Tambah Pengajuan Izin Keluar RDP";
        $data['page_desc'] = "Tambah pengajuan izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.karyawan-create";

        return view('rdp.index', compact('data'));
    }

    public function karyawanEdit($id)
    {
        $data['tab_title'] = "Edit Pengajuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Edit Pengajuan Izin Keluar RDP";
        $data['page_desc'] = "Ubah pengajuan izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.karyawan-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanDetail($id)
    {
        $data['tab_title'] = "Detail Pengajuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Detail Pengajuan Izin Keluar RDP";
        $data['page_desc'] = "Detail pengajuan izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.karyawan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanPendataanAset($id)
    {
        $data['tab_title'] = "Pendataan Aset Izin Keluar RDP | RDP";
        $data['page_title'] = "Pendataan Aset Izin Keluar RDP";
        $data['page_desc'] = "Pendataan aset rumah dinas oleh karyawan.";
        $data['lw'] = "rdp.karyawan-keluar.karyawan-pendataan-aset";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanIndexDT()
    {
        return DataTables::of(RdpKaryawanKeluarRepo::getDT([
            'data_employee_id' => Auth::user()->data_employees?->id,
        ]))
            ->filterColumn('rdp_master_rumahs.block', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('block', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.tipe', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('tipe', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.nomor', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }

    public function pimpinanIndex()
    {
        $data['tab_title'] = "Persetujuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Persetujuan Izin Keluar RDP";
        $data['page_desc'] = "Persetujuan pimpinan untuk izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.pimpinan-data";

        return view('rdp.index', compact('data'));
    }

    public function pimpinanDetail($id)
    {
        $data['tab_title'] = "Detail Persetujuan Izin Keluar RDP | RDP";
        $data['page_title'] = "Detail Persetujuan Izin Keluar RDP";
        $data['page_desc'] = "Detail data persetujuan izin keluar rumah dinas.";
        $data['lw'] = "rdp.karyawan-keluar.pimpinan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function pimpinanIndexDT()
    {
        return DataTables::of(RdpKaryawanKeluarRepo::getDT([
            'status_in' => RdpKaryawanKeluarRepo::PIMPINAN_VISIBLE_STATUS,
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
                    $q->where('block', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.tipe', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('tipe', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rdp_master_rumahs.nomor', function ($query, $keyword) {
                $query->whereHas('rdp_master_rumahs', function ($q) use ($keyword) {
                    $q->where('nomor', 'like', "%$keyword%");
                });
            })
            ->toJson();
    }
}
