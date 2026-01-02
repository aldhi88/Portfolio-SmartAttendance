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
    ) {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLemburRepo = $dataLemburRepo;
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'data_employee_id'       => 'required',
            'work_time_lembur'       => 'required|date',
            'checkout_time_lembur'   => 'required|date|after_or_equal:work_time_lembur',
            'pekerjaan'              => 'required|string',
            'pengawas1'              => 'required',
            'pengawas2'              => 'nullable',
            'security'               => 'nullable',
            'korlap'                 => 'nullable|string',
        ], [
            'data_employee_id.required'     => 'ID karyawan wajib diisi.',
            'work_time_lembur.required'     => 'Jam Masuk & Pulang wajib diisi.',
            'work_time_lembur.date'         => 'Jam Masuk & Pulang tidak valid.',
            'checkout_time_lembur.required' => 'Jam Masuk & Pulang wajib diisi.',
            'checkout_time_lembur.date'     => 'Jam Masuk & Pulang tidak valid.',
            'checkout_time_lembur.after_or_equal' => 'Jam pulang tidak boleh lebih awal dari jam masuk.',
            'pekerjaan.required'            => 'Pekerjaan wajib diisi.',
            'pengawas1.required'            => 'Pengawas 1 wajib diisi.',
        ]);

        $workTime     = Carbon::parse($validated['work_time_lembur']);
        $checkoutTime = Carbon::parse($validated['checkout_time_lembur']);

        $data = [
            'data_employee_id'                => $validated['data_employee_id'],
            'work_time_lembur'                => $workTime->format('Y-m-d H:i:s'),
            'checkout_time_lembur'            => $checkoutTime->format('Y-m-d H:i:s'),
            'tanggal'                         => $workTime->toDateString(),

            // sama seperti web (livewire)
            'checkin_time_lembur'             => $workTime->copy()->subHours(2)->format('Y-m-d H:i:s'),
            'checkin_deadline_time_lembur'    => $workTime->copy()->addHour()->format('Y-m-d H:i:s'),
            'checkout_deadline_time_lembur'   => $checkoutTime->copy()->addHours(2)->format('Y-m-d H:i:s'),

            'pekerjaan'                       => $validated['pekerjaan'],
            'pengawas1'                       => $validated['pengawas1'],
            'pengawas2'                       => $validated['pengawas2'] ?? null,
            'security'                        => $validated['security'] ?? null,
            'korlap'                          => $validated['korlap'] ?? null,
        ];

        // dd($data);

        if ($this->dataLemburRepo->create($data)) {
            return response()->json([
                'message' => 'Data lembur berhasil disimpan.',
                'data'    => $data,
            ], 201);
        }

        return response()->json([
            'message' => 'Terjadi masalah ketika menyimpan data.',
        ], 400);
    }

    public function update($id, Request $r)
    {

        $validated = $r->validate([
            'data_employee_id'       => 'required',
            'work_time_lembur'       => 'required|date',
            'checkout_time_lembur'   => 'required|date|after_or_equal:work_time_lembur',
            'pekerjaan'              => 'required|string',
            'pengawas1'              => 'required',
            'pengawas2'              => 'nullable',
            'security'               => 'nullable',
            'korlap'                 => 'nullable|string',
        ], [
            'data_employee_id.required'     => 'ID karyawan wajib diisi.',
            'work_time_lembur.required'     => 'Jam Masuk & Pulang wajib diisi.',
            'work_time_lembur.date'         => 'Jam Masuk & Pulang tidak valid.',
            'checkout_time_lembur.required' => 'Jam Masuk & Pulang wajib diisi.',
            'checkout_time_lembur.date'     => 'Jam Masuk & Pulang tidak valid.',
            'checkout_time_lembur.after_or_equal' => 'Jam pulang tidak boleh lebih awal dari jam masuk.',
            'pekerjaan.required'            => 'Pekerjaan wajib diisi.',
            'pengawas1.required'            => 'Pengawas 1 wajib diisi.',
        ]);

        $workTime     = Carbon::parse($validated['work_time_lembur']);
        $checkoutTime = Carbon::parse($validated['checkout_time_lembur']);

        $data['id'] = $id;
        $data['form'] = [
            'data_employee_id'                => $validated['data_employee_id'],
            'work_time_lembur'                => $workTime->format('Y-m-d H:i:s'),
            'checkout_time_lembur'            => $checkoutTime->format('Y-m-d H:i:s'),
            'tanggal'                         => $workTime->toDateString(),

            // sama seperti web (livewire)
            'checkin_time_lembur'             => $workTime->copy()->subHours(2)->format('Y-m-d H:i:s'),
            'checkin_deadline_time_lembur'    => $workTime->copy()->addHour()->format('Y-m-d H:i:s'),
            'checkout_deadline_time_lembur'   => $checkoutTime->copy()->addHours(2)->format('Y-m-d H:i:s'),

            'pekerjaan'                       => $validated['pekerjaan'],
            'pengawas1'                       => $validated['pengawas1'],
            'pengawas2'                       => $validated['pengawas2'] ?? null,
            'security'                        => $validated['security'] ?? null,
            'korlap'                          => $validated['korlap'] ?? null,

            // sama seperti web
            'approved_by'                     => $validated['pengawas1'],
            'status'                          => 'Proses',
        ];

        if ($this->dataLemburRepo->update($data)) {
            return response()->json([
                'message' => 'Data lembur berhasil diubah.',
                'data'    => $data,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Terjadi masalah ketika menyimpan data.',
                'data'    => $data,
            ], 400);
        }
    }

    public function delete($id)
    {
        if ($this->dataLemburRepo->delete($id)) {
            return response()->json([
                'message' => 'Data lembur berhasil dihapus.',
                'data'    => $id,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Terjadi masalah ketika melakukan proses.',
                'data'    => $data,
            ], 400);
        }
    }
}
