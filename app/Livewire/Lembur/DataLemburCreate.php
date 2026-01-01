<?php

namespace App\Livewire\Lembur;

use App\Models\DataLembur;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use Carbon\Carbon;
use Livewire\Component;

class DataLemburCreate extends Component
{
    protected $dataEmployeeRepo;
    protected $dataLemburRepo;
    public function boot(
        DataEmployeeFace $dataEmployeeRepo,
        DataLemburFace $dataLemburRepo,
    ) {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLemburRepo = $dataLemburRepo;
    }

    public $ttd = [];
    public function genTtdLembur()
    {
        $this->ttd['pengawas'] = $this->dataEmployeeRepo->getPengawasLembur();
        $this->ttd['security'] = $this->dataEmployeeRepo->getSecurityLembur();
    }

    public function wireSubmit()
    {
        // dd($this->all());
        $this->validate();
        $workTime = Carbon::parse($this->form['work_time_lembur']);
        $checkoutTime = Carbon::parse($this->form['checkout_time_lembur']);
        $this->form['tanggal'] = $workTime->toDateString(); // Y-m-d
        $this->form['checkin_time_lembur'] = $workTime
            ->copy()
            ->subHours(2)
            ->format('Y-m-d H:i:s');
        $this->form['checkin_deadline_time_lembur'] = $workTime
            ->copy()
            ->addHour()
            ->format('Y-m-d H:i:s');
        $this->form['checkout_deadline_time_lembur'] = $checkoutTime
            ->copy()
            ->addHours(2)
            ->format('Y-m-d H:i:s');

        $this->form['approved_by'] = $this->form['pengawas1'];
        $this->form['status'] = "Disetujui";

        if ($this->dataLemburRepo->create($this->form)) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('form');
            $this->query = "";
            $this->results = [];
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $form = [];
    public function rules()
    {
        $return = [
            "form.data_employee_id" => "required",
            "form.work_time_lembur" => "required",
            "form.checkout_time_lembur" => "required",
            "form.pekerjaan" => "required",
            "form.pengawas1" => "required",
            "form.pengawas2" => "",
            "form.security" => "",
            "form.korlap" => "",

            "query" => "required",
            "queryPekerjaan" => "required",
        ];

        if (!$this->form['data_employee_id']) {
            $return["form.mask"] = "required";
        }

        return $return;
    }
    public function messages()
    {
        return [
            "form.data_employee_id.required" => "Ketik nama karyawan lalu pilih dari list yang muncul",
            "form.mask.required" => "Ketik nama karyawan lalu pilih dari list yang muncul",
        ];
    }
    public $validationAttributes = [
        "form.tanggal" => "Tanggal",
        "form.pekerjaan" => "Pekerjaan",
        "form.pengawas1" => "Pengawas 1",
        "form.work_time_lembur" => "Jam Masuk & Pulang",
        "form.checkout_time_lembur" => "Jam Masuk & Pulang",
        "query" => "Nama Karyawan",
        "queryPekerjaan" => "Pekerjaan",
    ];

    public $query = '';
    public $results = [];
    public function updatedQuery()
    {
        if (strlen($this->query) > 1) {
            $this->results = $this->dataEmployeeRepo->searchByName($this->query);
        }
    }
    public function selectNama($id)
    {
        $item = collect($this->results);
        $name = ($item->where('id', $id)->first())['name'];
        $orgId = ($item->where('id', $id)->first())['master_organization_id'];
        $this->pass['format'] = DataLembur::formatOrg($orgId);
        $this->query = $name;
        $this->form['data_employee_id'] = $id;
        $this->results = [];
    }

    public $queryPekerjaan = '';
    public $resultsPekerjaan = [];
    public function updatedQueryPekerjaan()
    {
        if (strlen($this->queryPekerjaan) > 1) {
            $this->resultsPekerjaan = $this->dataLemburRepo->searchPekerjaan($this->queryPekerjaan);
            $this->form['pekerjaan'] = $this->queryPekerjaan;
        }
    }
    public function selectPekerjaan($val)
    {
        $this->queryPekerjaan = $val;
        $this->form['pekerjaan'] = $val;
        $this->resultsPekerjaan = [];
    }

    public $izinList;
    public function mount()
    {
        $this->form['data_employee_id'] = false;
        $this->genTtdLembur();
        $this->pass['format'] = null;
    }

    public $pass;
    public function render()
    {
        return view('lembur.data_lembur_create');
    }
}
