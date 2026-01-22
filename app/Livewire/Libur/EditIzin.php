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
        $this->validate();
        if(!$this->form['data_employee_id']){
            $this->addError('employee_invalid', 'Ketik dan Pilih karyawan yang tersedia, anda belum memilih karyawan yang tersedia');
            return;
        }

        $employeeId = (int) $this->form['data_employee_id'];
        $from = Carbon::parse($this->form['from'])->format('Y-m-d H:i:s');
        $to   = Carbon::parse($this->form['to'])->format('Y-m-d H:i:s');
        $overlap = DataIzin::query()
            ->where('id', '!=', $this->pass['id'])
            ->where('data_employee_id', $employeeId)
            ->whereIn('status', ['Proses', 'Disetujui'])
            ->where(function ($q) use ($from, $to) {
                $q->where('from', '<=', $to)
                    ->where('to',   '>=', $from);
            })
            ->exists();
        if ($overlap) {
            $this->addError('form.from', 'Tanggal izin bertabrakan dengan pengajuan izin yang sudah ada.');
            $this->addError('form.to',   'Tanggal izin bertabrakan dengan pengajuan izin yang sudah ada.');
            return;
        }

        $this->form['approved_by'] = $this->dataEmployeeRepo->pengawasCheck($this->form['data_employee_id']);

        $from = Carbon::parse($this->form['from']);
        $to = Carbon::parse($this->form['to']);
        if($from > $to){
            $this->addError('tgl_range_invalid', 'Rentang tanggal tidak valid, pastikan dari rendah ke tinggi.');
            return;
        }

        $this->form['status'] = "Disetujui";
        $dtEdit['old_file'] = $this->form['old_file'];
        unset($this->form['old_file']);
        $dtEdit['form'] = $this->form;
        $dtEdit['id'] = $this->pass['id'];

        if($this->dataLiburIzinRepo->update($dtEdit)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Perubahan data berhasil disimpan.']);
            $this->initEdit();
            // $this->form['bukti'] = null;
            // $this->query = "";
            // $this->results = [];
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
            "form.bukti" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10240",
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

    public function initEdit()
    {
        $this->form = $this->dataLiburIzinRepo->getByCol('id', $this->pass['id']);
        $this->form['from'] = Carbon::parse($this->form['from'])->format('Y-m-d H:i');
        $this->form['to'] = Carbon::parse($this->form['to'])->format('Y-m-d H:i');
        $this->form['old_file'] = $this->form['bukti'];
        $this->query = $this->form['data_employees']['name'];
        unset(
            $this->form['id'],
            $this->form['created_at'],
            $this->form['updated_at'],
            $this->form['deleted_at'],
            $this->form['data_employees'],
            $this->form['bukti'],
        );
    }

    public $izinList;
    public function mount()
    {
        $this->izinList = $this->dataLiburIzinRepo->getIzinList();
        $this->initEdit();
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_izin_edit');
    }
}
