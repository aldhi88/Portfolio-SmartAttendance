<?php

namespace App\Livewire\Schedule;

use App\Repositories\Interfaces\MasterScheduleFace;
use Livewire\Component;

class ScheduleCreate extends Component
{

    protected $masterScheduleRepo;
    public function boot(MasterScheduleFace $masterScheduleRepo)
    {
        $this->masterScheduleRepo = $masterScheduleRepo;
    }

    public $dtTetap = [];
    public function rulesTetap()
    {
        return [
            "dtTetap.name" => "required|unique:master_schedules,name,NULL,id,deleted_at,NULL",
            "dtTetap.checkin_time" => "required",
            "dtTetap.work_time" => "required",
            "dtTetap.checkin_deadline_time" => "required",
            "dtTetap.checkout_time" => "required",
            "dtTetap.day_type" => "",
            "dtTetap.day_work.regular" => "required",
            "dtTetap.day_work.lembur" => "required",
        ];
    }

    public function wireSubmitTetap()
    {
        $this->validate($this->rulesTetap());
        $this->dtTetap['day_work']['regular'] = array_keys(array_filter($this->dtTetap['day_work']['regular']));
        $this->dtTetap['day_work']['lembur'] = array_keys(array_filter($this->dtTetap['day_work']['lembur']));
        if($this->masterScheduleRepo->create($this->dtTetap)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('dtTetap');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $validationAttributes = [
        "dtTetap.name" => "Nama Jadwal",
        "dtTetap.checkin_time" => "Jam Boleh Absen Masuk",
        "dtTetap.work_time" => "Jam Mulai Kerja ",
        "dtTetap.checkin_deadline_time" => "Jam Batas Toleransi",
        "dtTetap.checkout_time" => "Jam Pulang",
        "dtTetap.day_work.regular" => "Hari Kerja Regular",
        "dtTetap.day_work.lembur" => "Hari Kerja Lembur",
    ];

    protected $messages = [
        "dtTetap.day_work.regular.required" => ":attribute wajib dipilih minimal 1 hari.",
        "dtTetap.day_work.lembur.required" => ":attribute wajib dipilih minimal 1 hari.",
    ];

    public $hariIndo;
    public function mount()
    {
        $this->hariIndo = $this->masterScheduleRepo->getHariIndo();
        $this->dtTetap['day_type'] = $this->pass['type'];
    }

    public $pass;
    public function render()
    {
        return view('schedule.schedule_create_'.$this->pass['type']);
    }
}
