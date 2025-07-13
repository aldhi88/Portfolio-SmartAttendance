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
            "dtRotasi.type" => "",
            "dtRotasi.day_work.start_date" => "required",
            "dtRotasi.day_work.work_day" => "required|numeric|min:1",
            "dtRotasi.day_work.off_day" => "required|numeric|min:1",

            "dtRotasi.day_work.time.*.name" => "required",
            "dtRotasi.day_work.time.*.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtRotasi.day_work.time.*.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtRotasi.day_work.time.*.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtRotasi.day_work.time.*.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtRotasi.day_work.time.*.checkout_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
        ];
    }
    // end edit rotasi


    // edit tetap
    public function wireSubmitTetap()
    {
        $this->validate($this->rulesTetap());
        $this->dtTetap['type'] = $this->pass['type'];
        $this->dtTetap['day_work']['day'] = array_keys(array_filter($this->dtTetap['day_work']['day'] ?? []));
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
            "dtTetap.day_work.time.checkin_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtTetap.day_work.time.work_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtTetap.day_work.time.checkin_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtTetap.day_work.time.checkout_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtTetap.day_work.time.checkout_deadline_time" => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/'],
            "dtTetap.day_work.day" => "required",
            "dtTetap.type" => "",
        ];
    }
    // end edit tetap


    public $validationAttributes = [
        "dtTetap.kode" => "Kode Jadwal",
        "dtTetap.name" => "Nama Jadwal",
        "dtTetap.day_work.time.checkin_time" => "Checkin Start",
        "dtTetap.day_work.time.work_time" => "Check-In Ontime",
        "dtTetap.day_work.time.checkin_deadline_time" => "Check-In End",
        "dtTetap.day_work.time.checkout_time" => "Checkout Start",
        "dtTetap.day_work.time.checkout_deadline_time" => "Checkout End",
        "dtTetap.day_work.day" => "Hari Kerja",

        "dtRotasi.kode" => "Kode Jadwal",
        "dtRotasi.name" => "Nama Jadwal",
        "dtRotasi.day_work.start_date" => "Tanggal Mulai Rotasi",
        "dtRotasi.day_work.work_day" => "Jumlah Hari Kerja",
        "dtRotasi.day_work.off_day" => "Jumlah Hari Off",

        "dtRotasi.day_work.time.*.name" => "Nama Shift",
        "dtRotasi.day_work.time.*.checkin_time" => "Checkin Start",
        "dtRotasi.day_work.time.*.work_time" => "Check-In Ontime",
        "dtRotasi.day_work.time.*.checkin_deadline_time" => "Check-In End",
        "dtRotasi.day_work.time.*.checkout_time" => "Checkout Start",
        "dtRotasi.day_work.time.*.checkout_deadline_time" => "Checkout End",
    ];

    protected $messages = [
        "dtTetap.day_work.day.required" => ":attribute wajib dipilih minimal 1 hari.",
        'dtTetap.day_work.time.checkin_time.regex' => 'Format tidak valid (ex: 08:00:00)',
        'dtTetap.day_work.time.work_time.regex' => 'Format tidak valid (ex: 08:00:00)',
        'dtTetap.day_work.time.checkin_deadline_time.regex' => 'Format tidak valid (ex: 08:00:00)',
        'dtTetap.day_work.time.checkout_time.regex' => 'Format tidak valid (ex: 08:00:00)',
        'dtTetap.day_work.time.checkout_deadline_time.regex' => 'Format tidak valid (ex: 08:00:00)',

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
        $daywork = $this->dtTetap['day_work']['day'];
        $this->dtTetap['day_work']['day'] = [];
        foreach ($daywork as $key => $value) {
            $this->dtTetap['day_work']['day'][$value] = true;
        }
        unset(
            $this->dtTetap['id'],
            $this->dtTetap['created_at'],
            $this->dtTetap['updated_at'],
            $this->dtTetap['deleted_at']
        );
    }

    public $jlhShift;
    public function addShift()
    {
        $this->jlhShift+=1;
        $this->dtRotasi['day_work']['time'][$this->jlhShift-1] = [
                'name' => '',
                'checkin_time' => '',
                'work_time' => '',
                'checkin_deadline_time' => '',
                'checkout_time' => '',
                'checkout_deadline_time' => '',
            ];
    }
    public function delShift($index)
    {
        unset($this->dtRotasi['day_work']['time'][$index]);
        $this->dtRotasi['day_work']['time'] = array_values($this->dtRotasi['day_work']['time']);
        $this->jlhShift -= 1;
    }
    public function genDataEditRotasi()
    {
        $this->dtRotasi = $this->masterScheduleRepo->getByKey($this->pass['editId'])->toArray();
        $this->jlhShift = count($this->dtRotasi['day_work']['time']);
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
