<?php

namespace App\Livewire\Lembur;

use App\Helpers\PublicHelper;
use App\Models\DataLembur;
use App\Repositories\Interfaces\DataLemburFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\LogGpsRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class LemburData extends Component
{
    protected $dataLemburRepo;
    protected $masterOrganizationRepo;
    public function boot(
        DataLemburFace $dataLemburRepo,
        MasterOrganizationFace $masterOrganizationRepo,
    ) {
        $this->dataLemburRepo = $dataLemburRepo;
        $this->masterOrganizationRepo = $masterOrganizationRepo;
    }

    public $dt = [];

    public $lemburIn;
    public $lemburOut;
    // claim proses
    public function wireSubmitClaim($employeeId)
    {
        if (is_null($this->lemburIn) && is_null($this->lemburOut)) {
            $this->addError('error', 'Waktu masuk dan pulang harus diisi.');
            return;
        }

        $lemburIn  = Carbon::parse($this->lemburIn);
        $lemburOut = Carbon::parse($this->lemburOut);

        if ($lemburIn->greaterThanOrEqualTo($lemburOut)) {
            $this->addError(
                'error',
                'Waktu pulang lembur harus lebih besar dari waktu masuk lembur.'
            );
            return;
        }

        $data = [
            [
                'data_employee_id' => $employeeId,
                'created_by' => Auth::id(),
                'time' => $lemburIn,
            ],
            [
                'data_employee_id' => $employeeId,
                'created_by' => Auth::id(),
                'time' => $lemburOut,
            ],
        ];

        if(LogGpsRepo::bulkInsert($data)){
            $this->dispatch('closeModal', id: 'modalConfirmClaim');
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->addError(
            'error',
            'Terjadi kesalahan di server.'
        );
    }
    public function claimRules()
    {
        return [
            "lembur.time_in" => "required",
            "lembur.time_out" => "required",
        ];
    }
    public $validationAttributes = [
        "lembur.time_in" => "Waktu Masuk Lembur",
        "lembur.time_out" => "Waktu Pulang Lembur",
    ];

    // delete section
    public $deleteId;
    #[On('setDeleteId')]
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }
    public function wireDelete()
    {
        if ($this->dataLemburRepo->delete($this->deleteId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDelete');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data berhasil dihapus.']);
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $deleteMultipleId;
    #[On('setDeleteMultipleId')]
    public function setDeleteMultipleId($ids)
    {
        $this->deleteMultipleId = $ids;
    }
    public function deleteMultiple()
    {
        if ($this->dataLemburRepo->deleteMultiple($this->deleteMultipleId)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmDeleteMultiple');
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Semua data yang dipilih berhasil dihapus.']);
            return;
        }
        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }
    // end delete section

    public $prosesId;
    #[On('setProsesId')]
    public function setProsesId($id)
    {
        $this->prosesId = $id;
    }
    public function wireProses($proses)
    {
        $user = Auth::user();
        $employeeId = $user->data_employees?->id;

        $lembur = DataLembur::find($this->prosesId);

        if (!$lembur) {
            $this->dispatch('alert', data: [
                'type' => 'error',
                'message' => 'Data tidak ditemukan.'
            ]);
            return;
        }

        $data = [];

        // SUPERUSER
        if ($user->is_superuser) {

            if ($lembur->pengawas1 !== null) {
                $data['status_pengawas1'] = $proses;
            }

            if ($lembur->pengawas2 !== null) {
                $data['status_pengawas2'] = $proses;
            }
        }
        // PENGAWAS BIASA
        else {

            if ($lembur->pengawas1 === $employeeId) {
                $data['status_pengawas1'] = $proses;
            }

            if ($lembur->pengawas2 === $employeeId) {
                $data['status_pengawas2'] = $proses;
            }

            if (empty($data)) {
                $this->dispatch('alert', data: [
                    'type' => 'error',
                    'message' => 'Anda tidak berhak memproses data ini.'
                ]);
                return;
            }
        }

        if ($this->dataLemburRepo->process($this->prosesId, $data)) {
            $this->dispatch('reloadDT', data: 'dtTable');
            $this->dispatch('closeModal', id: 'modalConfirmSetuju');
            $this->dispatch('alert', data: [
                'type' => 'success',
                'message' => 'Data berhasil diproses.'
            ]);
            return;
        }

        $this->dispatch('alert', data: [
            'type' => 'error',
            'message' => 'Terjadi masalah, hubungi administrator.'
        ]);
    }

    public function mount()
    {
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
    }

    public $pass;
    public function render()
    {
        return view('lembur.lembur_data');
    }
}
