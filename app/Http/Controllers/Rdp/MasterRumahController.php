<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpMasterRumahRepo;
use DataTables;

class MasterRumahController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Unit Rumah | RDP";
        $data['page_title'] = "Data Unit Rumah";
        $data['page_desc'] = "Manajemen data unit rumah dinas.";
        $data['lw'] = "rdp.master-rumah.master-rumah-data";

        return view('rdp.index', compact('data'));
    }

    public function create()
    {
        $data['tab_title'] = "Tambah Data Unit Rumah | RDP";
        $data['page_title'] = "Tambah Data Unit Rumah";
        $data['page_desc'] = "Tambah data fisik unit rumah dinas.";
        $data['lw'] = "rdp.master-rumah.master-rumah-create";

        return view('rdp.index', compact('data'));
    }

    public function detail($id)
    {
        $data['tab_title'] = "Detail Data Unit Rumah | RDP";
        $data['page_title'] = "Detail Data Unit Rumah";
        $data['page_desc'] = "Detail identitas dan status unit rumah.";
        $data['lw'] = "rdp.master-rumah.master-rumah-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function edit($id)
    {
        $data['tab_title'] = "Edit Data Unit Rumah | RDP";
        $data['page_title'] = "Edit Data Unit Rumah";
        $data['page_desc'] = "Ubah identitas dan status unit rumah.";
        $data['lw'] = "rdp.master-rumah.master-rumah-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        $data = RdpMasterRumahRepo::getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
