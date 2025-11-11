<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    protected $dataLemburRepo;
    protected $dataEmployeeRepo;

    public function __construct(
        DataEmployeeFace $dataEmployeeRepo,
        DataLemburFace $dataLemburRepo,
    )
    {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLemburRepo = $dataLemburRepo;
    }

    public function store(Request $r)
    {

        $validated = $r->validate([
            "data_employee_id" => "required",
            "from" => "required|date",
            "to" => "required|date|after_or_equal:from",
        ], [
            'data_employee_id.required' => 'ID karyawan wajib diisi.',
            'from.required' => 'Tanggal mulai wajib diisi.',
            'from.date' => 'Tanggal mulai wajib diisi dengan benar.',
            'to.required' => 'Tanggal selesai wajib diisi.',
            'to.date' => 'Tanggal selesai wajib diisi dengan benar.',
            'to.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $startTime = Carbon::parse($validated['from'].':00');
        $endTime   = Carbon::parse($validated['to'].':00');
        $tanggal = $startTime->toDateString();

        $data = [
            'data_employee_id'   => $validated['data_employee_id'],
            'approved_by' => $this->dataEmployeeRepo->pengawasCheck($validated['data_employee_id']),
            'tanggal' => $tanggal,
            'checkin_time_lembur' => $startTime->copy()->subHour()->format('Y-m-d H:i:s'),
            'work_time_lembur' => $startTime->format('Y-m-d H:i:s'),
            'checkin_deadline_time_lembur' => $startTime->copy()->addMinutes(15)->format('Y-m-d H:i:s'),
            'checkout_time_lembur' => $endTime->format('Y-m-d H:i:s'),
            'checkout_deadline_time_lembur' => $endTime->copy()->addHours(2)->format('Y-m-d H:i:s'),
            'status' => 'Proses'
        ];

        if($this->dataLemburRepo->create($data)){
            return response()->json([
                'message' => 'Data lembur berhasil diajukan.',
                'data'    => $data,
            ], 201);
        }else{
            return response()->json([
                'message' => 'Terjadi masalah ketika menyimpan data.',
                'data'    => $data,
            ], 400);
        }

    }

    public function delete($id)
    {
        if($this->dataLemburRepo->delete($id)){
            return response()->json([
                'message' => 'Data izin berhasil dihapus.',
                'data'    => $id,
            ], 201);
        }else{
            return response()->json([
                'message' => 'Terjadi masalah ketika melakukan proses.',
                'data'    => $data,
            ], 400);
        }

    }
}
