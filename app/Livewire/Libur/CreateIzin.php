<?php

namespace App\Livewire\Libur;

use App\Models\DataIzin;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataLiburIzinFace;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateIzin extends Component
{
    use WithFileUploads;

    protected $dataEmployeeRepo;
    protected $dataLiburIzinRepo;
    public function boot(
        DataEmployeeFace $dataEmployeeRepo,
        DataLiburIzinFace $dataLiburIzinRepo,
    ) {
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->dataLiburIzinRepo = $dataLiburIzinRepo;
    }

    public function wireSubmit()
    {
        $this->validate();

        if (!$this->form['data_employee_id']) {
            $this->addError('employee_invalid', 'Ketik dan Pilih karyawan yang tersedia, anda belum memilih karyawan yang tersedia');
            return;
        }

        // cek overlap tanggal
        $employeeId = (int) $this->form['data_employee_id'];
        $from = Carbon::parse($this->form['from'])->format('Y-m-d H:i:s');
        $to   = Carbon::parse($this->form['to'])->format('Y-m-d H:i:s');
        $overlap = DataIzin::query()
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
        if ($from > $to) {
            $this->addError('tgl_range_invalid', 'Rentang tanggal tidak valid, pastikan dari rendah ke tinggi.');
            return;
        }

        $this->form['status'] = "Disetujui";

        // dd($this->all());

        if ($this->dataLiburIzinRepo->create($this->form)) {
            $this->dispatch('alert', data: ['type' => 'success',  'message' => 'Data baru berhasil ditambahkan.']);
            $this->reset('form');
            $this->form['bukti'] = null;
            $this->query = "";
            $this->results = [];
            return;
        }

        $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $form = [];
    public function rules()
    {
        return [
            "form.data_employee_id" => "required",
            "form.jenis" => "required",
            "form.from" => "required",
            "form.to" => "required|date|after_or_equal:form.from",
            "form.desc" => "nullable",
            "form.bukti" => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10240",
            "query" => "required",
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

    public $izinList;
    public function mount()
    {
        $this->izinList = $this->dataLiburIzinRepo->getIzinList();
        $this->form['data_employee_id'] = false;
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_izin_create');
    }
}
