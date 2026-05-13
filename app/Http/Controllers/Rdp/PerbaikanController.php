<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Helpers\RdpAccess;
use App\Repositories\RdpPerbaikanRepo;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Support\Facades\Auth;

class PerbaikanController extends Controller
{
    public function adminIndex()
    {
        $data['tab_title'] = "Perbaikan RDP | RDP";
        $data['page_title'] = "Perbaikan RDP";
        $data['page_desc'] = "Manajemen permintaan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.admin-data";

        return view('rdp.index', compact('data'));
    }

    public function adminCreate()
    {
        $data['tab_title'] = "Tambah Perbaikan RDP | RDP";
        $data['page_title'] = "Tambah Perbaikan RDP";
        $data['page_desc'] = "Tambah permintaan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.admin-create";

        return view('rdp.index', compact('data'));
    }

    public function adminEdit($id)
    {
        $data['tab_title'] = "Edit Perbaikan RDP | RDP";
        $data['page_title'] = "Edit Perbaikan RDP";
        $data['page_desc'] = "Ubah data perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.admin-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminDetail($id)
    {
        $data['tab_title'] = "Detail Perbaikan RDP | RDP";
        $data['page_title'] = "Detail Perbaikan RDP";
        $data['page_desc'] = "Detail data perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.admin-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function adminIndexDT()
    {
        return $this->datatable(RdpPerbaikanRepo::getDT())->toJson();
    }

    public function karyawanIndex()
    {
        $data['tab_title'] = "Pengajuan Perbaikan RDP | RDP";
        $data['page_title'] = "Pengajuan Perbaikan RDP";
        $data['page_desc'] = "Pengajuan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.karyawan-data";

        return view('rdp.index', compact('data'));
    }

    public function karyawanCreate()
    {
        $data['tab_title'] = "Tambah Pengajuan Perbaikan RDP | RDP";
        $data['page_title'] = "Tambah Pengajuan Perbaikan RDP";
        $data['page_desc'] = "Tambah pengajuan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.karyawan-create";

        return view('rdp.index', compact('data'));
    }

    public function karyawanEdit($id)
    {
        $data['tab_title'] = "Edit Pengajuan Perbaikan RDP | RDP";
        $data['page_title'] = "Edit Pengajuan Perbaikan RDP";
        $data['page_desc'] = "Ubah pengajuan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.karyawan-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanDetail($id)
    {
        $data['tab_title'] = "Detail Pengajuan Perbaikan RDP | RDP";
        $data['page_title'] = "Detail Pengajuan Perbaikan RDP";
        $data['page_desc'] = "Detail pengajuan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.karyawan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function karyawanIndexDT()
    {
        return $this->datatable(RdpPerbaikanRepo::getDT([
            'data_employee_id' => Auth::user()->data_employees?->id,
        ]))->toJson();
    }

    public function pimpinanIndex()
    {
        $data['tab_title'] = "Persetujuan Perbaikan RDP | RDP";
        $data['page_title'] = "Persetujuan Perbaikan RDP";
        $data['page_desc'] = "Persetujuan pimpinan untuk perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.pimpinan-data";

        return view('rdp.index', compact('data'));
    }

    public function pimpinanDetail($id)
    {
        $data['tab_title'] = "Detail Persetujuan Perbaikan RDP | RDP";
        $data['page_title'] = "Detail Persetujuan Perbaikan RDP";
        $data['page_desc'] = "Detail data persetujuan perbaikan rumah dinas.";
        $data['lw'] = "rdp.perbaikan.pimpinan-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function pimpinanIndexDT()
    {
        return $this->datatable(RdpPerbaikanRepo::getDT([
            'status_in' => RdpPerbaikanRepo::PIMPINAN_VISIBLE_STATUS,
        ]))->toJson();
    }

    public function vendorIndex()
    {
        $data['tab_title'] = "Permintaan Perbaikan RDP | RDP";
        $data['page_title'] = "Permintaan Perbaikan RDP";
        $data['page_desc'] = "Daftar perbaikan rumah dinas yang ditugaskan.";
        $data['lw'] = "rdp.perbaikan.vendor-data";

        return view('rdp.index', compact('data'));
    }

    public function vendorDetail($id)
    {
        $data['tab_title'] = "Detail Permintaan Perbaikan RDP | RDP";
        $data['page_title'] = "Detail Permintaan Perbaikan RDP";
        $data['page_desc'] = "Detail perbaikan rumah dinas yang ditugaskan.";
        $data['lw'] = "rdp.perbaikan.vendor-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function vendorIndexDT()
    {
        return $this->datatable(RdpPerbaikanRepo::getDT([
            'vendor_id' => Auth::user()->rdp_master_vendors?->id,
        ]))->toJson();
    }

    public function asetRegionIndex()
    {
        $data['tab_title'] = "Approval Manager Aset Region Perbaikan RDP | RDP";
        $data['page_title'] = "Approval Manager Aset Region Perbaikan RDP";
        $data['page_desc'] = "Approval Manager Aset Region sebelum penerbitan SPK perbaikan.";
        $data['lw'] = "rdp.perbaikan.aset-region-data";

        return view('rdp.index', compact('data'));
    }

    public function asetRegionDetail($id)
    {
        $data['tab_title'] = "Detail Approval Manager Aset Region Perbaikan RDP | RDP";
        $data['page_title'] = "Detail Approval Manager Aset Region Perbaikan RDP";
        $data['page_desc'] = "Detail approval Manager Aset Region sebelum penerbitan SPK perbaikan.";
        $data['lw'] = "rdp.perbaikan.aset-region-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function asetRegionIndexDT()
    {
        return $this->datatable(RdpPerbaikanRepo::getDT([
            'status_in' => RdpPerbaikanRepo::ASET_REGION_VISIBLE_STATUS,
        ]))->toJson();
    }

    public function spkPdf($id)
    {
        $item = RdpPerbaikanRepo::getByKey($id);
        $visibleStatus = [
            RdpPerbaikanRepo::WORK_RUNNING_STATUS,
            RdpPerbaikanRepo::VENDOR_FINISHED_STATUS,
            RdpPerbaikanRepo::RESULT_SPV_APPROVED_STATUS,
            RdpPerbaikanRepo::FINISHED_STATUS,
        ];

        abort_if(!$item, 404);
        abort_if(!in_array($item->status, $visibleStatus, true), 404);
        abort_if(!(
            RdpAccess::isAdmin()
            || RdpAccess::isPimpinan()
            || RdpAccess::isManagerAsetRegion()
            || (RdpAccess::isVendor() && (int) $item->rdp_master_vendor_id === (int) RdpAccess::vendorId())
            || (RdpAccess::isEmployee() && (int) $item->rdp_karyawan_masuks?->data_employee_id === (int) RdpAccess::employeeId())
        ), 404);

        $pdf = Pdf::loadView('rdp.perbaikan.pdf.spk', compact('item'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('spk-perbaikan-' . $item->id . '.pdf');
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
