<?php

namespace App\Livewire\Schedule;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\MasterScheduleFace;
use Livewire\Component;

class ScheduleCreate extends Component
{

    protected $masterScheduleRepo;
    public function boot(MasterScheduleFace $masterScheduleRepo)
    {
        $this->masterScheduleRepo = $masterScheduleRepo;
    }

    // insert rotasi
    public function wireSubmitRotasi()
    {
        $this->validate($this->rulesRotasi());
        $this->dtRotasi['type'] = $this->pass['type'];
        if($this->masterScheduleRepo->create($this->dtRotasi)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('dtRotasi');
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    public $dtRotasi = [];
    public function rulesRotasi()
    {
        return [
            "dtRotasi.kode" => "required|max:16|unique:master_schedules,kode,NULL,id,deleted_at,NULL",
            "dtRotasi.name" => "required|unique:master_schedules,name,NULL,id,deleted_at,NULL",
            "dtRotasi.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.type" => "",
            "dtRotasi.day_work.start_date" => "required",
            "dtRotasi.day_work.work_day" => "required|numeric|min:1",
            "dtRotasi.day_work.off_day" => "required|numeric|min:1",

            "dtRotasi.day_work.rotasi.sore.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.sore.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.sore.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.sore.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.malam.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.malam.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.malam.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtRotasi.day_work.rotasi.malam.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
        ];
    }
    // end insert rotasi

    // insert tetap
    public function wireSubmitTetap()
    {
        $this->validate($this->rulesTetap());
        $this->dtTetap['type'] = $this->pass['type'];
        if($this->masterScheduleRepo->create($this->dtTetap)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('dtTetap');
            $this->setDtCreateTetap();
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $dtTetap = [];
    public function rulesTetap()
    {
        return [
            "dtTetap.kode" => "required|unique:master_schedules,kode,NULL,id,deleted_at,NULL",
            "dtTetap.name" => "required|unique:master_schedules,name,NULL,id,deleted_at,NULL",
            "dtTetap.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.type" => "",
            "dtTetap.day_work.regular" => "required",
            "dtTetap.day_work.lembur" => "",
        ];
    }
    // end insert tetap

    public $validationAttributes = [
        "dtTetap.kode" => "Kode Jadwal",
        "dtTetap.name" => "Nama Jadwal",
        "dtTetap.checkin_time" => "Jam Boleh Check-In",
        "dtTetap.work_time" => "Jam Akhir Check-In Ontime",
        "dtTetap.checkin_deadline_time" => "Jam Akhir Check-In",
        "dtTetap.checkout_time" => "Jam Boleh Checkout",
        "dtTetap.day_work.regular" => "Hari Kerja Regular",
        "dtTetap.day_work.lembur" => "Hari Kerja Lembur",

        "dtRotasi.kode" => "Kode Jadwal",
        "dtRotasi.name" => "Nama Jadwal",
        "dtRotasi.checkin_time" => "Jam Boleh Check-In",
        "dtRotasi.work_time" => "Jam Akhir Check-In Ontime",
        "dtRotasi.checkin_deadline_time" => "Jam Akhir Check-In",
        "dtRotasi.checkout_time" => "Jam Boleh Checkout",
        "dtRotasi.day_work.regular" => "Hari Kerja Regular",
        "dtRotasi.day_work.lembur" => "Hari Kerja Lembur",
        "dtRotasi.day_work.start_date" => "Tanggal Mulai Rotasi",
        "dtRotasi.day_work.work_day" => "Jumlah Hari Kerja",
        "dtRotasi.day_work.off_day" => "Jumlah Hari Off",

        "dtRotasi.day_work.rotasi.sore.checkin_time" => "Jam Boleh Check-In",
        "dtRotasi.day_work.rotasi.sore.work_time" => "Jam Akhir Check-In Ontime",
        "dtRotasi.day_work.rotasi.sore.checkin_deadline_time" => "Jam Akhir Check-In",
        "dtRotasi.day_work.rotasi.sore.checkout_time" => "Jam Boleh Checkout",

        "dtRotasi.day_work.rotasi.malam.checkin_time" => "Jam Boleh Check-In",
        "dtRotasi.day_work.rotasi.malam.work_time" => "Jam Akhir Check-In Ontime",
        "dtRotasi.day_work.rotasi.malam.checkin_deadline_time" => "Jam Akhir Check-In",
        "dtRotasi.day_work.rotasi.malam.checkout_time" => "Jam Boleh Checkout",
    ];

    protected $messages = [
        "dtTetap.day_work.regular.required" => ":attribute wajib dipilih minimal 1 hari.",
        'dtTetap.checkin_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtTetap.work_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtTetap.checkin_deadline_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtTetap.checkout_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtRotasi.checkin_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtRotasi.work_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtRotasi.checkin_deadline_time.regex' => 'Format tidak valid (ex: 08:00)',
        'dtRotasi.checkout_time.regex' => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.sore.checkin_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.sore.work_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.sore.checkin_deadline_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.sore.checkout_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.malam.checkin_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.malam.work_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.malam.checkin_deadline_time.regex" => 'Format tidak valid (ex: 08:00)',
        "dtRotasi.day_work.rotasi.malam.checkout_time.regex" => 'Format tidak valid (ex: 08:00)',
    ];

    public $hariIndo;
    public function mount()
    {
        $this->hariIndo = PublicHelper::getHariIndo();
        $this->setDtCreateTetap();
    }

    public function setDtCreateTetap()
    {
        foreach ($this->hariIndo as $key => $value) {
            $this->dtTetap['day_work']['regular'][$key+1] = $this->dtTetap['day_work']['regular'][$key+1] ?? false;
            $this->dtTetap['day_work']['lembur'][$key+1] = $this->dtTetap['day_work']['lembur'][$key+1] ?? false;
        }
    }

    public $pass;
    public function render()
    {
        return view('schedule.schedule_create_'.$this->pass['type']);
    }
}
