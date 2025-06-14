<?php

namespace App\Livewire\Libur;

use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EditIzin extends Component
{
    use WithFileUploads;

    protected $dataEmployeeRepo;
    protected $dataLiburIzinRepo;
    public function boot(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburIzinFace $dataLiburIzinRepo,
    )
    {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLiburIzinRepo = $dataLiburIzinRepo;
    }


    public function wireSubmit()
    {
        // dd($this->form);
        $this->validate();
        if(!$this->form['data_employee_id']){
            $this->addError('employee_invalid', 'Ketik dan Pilih karyawan yang tersedia, anda belum memilih karyawan yang tersedia');
            return;
        }

        $from = Carbon::parse($this->form['from']);
        $to = Carbon::parse($this->form['to']);
        if($from > $to){
            $this->addError('tgl_range_invalid', 'Rentang tanggal tidak valid, pastikan dari rendah ke tinggi.');
            return;
        }

        if (isset($this->form['bukti'])) {
            $file = $this->form['bukti'];
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti', $uniqueName, 'public');
            $this->form['bukti'] = $uniqueName;
        }

        $this->form['status'] = "Disetujui";

        if($this->dataLiburIzinRepo->create($this->form)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('form');
            $this->form['bukti'] = null;
            $this->query = "";
            $this->results = [];
            return;
        }

        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);

    }

    public $form = [];
    public function rules()
    {
        return [
            "form.data_employee_id" => "required",
            "query" => "required",
            "form.jenis" => "required",
            "form.from" => "required",
            "form.to" => "required",
            "form.desc" => "nullable",
            "form.bukti" => "nullable|file|max:10240",
        ];
    }
    public $validationAttributes = [
        "form.data_employee_id" => "Nama Karyawan",
        "form.jenis" => "Jenis Izin",
        "form.from" => "Tanggal Waktu",
        "form.to" => "Tanggal Waktu",
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

    public function mount()
    {
        $this->form = $this->dataLiburIzinRepo->getByCol('id', $this->pass['id']);
        $this->form['from'] = Carbon::parse($this->form['from'])->format('Y-m-d H:i');
        $this->form['to'] = Carbon::parse($this->form['to'])->format('Y-m-d H:i');
        $this->query = $this->form['data_employees']['name'];
        unset(
            $this->form['id'],
            $this->form['created_at'],
            $this->form['updated_at'],
            $this->form['deleted_at'],
            $this->form['data_employees'],
        );
        // dd($this->form);

        // $this->form['data_employee_id'] = false;
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_izin_edit');
    }
}
