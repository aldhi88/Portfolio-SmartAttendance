<?php

namespace App\Livewire\Lembur;

use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLemburFace;
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

    public function wireSubmit()
    {
        $this->validate();
        $this->form['approved_by'] = $this->dataEmployeeRepo->pengawasCheck($this->form['data_employee_id']);
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
            "form.tanggal" => "required",
            "query" => "required",
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
        "query" => "Nama Karyawan",
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

    public $izinList;
    public function mount()
    {
        $this->form['data_employee_id'] = false;
    }

    public $pass;
    public function render()
    {
        return view('lembur.lembur_create');
    }
}
