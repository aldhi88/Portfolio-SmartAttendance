<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
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

        $pdf = Pdf::loadView('rdp.pengadaan.pdf.spk', compact('item'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('spk-pengadaan-' . $item->id . '.pdf');
    }

    protected function datatable($query)
    {
        return DataTables::of($query)
            ->filterColumn('rdp_master_vendors.nama', function ($query, $keyword) {
                $query->whereHas('rdp_master_vendors', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%");
                });
            });
    }
}
