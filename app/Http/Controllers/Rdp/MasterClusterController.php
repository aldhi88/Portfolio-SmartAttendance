<?php

namespace App\Http\Controllers\Rdp;

use App\Http\Controllers\Controller;
use App\Repositories\RdpMasterClusterRepo;
use DataTables;

class MasterClusterController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Cluster | RDP";
        $data['page_title'] = "Data Cluster";
        $data['page_desc'] = "Manajemen data cluster rumah dinas.";
        $data['lw'] = "rdp.master-cluster.master-cluster-data";

        return view('rdp.index', compact('data'));
    }

    public function create()
    {
        $data['tab_title'] = "Tambah Data Cluster | RDP";
        $data['page_title'] = "Tambah Data Cluster";
        $data['page_desc'] = "Tambah cluster beserta standar asetnya.";
        $data['lw'] = "rdp.master-cluster.master-cluster-create";

        return view('rdp.index', compact('data'));
    }

    public function detail($id)
    {
        $data['tab_title'] = "Detail Data Cluster | RDP";
        $data['page_title'] = "Detail Data Cluster";
        $data['page_desc'] = "Detail standar aset cluster rumah dinas.";
        $data['lw'] = "rdp.master-cluster.master-cluster-detail";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function edit($id)
    {
        $data['tab_title'] = "Edit Data Cluster | RDP";
        $data['page_title'] = "Edit Data Cluster";
        $data['page_desc'] = "Ubah cluster beserta standar asetnya.";
        $data['lw'] = "rdp.master-cluster.master-cluster-edit";
        $data['id'] = $id;

        return view('rdp.index', compact('data'));
    }

    public function indexDT()
    {
        $data = RdpMasterClusterRepo::getDT(0);

        return DataTables::of($data)
            ->toJson();
    }
}
