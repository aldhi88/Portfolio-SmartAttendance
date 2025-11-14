<?php

namespace App\Livewire\Employee;

use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use App\Repositories\Interfaces\UserRoleFace;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class EmployeeData extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $userLoginRepository;
    protected $masterSchedulesRepo;
    protected $userRoleRepo;

    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        UserLoginInterface $userLoginRepository,
        MasterScheduleFace $masterSchedulesRepo,
        UserRoleFace $userRoleRepo,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->userLoginRepository = $userLoginRepository;
        $this->masterSchedulesRepo = $masterSchedulesRepo;
        $this->userRoleRepo = $userRoleRepo;
    }

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }

    public $multipleId;
    public $processType;
    #[On('setProcessMultipleId')]
    public function setProcessMultipleId($ids, $process)
    {
        $this->multipleId = $ids;
        $this->processType = $process;
    }
    public function multipleProcess()
    {
        try {
            if($this->processType==='set-aktif'){
                DB::transaction(function () {
                    $this->dataEmployeeRepo->setStatusMultiple($this->multipleId, 'Aktif');
                });
            }
            if($this->processType==='set-nonaktif'){
                DB::transaction(function () {
                    $this->dataEmployeeRepo->setStatusMultiple($this->multipleId, 'Tidak Aktif');
                });
            }
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirm');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Proses berhasil dilakukan.']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }
    // end delete section

    public function getNewId()
    {
        $q = DataEmployee::max('id');
        $this->dt['newId'] = $q+1;
    }

    public $dt;
    public function mount()
    {
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dt['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dt['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dt['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dt['jadwal'] = $this->masterSchedulesRepo->getAll()->toArray();
        $this->dt['jadwal'] = $this->masterSchedulesRepo->getAll()->toArray();
        $this->dt['roles'] = $this->userRoleRepo->getAll()->toArray();
        $this->getNewId();
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_data');
    }
}
