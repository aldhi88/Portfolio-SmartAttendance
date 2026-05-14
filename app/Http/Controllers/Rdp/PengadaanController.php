<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Helpers\RdpAccess;
use App\Repositories\RdpManagerAccountRepo;
use App\Repositories\RdpPengadaanRepo;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function adminIndex()
    {
        $data['tab_title'] = "Pengadaan RDP | RDP";
        $data['page_title'] = "Pengadaan RDP";
        $data['page_desc'] = "Manajemen pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.admin-data";

        return view('rdp.index', compact('data'));
    }

    public function adminCreate()
    {
        $data['tab_title'] = "Tambah Pengadaan RDP | RDP";
        $data['page_title'] = "Tambah Pengadaan RDP";
        $data['page_desc'] = "Tambah pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.admin-create";

        return view('rdp.index', compact('data'));
    }

    public function adminEdit($id)
    {
        $data['tab_title'] = "Edit Pengadaan RDP | RDP";
        $data['page_title'] = "Edit Pengadaan RDP";
        $data['page_desc'] = "Ubah data pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.admin-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminDetail($id)
    {
        $data['tab_title'] = "Detail Pengadaan RDP | RDP";
        $data['page_title'] = "Detail Pengadaan RDP";
        $data['page_desc'] = "Detail data pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.admin-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminIndexDT()
    {
        return $this->datatable(RdpPengadaanRepo::getDT())->toJson();
    }

    public function karyawanIndex()
    {
        $data['tab_title'] = "Pengajuan Pengadaan RDP | RDP";
        $data['page_title'] = "Pengajuan Pengadaan RDP";
        $data['page_desc'] = "Pengajuan pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.karyawan-data";

        return view('rdp.index', compact('data'));
    }

    public function karyawanCreate()
    {
        $data['tab_title'] = "Tambah Pengajuan Pengadaan RDP | RDP";
        $data['page_title'] = "Tambah Pengajuan Pengadaan RDP";
        $data['page_desc'] = "Tambah pengajuan pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.karyawan-create";

        return view('rdp.index', compact('data'));
    }

    public function karyawanEdit($id)
    {
        $data['tab_title'] = "Edit Pengajuan Pengadaan RDP | RDP";
        $data['page_title'] = "Edit Pengajuan Pengadaan RDP";
        $data['page_desc'] = "Ubah pengajuan pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.karyawan-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanDetail($id)
    {
        $data['tab_title'] = "Detail Pengajuan Pengadaan RDP | RDP";
        $data['page_title'] = "Detail Pengajuan Pengadaan RDP";
        $data['page_desc'] = "Detail pengajuan pengadaan barang dan perlengkapan RDP.";
        $data['lw'] = "rdp.pengadaan.karyawan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanIndexDT()
    {
        return $this->datatable(RdpPengadaanRepo::getDT([
            'data_employee_id' => Auth::user()->data_employees?->id,
        ]))->toJson();
    }

    public function pimpinanIndex()
    {
        $data['tab_title'] = "Persetujuan Pengadaan RDP | RDP";
        $data['page_title'] = "Persetujuan Pengadaan RDP";
        $data['page_desc'] = "Persetujuan pimpinan untuk pengadaan RDP.";
        $data['lw'] = "rdp.pengadaan.pimpinan-data";

        return view('rdp.index', compact('data'));
    }

    public function pimpinanDetail($id)
    {
        $data['tab_title'] = "Detail Persetujuan Pengadaan RDP | RDP";
        $data['page_title'] = "Detail Persetujuan Pengadaan RDP";
        $data['page_desc'] = "Detail data persetujuan pengadaan RDP.";
        $data['lw'] = "rdp.pengadaan.pimpinan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function pimpinanIndexDT()
    {
        return $this->datatable(RdpPengadaanRepo::getDT([
            'status_in' => RdpPengadaanRepo::PIMPINAN_VISIBLE_STATUS,
        ]))->toJson();
    }

    public function vendorIndex()
    {
        $data['tab_title'] = "Permintaan Pengadaan RDP | RDP";
        $data['page_title'] = "Permintaan Pengadaan RDP";
        $data['page_desc'] = "Daftar pengadaan RDP yang ditugaskan.";
        $data['lw'] = "rdp.pengadaan.vendor-data";

        return view('rdp.index', compact('data'));
    }

    public function vendorDetail($id)
    {
        $data['tab_title'] = "Detail Permintaan Pengadaan RDP | RDP";
        $data['page_title'] = "Detail Permintaan Pengadaan RDP";
        $data['page_desc'] = "Detail pengadaan RDP yang ditugaskan.";
        $data['lw'] = "rdp.pengadaan.vendor-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function vendorIndexDT()
    {
        return $this->datatable(RdpPengadaanRepo::getDT([
            'vendor_id' => Auth::user()->rdp_master_vendors?->id,
        ]))->toJson();
    }

    public function spkPdf($id)
    {
        $item = RdpPengadaanRepo::getByKey($id);
        $visibleStatus = [
            RdpPengadaanRepo::WORK_RUNNING_STATUS,
            RdpPengadaanRepo::VENDOR_FINISHED_STATUS,
            RdpPengadaanRepo::RESULT_SPV_APPROVED_STATUS,
            RdpPengadaanRepo::FINISHED_STATUS,
        ];

        abort_if(!$item, 404);
        abort_if(!in_array($item->status, $visibleStatus, true), 404);
        abort_if(!(
            RdpAccess::isAdmin()
            || RdpAccess::isPimpinan()
            || (RdpAccess::isVendor() && (int) $item->rdp_master_vendor_id === (int) RdpAccess::vendorId())
            || (RdpAccess::isEmployee() && (int) $item->rdp_karyawan_masuks?->data_employee_id === (int) RdpAccess::employeeId())
        ), 404);

        $item = RdpPengadaanRepo::ensureSpkNumber($item);
        $managerAsetRegion = RdpManagerAccountRepo::getPrintSignerByRole(RdpManagerAccountRepo::MANAGER_ASET_REGION_ROLE);

        $pdf = Pdf::loadView('rdp.pengadaan.pdf.spk', compact('item', 'managerAsetRegion'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('spk-pengadaan-' . $item->id . '.pdf');
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
            })
            ->filterColumn('rdp_master_vendors.nama', function ($query, $keyword) {
                $query->whereHas('rdp_master_vendors', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%");
                });
            });
    }
}
