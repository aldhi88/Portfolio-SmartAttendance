<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    protected $dataLiburIzinRepo;
    protected $dataEmployeeRepo;

    public function __construct(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburIzinFace $dataLiburIzinRepo,
    )
    {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLiburIzinRepo = $dataLiburIzinRepo;
    }

    public function store(Request $r)
    {

        $data = $r->validate([
            "data_employee_id" => "required",
            "jenis" => "required",
            "from" => "required|date",
            "to" => "required|date|after_or_equal:from",
            "desc" => "nullable|string",
            "bukti" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10240",
        ], [
            'data_employee_id.required' => 'ID karyawan wajib diisi.',
            'jenis.required' => 'Jenis izin wajib diisi.',
            'from.required' => 'Tanggal mulai wajib diisi.',
            'to.required' => 'Tanggal selesai wajib diisi.',
            'to.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'bukti.mimes' => 'File bukti hanya boleh berupa PDF, JPG, JPEG, atau PNG.',
            'bukti.max' => 'Ukuran file bukti maksimal 10 MB.',
        ]);

        $data['approved_by'] = $this->dataEmployeeRepo->pengawasCheck($data['data_employee_id']);
        $data['status'] = "Proses";

        if($this->dataLiburIzinRepo->create($data)){
            return response()->json([
                'message' => 'Data izin berhasil diajukan.',
                'data'    => $data,
            ], 201);
        }else{
            return response()->json([
                'message' => 'Terjadi masalah ketika menyimpan data.',
                'data'    => $data,
            ], 400);
        }

    }

    public function update($id, Request $r)
    {

        $data = $r->validate([
            "data_employee_id" => "required",
            "jenis" => "required",
            "from" => "required|date",
            "to" => "required|date|after_or_equal:from",
            "desc" => "nullable|string",
            "old_file" => "nullable|string",
            "bukti" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10240",
        ], [
            'data_employee_id.required' => 'ID karyawan wajib diisi.',
            'jenis.required' => 'Jenis izin wajib diisi.',
            'from.required' => 'Tanggal mulai wajib diisi.',
            'to.required' => 'Tanggal selesai wajib diisi.',
            'to.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'bukti.mimes' => 'File bukti hanya boleh berupa PDF, JPG, JPEG, atau PNG.',
            'bukti.max' => 'Ukuran file bukti maksimal 10 MB.',
        ]);

        $data['approved_by'] = $this->dataEmployeeRepo->pengawasCheck($data['data_employee_id']);
        $data['status'] = "Proses";
        $dtEdit['form'] = $data;
        $dtEdit['old_file'] = $data['old_file'];
        unset($dtEdit['form']['old_file']);
        $dtEdit['id'] = $id;


        if($this->dataLiburIzinRepo->update($dtEdit)){
            return response()->json([
                'message' => 'Data izin berhasil diubah.',
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
        if($this->dataLiburIzinRepo->delete($id)){
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
