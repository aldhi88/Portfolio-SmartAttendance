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
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        MasterScheduleFace $masterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        UserLoginInterface $userLoginRepository
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
        );

        $index = 0;
        foreach ($this->dtForm['master_schedule_id'] as $key => $value) {
            $dtRel[$index]['data_employee_id'] = $this->pass['editId'];
            $dtRel[$index]['master_schedule_id'] = $key;
            $dtRel[$index]['effective_at'] = $this->dtForm['effective_at'][$key];
            $dtRel[$index]['expired_at'] = $this->dtForm['expired_at'][$key];
            $dtRel[$index]['created_at'] = Carbon::now();
            $dtRel[$index]['updated_at'] = Carbon::now();
            $index++;
        }

        $dtLogin['nickname'] = $this->dtForm['name'];
        $dtLogin['username'] = $this->dtForm['username'];
        if(isset($this->dtForm['password'])){
            $dtLogin['password'] = Hash::make($this->dtForm['password']);
        }


        // dd($dtEmployee, $dtRel, $dtLogin);

        try {
            DB::transaction(function () use($dtEmployee, $dtRel, $dtLogin) {
                if(!is_null($this->dtForm['user_login_id'])){
                    $this->userLoginRepository->update($this->dtForm['user_login_id'],$dtLogin);
                    $this->dataEmployeeRepo->update($this->dtForm['id'],$dtEmployee);
                    $this->relDataEmployeeMasterScheduleRepo->update($this->dtForm['id'], $dtRel);
                }else{
                    $userId = $this->userLoginRepository->create($dtLogin);
                    $dtEmployee['user_login_id'] = $userId;
                    $this->dataEmployeeRepo->update($this->dtForm['id'],$dtEmployee);
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

        if(isset($this->dtForm['master_schedule_id'][$id])){
            if($this->dtForm['master_schedule_id'][$id]){
                $this->dtForm['effective_at'][$id] = date('Y-m-d');
            }else{
                unset(
                    $this->dtForm['master_schedule_id'][$id],
                    $this->dtForm['effective_at'][$id],
                    $this->dtForm['expired_at'][$id],
                );
            }
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
            "dtForm.password" => $this->dtForm['user_logins']? 'nullable':'required',

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
        $this->genDataEdit();
    }

    public function genDataEdit()
    {
        $this->dtForm = $this->dataEmployeeRepo->getByKey($this->pass['editId'])->toArray();
        $this->dtForm['username'] = "";
        if($this->dtForm['user_logins']){
            $this->dtForm['username'] = $this->dtForm['user_logins']['username'];
        }

        // dd($this->dtForm);
        $scheduleId = [];
        $effectiveAt = [];
        $expiredAt = [];
        if($this->dtForm['master_schedules']){
            foreach ($this->dtForm['master_schedules'] as $key => $value) {
                $scheduleId[$value['id']] = true;
                $effectiveAt[$value['id']] = null;
                $expiredAt[$value['id']] = null;

                if($value['pivot']['effective_at']){
                    $effectiveAt[$value['id']] = Carbon::parse($value['pivot']['effective_at'])->format('Y-m-d');
                }
                if($value['pivot']['expired_at']){
                    $expiredAt[$value['id']] = Carbon::parse($value['pivot']['expired_at'])->format('Y-m-d');
                }
            }
        }

        $this->dtForm['master_schedule_id'] = $scheduleId;
        $this->dtForm['effective_at'] = $effectiveAt;
        $this->dtForm['expired_at'] = $expiredAt;
        // dd($this->dtForm);
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_edit');
    }
}
