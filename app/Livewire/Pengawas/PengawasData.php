<?php

namespace App\Livewire\Pengawas;

use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class PengawasData extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $dataEmployeeRepo;

    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        DataEmployeeFace $dataEmployeeRepo,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
    }

    #[On('addMember')]
    public function addMember($ids)
    {
        $data['pengawas'] = $this->dt['pengawas_selected_id'];
        $data['member'] = $ids;
        try {
            $this->dataEmployeeRepo->addMember($data);
            $this->dispatch('reloadDT',data:'dtTableEmployee');
            $this->dispatch('reloadDT',data:'dtTableMember');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Anggota baru berhasil ditambahkan.']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }
    #[On('delMember')]
    public function delMember($ids)
    {
        $data['pengawas'] = $this->dt['pengawas_selected_id'];
        $data['member'] = $ids;
        try {
            $this->dataEmployeeRepo->delMember($data);
            $this->dispatch('reloadDT',data:'dtTableEmployee');
            $this->dispatch('reloadDT',data:'dtTableMember');
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Anggota berhasil dihapus.']);
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }


    public $dt;
    public $noPengawas = false;

    public function mount()
    {
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dt['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dt['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dt['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dt['pengawas'] = $this->dataEmployeeRepo->getPengawas();
        $this->dt['pengawas_selected_name'] = '';
        $this->dt['pengawas_selected_id'] = '';

        if(Auth::user()->is_pengawas){ //jika pengawas
            if (!request()->has('pengawas') || is_null(request()->get('pengawas'))) {
                return redirect()->to('/pengawas/index?pengawas='.Auth::user()->data_employees->id);
            }
            $this->dt['pengawas_selected_id'] = request()->get('pengawas');
            $this->dt['pengawas_selected_name'] = Auth::user()->data_employees->name;
        }else{
            if (request()->has('pengawas')) {
                if( !is_null(request()->get('pengawas'))){
                    $this->dt['pengawas_selected_id'] = request()->get('pengawas');
                }else{
                    return redirect()->route('pengawas.index');
                }
            }


        }

        // dd($this->dt);
    }

    public $pass;
    public function render()
    {
        return view('pengawas.pengawas_data');
    }
}
