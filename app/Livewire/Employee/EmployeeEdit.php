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
use App\Repositories\Interfaces\UserRoleFace;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class EmployeeEdit extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $masterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $userLoginRepository;
    protected $userRoleRepo;
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        MasterScheduleFace $masterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        UserLoginInterface $userLoginRepository,
        UserRoleFace $userRoleRepo
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->masterScheduleRepo = $masterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->userLoginRepository = $userLoginRepository;
        $this->userRoleRepo = $userRoleRepo;
    }

    // insert
    public function wireSubmit()
    {
        $this->validate();
        $dtEmployee = $this->dtForm;
        unset(
            $dtEmployee['id'],
            $dtEmployee['master_schedule_id'],
            $dtEmployee['effective_at'],
            $dtEmployee['expired_at'],
            $dtEmployee['created_at'],
            $dtEmployee['updated_at'],
            $dtEmployee['deleted_at'],
            $dtEmployee['master_schedules'],
            $dtEmployee['user_logins'],
            $dtEmployee['username'],
            $dtEmployee['password'],
            $dtEmployee['role'],
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
            $dtRel[$index]['data_employee_id'] = $this->pass['editId'];
            $dtRel[$index]['master_schedule_id'] = $key;
            $dtRel[$index]['effective_at'] = $this->dtForm['effective_at'][$key];
            $dtRel[$index]['expired_at'] = $this->dtForm['expired_at'][$key];
            $index++;
        }

        $dtLogin['nickname'] = $this->dtForm['name'];
        $dtLogin['username'] = $this->dtForm['username'];
        $dtLogin['user_role_id'] = $this->dtForm['role'];
        if (isset($this->dtForm['password'])) {
            $dtLogin['password'] = Hash::make($this->dtForm['password']);
        }


        // dd($dtEmployee, $dtRel, $dtLogin);

        try {
            DB::transaction(function () use ($dtEmployee, $dtRel, $dtLogin) {
                if (!is_null($this->dtForm['user_login_id'])) {
                    $this->userLoginRepository->update($this->dtForm['user_login_id'], $dtLogin);
                    $this->dataEmployeeRepo->update($this->dtForm['id'], $dtEmployee);
                    $this->relDataEmployeeMasterScheduleRepo->update($this->dtForm['id'], $dtRel);
                } else {
                    $userId = $this->userLoginRepository->create($dtLogin);
                    $dtEmployee['user_login_id'] = $userId;
                    $this->dataEmployeeRepo->update($this->dtForm['id'], $dtEmployee);
                    $this->relDataEmployeeMasterScheduleRepo->update($this->dtForm['id'], $dtRel);
                }
            });
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Perubahan data berhasil disimpan.']);
            $this->genDataEdit();
        } catch (\Throwable $e) {
            dd($e);
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }

    public function checkSchedule($id)
    {
        $this->dtForm['expired_at'][$id] = null;
        $this->dtForm['effective_at'][$id] = null;

        if (isset($this->dtForm['master_schedule_id'][$id])) {
            if ($this->dtForm['master_schedule_id'][$id]) {
                $this->dtForm['effective_at'][$id] = '2025-02-01';
            } else {
                unset(
                    $this->dtForm['master_schedule_id'][$id],
                    $this->dtForm['effective_at'][$id],
                    $this->dtForm['expired_at'][$id],
                );
            }
        }
    }

    public function isReady($id): bool
    {
        return !empty($this->dtForm['master_schedule_id'][$id] ?? null)
            && !empty($this->dtForm['effective_at'][$id] ?? null)
            && !empty($this->dtForm['expired_at'][$id] ?? null);
    }

    public function resetTglSelesai($id)
    {
        if (!empty($this->dtForm['expired_at'][$id])) {
            $expired   = Carbon::parse($this->dtForm['expired_at'][$id]);
            $effective = Carbon::parse($this->dtForm['effective_at'][$id]);

            if ($expired->lt($effective)) {
                $this->dtForm['expired_at'][$id] = '';
            }
        }
    }

    public function updated($property)
    {
        $base = Str::beforeLast($property, '.');
        $id   = Str::afterLast($property, '.');
        if ($base === 'dtForm.effective_at') {
            $this->resetTglSelesai($id);
        }
    }

    public $dtForm = [];
    public function rules()
    {
        return [
            "dtForm.id" => "required|numeric|unique:data_employees,id,{$this->pass['editId']},id,deleted_at,NULL",
            "dtForm.number" => "required|unique:data_employees,number,{$this->pass['editId']},id,deleted_at,NULL",
            "dtForm.name" => "required",
            "dtForm.master_organization_id" => "required",
            "dtForm.master_position_id" => "required",
            "dtForm.master_location_id" => "required",
            "dtForm.master_function_id" => "required",
            "dtForm.master_schedule_id" => "required",
            "dtForm.status" => "required",
            "dtForm.effective_at" => "",
            "dtForm.expired_at" => "",
            "dtForm.username" => "required|unique:user_logins,username,{$this->dtForm['user_login_id']},id,deleted_at,NULL",
            "dtForm.password" => $this->dtForm['user_logins'] ? 'nullable' : 'required',

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
        "dtForm.status" => "Status",
        "dtForm.username" => "Username Login",
        "dtForm.password" => "Password Login",
    ];

    protected $messages = [
        "dtForm.master_schedule_id.required" => ":attribute wajib dipilih minimal 1 jadwal.",
    ];

    public $dtEdit;
    public function mount()
    {
        $this->dtEdit['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dtEdit['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dtEdit['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dtEdit['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dtEdit['schedule'] = $this->masterScheduleRepo->getAll()->toArray();
        $this->dtEdit['roles'] = $this->userRoleRepo
            ->getAll()
            ->filter(fn($role) => $role->name !== 'Super User')
            ->values() // reset index agar array rapi
            ->toArray();
        $this->genDataEdit();
    }

    public function genDataEdit()
    {
        $this->dtForm = $this->dataEmployeeRepo->getByKey($this->pass['editId'])->toArray();
        $this->dtForm['username'] = "";
        $this->dtForm['username'] = null;
        $this->dtForm['role'] = null;
        if ($this->dtForm['user_logins']) {
            $this->dtForm['username'] = $this->dtForm['user_logins']['username'];
            $this->dtForm['role'] = $this->dtForm['user_logins']['user_roles']['id'];
        }

        // dd($this->dtForm);
        $scheduleId = [];
        $effectiveAt = [];
        $expiredAt = [];
        if ($this->dtForm['master_schedules']) {
            foreach ($this->dtForm['master_schedules'] as $key => $value) {
                $scheduleId[$value['id']] = true;
                $effectiveAt[$value['id']] = null;
                $expiredAt[$value['id']] = null;

                if ($value['pivot']['effective_at']) {
                    $effectiveAt[$value['id']] = Carbon::parse($value['pivot']['effective_at'])->format('Y-m-d');
                }
                if ($value['pivot']['expired_at']) {
                    $expiredAt[$value['id']] = Carbon::parse($value['pivot']['expired_at'])->format('Y-m-d');
                }
            }
        }

        $this->dtForm['master_schedule_id'] = $scheduleId;
        $this->dtForm['effective_at'] = $effectiveAt;
        $this->dtForm['expired_at'] = $expiredAt;
        if ($this->dtForm['status'] == 'Belum Aktif') {
            $this->dtForm['status'] = "Aktif";
        }
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_edit');
    }
}
