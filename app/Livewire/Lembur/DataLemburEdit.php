<?php

namespace App\Livewire\Lembur;

use App\Models\DataLembur;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
use Carbon\Carbon;
use Livewire\Component;

class DataLemburEdit extends Component
{
    protected $dataEmployeeRepo;
    protected $dataLemburRepo;
    public function boot(
        DataEmployeeFace $dataEmployeeRepo,
        DataLemburFace $dataLemburRepo,
    )
    {
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
        // $this->validate();
        // $this->form['approved_by'] = $this->dataEmployeeRepo->pengawasCheck($this->form['data_employee_id']);
        // $this->form['status'] = "Disetujui";

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

        $dtEdit['id'] = $this->pass['id'];
        $dtEdit['form'] = $this->form;

        // dd($dtEdit);

        if($this->dataLemburRepo->update($dtEdit)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Perubahan data berhasil disimpan.']);
            $this->initEdit();
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

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

    public function initEdit()
    {
        $this->form = $this->dataLemburRepo->getByCol('id', $this->pass['id']);
        $this->query = $this->form['data_employees']['name'];
        $this->pass['format'] = DataLembur::formatOrg($this->form['data_employees']['master_organization_id']);
        $this->queryPekerjaan = $this->form['pekerjaan'];
        unset(
            $this->form['id'],
            $this->form['created_at'],
            $this->form['updated_at'],
            $this->form['deleted_at'],
            $this->form['data_employees'],
        );
        // dd($this->form);
    }

    public function mount()
    {
        $this->initEdit();
        $this->genTtdLembur();
    }

    public $pass;
    public function render()
    {
        return view('lembur.lembur_edit');
    }
}
