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
    public function wireDelete()
    {
        dd($this->all());
        try {
            DB::transaction(function () {
                $userLoginId = $this->dataEmployeeRepo->getColValByCol('id', $this->deleteId, 'user_login_id');
                $this->relDataEmployeeMasterScheduleRepo->delByCol('data_employee_id', $this->deleteId);
                $this->dataEmployeeRepo->delete($this->deleteId);
                $this->userLoginRepository->delete($userLoginId);
            });
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDelete');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil dihapus.']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }

    }
    public $deleteMultipleId;
    #[On('setDeleteMultipleId')]
    public function setDeleteMultipleId($ids)
    {
        $this->deleteMultipleId = $ids;
    }
    public function deleteMultiple()
    {
        // dd($this->all());
        try {
            DB::transaction(function () {
                $userLoginId = $this->dataEmployeeRepo->getColValByColMulti('id', $this->deleteMultipleId, 'user_login_id');
                $this->relDataEmployeeMasterScheduleRepo->delByColMulti('data_employee_id', $this->deleteMultipleId);
                $this->dataEmployeeRepo->deleteMulti($this->deleteMultipleId);
                $this->userLoginRepository->deleteMulti($userLoginId);
            });
            $this->dispatch('reloadDT',data:'dtTable');
            $this->dispatch('closeModal',id:'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data berhasil dihapus.']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }
    // end delete section

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
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_data');
    }
}
