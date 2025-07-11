<?php

namespace App\Livewire\Employee;

use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class EmployeeCreate extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $masterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $userLoginRepository;
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        MasterScheduleFace $masterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        UserLoginInterface $userLoginRepository,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->masterScheduleRepo = $masterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->userLoginRepository = $userLoginRepository;
    }

    // insert
    public function wireSubmit()
    {
        // dd($this->dtForm);
        // $this->validate();
        $dtEmployee = $this->dtForm;
        $dtEmployee['status'] = 'Aktif';
        // dd($this->dtForm);
        unset(
            $dtEmployee['master_schedule_id'],
            $dtEmployee['effective_at'],
            $dtEmployee['expired_at'],
            $dtEmployee['username'],
            $dtEmployee['password'],
        );


        $selectedIds = collect($this->dtForm['master_schedule_id'])
            ->filter(fn($val) => $val === true)
            ->keys();

        if ($selectedIds->count() > 1) {
            $activeSchedules = [];

            foreach ($selectedIds as $id) {
                $effective = $this->dtForm['effective_at'][$id] ?? null;
                $expired   = $this->dtForm['expired_at'][$id] ?? null;

                if ($effective) {
                    $start = $effective;
                    $end = $expired ? $expired : null;

                    $activeSchedules[] = [
                        'id' => $id,
                        'start' => $start,
                        'end' => $end,
                    ];
                }
            }

            $activeWithoutEnd = collect($activeSchedules)->filter(fn($s) => is_null($s['end']));

            if ($activeWithoutEnd->count() > 1) {
                $this->addError('multi_schedule', 'Tanggal Selesai belum ditentukan, hanya jadwal aktif yang boleh tanpa Tanggal Selesai');
                return;
            }

            $itemsToCheck = [];

            foreach ($activeSchedules as $item) {
                $start = Carbon::parse($item['start']);
                $end = array_key_exists('end', $item) && !is_null($item['end'])
                    ? Carbon::parse($item['end'])
                    : Carbon::parse('9999-12-31');
                // Jika end ada (bukan null), cek apakah start <= end
                if (array_key_exists('end', $item) && !is_null($item['end'])) {
                    if ($start > $end) {
                        $this->addError('multi_schedule', 'Tanggal tidak valid, silahkan periksa lagi');
                        return;
                    }
                }

                $itemsToCheck[] = [
                    'start' => $start,
                    'end' => $end,
                    'id' => $item['id'] ?? null,
                ];
            }

            // Urutkan berdasarkan start
            usort($itemsToCheck, function ($a, $b) {
                return $a['start']->timestamp <=> $b['start']->timestamp;
            });

            // Cek overlap antar jadwal
            for ($i = 0; $i < count($itemsToCheck) - 1; $i++) {
                $currentEnd = $itemsToCheck[$i]['end'];
                $nextStart = $itemsToCheck[$i + 1]['start'];

                if ($nextStart <= $currentEnd) {
                    $this->addError('multi_schedule', 'Tanggal tidak valid, silahkan periksa lagi');
                    return;
                }
            }


        }

        $index = 0;
        foreach ($this->dtForm['master_schedule_id'] as $key => $value) {
            $dtRel[$index]['data_employee_id'] = $dtEmployee['id'];
            $dtRel[$index]['master_schedule_id'] = $key;
            $dtRel[$index]['effective_at'] = $this->dtForm['effective_at'][$key];
            $dtRel[$index]['expired_at'] = $this->dtForm['expired_at'][$key];
            $dtRel[$index]['created_at'] = Carbon::now();
            $dtRel[$index]['updated_at'] = Carbon::now();
            $index++;
        }


        $dtLogin['nickname'] = $this->dtForm['name'];
        $dtLogin['username'] = $this->dtForm['username'];
        $dtLogin['password'] = Hash::make($this->dtForm['password']);

        // dd($dtEmployee, $dtRel, $dtLogin);

        try {
            DB::transaction(function () use ($dtEmployee, $dtRel, $dtLogin) {
                $userId = $this->userLoginRepository->create($dtLogin);
                if ($userId) {
                    $dtEmployee['user_login_id'] = $userId;
                    $this->dataEmployeeRepo->createForm($dtEmployee);
                    $this->relDataEmployeeMasterScheduleRepo->insert($dtRel);
                } else {
                    throw new \Exception('Gagal membuat user login.');
                }
            });
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('dtForm');
            $this->initForm();
        } catch (\Throwable $e) {
            // dd($e);
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }

    // public function updated($property, $value)
    // {
    //     foreach ($value as $key => $item) {
    //         if(!$item){
    //             unset($va);
    //         }
    //     }

    //     dd($property, $value, $this->dtForm['master_schedule_id']);
    // }

    public function checkSchedule($id)
    {
        $this->dtForm['expired_at'][$id] = null;
        $this->dtForm['effective_at'][$id] = null;

        if ($this->dtForm['master_schedule_id'][$id]) {
            $this->dtForm['effective_at'][$id] = date('Y-m-d');
        } else {
            unset(
                $this->dtForm['master_schedule_id'][$id],
                $this->dtForm['effective_at'][$id],
                $this->dtForm['expired_at'][$id],
            );
        }
    }

    public $dtForm = [];
    public function rules()
    {
        return [
            "dtForm.id" => "required|numeric|unique:data_employees,id,NULL,id,deleted_at,NULL",
            "dtForm.number" => "required|unique:data_employees,number,NULL,id,deleted_at,NULL",
            "dtForm.name" => "required",
            "dtForm.master_organization_id" => "required",
            "dtForm.master_position_id" => "required",
            "dtForm.master_location_id" => "required",
            "dtForm.master_function_id" => "required",
            "dtForm.master_schedule_id" => "required",
            "dtForm.effective_at" => "",
            "dtForm.expired_at" => "",
            "dtForm.username" => "required|unique:user_logins,username,NULL,id,deleted_at,NULL",
            "dtForm.password" => "required",

        ];
    }
    // // end insert tetap

    public $validationAttributes = [
        "dtForm.id" => "Kode",
        "dtForm.number" => "Nomor",
        "dtForm.name" => "Nama",
        "dtForm.master_organization_id" => "Perusahaan",
        "dtForm.master_position_id" => "Jabatan",
        "dtForm.master_location_id" => "Lokasi",
        "dtForm.master_function_id" => "Fungsi",
        "dtForm.master_schedule_id" => "Jadwal Kerja",
        "dtForm.username" => "Username Login",
        "dtForm.password" => "Password Login",
    ];

    protected $messages = [
        "dtForm.master_schedule_id.required" => ":attribute wajib dipilih minimal 1 jadwal.",
    ];

    public $dtCreate;
    public function mount()
    {
        $this->dtCreate['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dtCreate['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dtCreate['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dtCreate['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dtCreate['schedule'] = $this->masterScheduleRepo->getAll()->toArray();
        $this->initForm();
    }

    public function initForm()
    {
        $this->dtForm['password'] = "123456";
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_create');
    }
}
