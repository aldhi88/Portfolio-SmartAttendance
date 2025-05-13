<?php

namespace App\Repositories;

use App\Models\DataEmployee;
use App\Models\LogAttendance;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\LogAttendanceInterface;
use Carbon\Carbon;

class LogAttendanceRepository implements LogAttendanceInterface
{
    protected $dataEmployeeRepo;

    public function __construct(DataEmployeeFace $dataEmployeeRepo)
    {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
    }

    public function store(array $data)
    {
        $ids = collect($data)->pluck('data_employee_id')->all(); // [1, 2]
        $existingIds = DataEmployee::whereIn('id', $ids)->pluck('id')->all();
        $toInsert = collect($data)->filter(function ($item) use ($existingIds) {
            return !in_array($item['data_employee_id'], $existingIds);
        })->values()->all();


        if (!empty($toInsert)) {
            foreach ($toInsert as $key => $value) {
                $dtEmployee[$key]['id'] = $value['data_employee_id'];
                $dtEmployee[$key]['name'] = $value['name'];
                $dtEmployee[$key]['created_at'] = Carbon::now();
                $dtEmployee[$key]['updated_at'] = Carbon::now();
            }

            dd($dtEmployee);

            $this->dataEmployeeRepo->insertAPI($dtEmployee);
        }

        return LogAttendance::insert($data);
    }

    public function getLastTimeByMachine($data)
    {
        return LogAttendance::where('master_machine_id', $data)->max('time');
    }
}
