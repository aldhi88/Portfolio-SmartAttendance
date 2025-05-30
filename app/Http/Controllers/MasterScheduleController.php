<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MasterScheduleFace;
use Illuminate\Support\Str;
use DataTables;

class MasterScheduleController extends Controller
{
    public function index()
    {
        $data['tab_title'] = "Data Jadwal Kerja | ".config('app.name');
        $data['page_title'] = "Data Jadwal Kerja";
        $data['page_desc'] = "Manajemen data jadwal kerja";
        $data['lw'] = "schedule.schedule-data";
        return view('index', compact('data'));
    }

    public function indexDT(MasterScheduleFace $masterScheduleRepo)
    {
        $data = $masterScheduleRepo->getDT(0);
        return DataTables::of($data)
            ->toJson();
    }

    public function create($type)
    {
        $data['type'] = $type;
        $type = Str::ucfirst($type);
        $data['tab_title'] = "Jadwal ".$type." Baru | ".config('app.name');
        $data['page_title'] = "Jadwal ".$type." Baru";
        $data['page_desc'] = "Form menambah data jadwal kerja";
        $data['lw'] = "schedule.schedule-create";
        return view('index', compact('data'));
    }

    public function edit($id,$type)
    {
        $data['type'] = $type;
        $data['editId'] = $id;
        $type = Str::ucfirst($type);
        $data['tab_title'] = "Edit Data Jadwal ".$type." | ".config('app.name');
        $data['page_title'] = "Edit Data Jadwal ".$type;
        $data['page_desc'] = "Form edit data jadwal kerja";
        $data['lw'] = "schedule.schedule-edit";
        return view('index', compact('data'));
    }
}
