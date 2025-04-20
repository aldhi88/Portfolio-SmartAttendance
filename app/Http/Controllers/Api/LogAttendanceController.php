<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LogAttendanceInterface;
use Illuminate\Http\Request;

class LogAttendanceController extends Controller
{
    protected $logAttendanceRepository;

    public function __construct(LogAttendanceInterface $logAttendanceRepository)
    {
        $this->logAttendanceRepository = $logAttendanceRepository;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.data_employee_id' => 'required',
            'attendances.*.master_machine_id' => 'required|exists:master_machines,id',
            'attendances.*.master_minor_id' => 'required|exists:master_minors,id',
            'attendances.*.name' => 'required',
            'attendances.*.time' => 'required|date_format:Y-m-d H:i:s',
            'attendances.*.created_at' => 'required|date_format:Y-m-d H:i:s',
            'attendances.*.updated_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Insert batch langsung ke database
        $this->logAttendanceRepository->store($validated['attendances']);

        return response()->json([
            'message' => 'Log attendance berhasil disimpan'
        ], 201);
    }

    public function getLastTimeByMachine(Request $request, $param)
    {
        $lastTime = $this->logAttendanceRepository->getLastTimeByMachine($param);
        return response()->json([
            'last_time' => $lastTime
        ], 201);
    }

}
