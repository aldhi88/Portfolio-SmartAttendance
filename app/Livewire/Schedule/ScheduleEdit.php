<?php

namespace App\Livewire\Schedule;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\MasterScheduleFace;
use Carbon\Carbon;
use Livewire\Component;

class ScheduleEdit extends Component
{
    protected $masterScheduleRepo;
    public function boot(MasterScheduleFace $masterScheduleRepo)
    {
        $this->masterScheduleRepo = $masterScheduleRepo;
    }

    // edit rotasi
    public function wireSubmitRotasi()
    {
        $this->validate($this->rulesRotasi());
        $this->dtRotasi['type'] = $this->pass['type'];
        if($this->masterScheduleRepo->update($this->pass['editId'], $this->dtRotasi)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->genDataEditRotasi();
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    public $dtRotasi = [];
    public function rulesRotasi()
    {
        return [
            "dtRotasi.kode" => "required|unique:master_schedules,kode,{$this->pass['editId']},id,deleted_at,NULL",
            "dtRotasi.name" => "required|unique:master_schedules,name,{$this->pass['editId']},id,deleted_at,NULL",
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
    // end edit rotasi


    // edit tetap
    public function wireSubmitTetap()
    {
        $this->validate($this->rulesTetap());
        $this->dtTetap['type'] = $this->pass['type'];
        if($this->masterScheduleRepo->update($this->pass['editId'], $this->dtTetap)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->genDataEditTetap();
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $dtTetap = [];
    public function rulesTetap()
    {
        return [
            "dtTetap.kode" => "required|unique:master_schedules,kode,{$this->pass['editId']},id,deleted_at,NULL",
            "dtTetap.name" => "required|unique:master_schedules,name,{$this->pass['editId']},id,deleted_at,NULL",
            "dtTetap.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            "dtTetap.type" => "",
            "dtTetap.day_work.regular" => "required",
            "dtTetap.day_work.lembur" => "",
        ];
    }
    // end edit tetap


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
        if($this->pass['type'] == "tetap"){
            $this->genDataEditTetap();
        }else{
            $this->genDataEditRotasi();
        }
        // dd($this->all());
    }

    public function genDataEditTetap()
    {
        $this->dtTetap = $this->masterScheduleRepo->getByKey($this->pass['editId'])->toArray();
        unset(
            $this->dtTetap['id'],
            $this->dtTetap['created_at'],
            $this->dtTetap['updated_at'],
            $this->dtTetap['deleted_at']
        );
    }

    public function genDataEditRotasi()
    {
        $this->dtRotasi = $this->masterScheduleRepo->getByKey($this->pass['editId'])->toArray();
        $this->dtRotasi['checkin_time'] = Carbon::parse($this->dtRotasi['checkin_time'])->format('H:i');
        $this->dtRotasi['work_time'] = Carbon::parse($this->dtRotasi['work_time'])->format('H:i');
        $this->dtRotasi['checkin_deadline_time'] = Carbon::parse($this->dtRotasi['checkin_deadline_time'])->format('H:i');
        $this->dtRotasi['checkout_time'] = Carbon::parse($this->dtRotasi['checkout_time'])->format('H:i');
        unset(
            $this->dtRotasi['id'],
            $this->dtRotasi['created_at'],
            $this->dtRotasi['updated_at'],
            $this->dtRotasi['deleted_at']
        );
    }

    public $pass;
    public function render()
    {
        return view('schedule.schedule_edit_'.$this->pass['type']);
    }
}
