<?php

namespace App\Livewire\Report;

use App\Helpers\PublicHelper;
use App\Repositories\DataAttendanceClaimRepo;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportAbsen extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $userLoginRepository;

    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        UserLoginInterface $userLoginRepository,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->userLoginRepository = $userLoginRepository;
    }

    public $lemburIn;
    public $lemburOut;
    public $date;
    // claim proses
    public function wireSubmitClaim($employeeId)
    {
        if (is_null($this->lemburIn) && is_null($this->lemburOut)) {
            $this->addError('error', 'Waktu masuk dan pulang harus diisi.');
            return;
        }

        $carbonLemburIn  = Carbon::parse($this->lemburIn);
        $carbonLemburOut = Carbon::parse($this->lemburOut);
        $carbonDate = Carbon::parse($this->date);

        if (!$carbonLemburIn->isSameDay($carbonDate)) {
            $this->addError(
                'error',
                'Periksa tanggal claim, anda memilih tanggal yang salah.'
            );
            return;
        }

        if ($carbonLemburIn->greaterThanOrEqualTo($carbonLemburOut)) {
            $this->addError(
                'error',
                'Waktu pulang lembur harus lebih besar dari waktu masuk lembur.'
            );
            return;
        }

        $now = now()->toDateTimeString();
        $data = [
            [
                'data_employee_id' => $employeeId,
                'created_by' => Auth::id(),
                'absen_date' => $this->date,
                'time' => $this->lemburIn,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'data_employee_id' => $employeeId,
                'created_by' => Auth::id(),
                'absen_date' => $this->date,
                'time' => $this->lemburOut,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        if (DataAttendanceClaimRepo::bulkInsert($data)) {
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

    public function updated($property)
    {
        if ($property == 'filter.thisMonth' || $property == 'filter.thisYear') {
            $this->setRangeDefault();
        }

        if ($property == 'filter.start_value' || $property == 'filter.end_value') {
            $this->setRangeOnChange();
        }
    }

    public function setRangeDefault()
    {
        // set min max input date range default
        $this->filter['min_start'] = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->startOfMonth()->format('Y-m-d');
        $this->filter['max_start'] = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->endOfMonth()->format('Y-m-d');
        $this->filter['min_end'] = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->startOfMonth()->format('Y-m-d');
        $this->filter['max_end'] = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->endOfMonth()->format('Y-m-d');

        // jika bulan dan tahun sekarang, set date end nya sampai tanggal sekarang
        if ($this->filter['thisMonth'] == date('m') && $this->filter['thisYear'] == date('Y')) {
            $this->filter['max_start'] = Carbon::now()->format('Y-m-d');
            $this->filter['max_end'] = Carbon::now()->format('Y-m-d');
        }

        $this->filter['start_value'] = $this->filter['min_start'];
        $this->filter['end_value'] = $this->filter['max_end'];
    }

    public function setRangeOnChange()
    {
        $this->filter['min_end'] = $this->filter['start_value'];
        $this->filter['max_start'] = $this->filter['end_value'];
    }

    public function setRangeOnReload()
    {
        $this->setRangeDefault();
        // jika ada url val start
        if (request()->query('start')) {
            $this->filter['min_end'] = request()->query('start');
        }

        // jika ada url val end
        if (request()->query('end')) {
            $this->filter['max_start'] = request()->query('end');
        }

        if (request()->query('start') && request()->query('end')) {
            $this->filter['start_value'] = request()->query('start');
            $this->filter['end_value'] = request()->query('end');
        }
    }

    public function getTglCol()
    {
        $start = Carbon::parse($this->filter['start_value']);
        $end = Carbon::parse($this->filter['end_value']);

        if (request()->query('start') && request()->query('end')) {
            $this->filter['start_value'] = request()->query('start');
            $this->filter['end_value'] = request()->query('end');
            $start = Carbon::parse(request()->query('start'));
            $end = Carbon::parse(request()->query('end'));
        }

        $dates = collect();
        while ($start->lte($end)) {
            $dates->push([
                'col_date' => $start->format('d'),
                'col_day' => PublicHelper::hariIndoByEng($start->format('l')),
            ]);

            $start->addDay();
        }

        $this->dt['tglCol'] = $dates->toArray();
        $this->thisMonthLabel = $this->dt['indoMonthList'][$this->filter['thisMonth']];
    }

    public $dt;
    public $filter;
    public $thisMonthLabel;
    public function mount()
    {
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dt['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();
        $this->dt['orderList'] = PublicHelper::orderList();

        $this->filter['master_organization_id'] = request()->query('master_organization_id', null);
        $this->filter['order'] = request()->query('order', 0);
        $this->filter['master_position_id'] = request()->query('master_position_id', null);
        $this->filter['name'] = request()->query('name', null);
        $this->filter['org_label'] = collect($this->dt['organization'])->firstWhere('id', $this->filter['master_organization_id'])['name'] ?? null;
        $this->filter['pos_label'] = collect($this->dt['position'])->firstWhere('id', $this->filter['master_position_id'])['name'] ?? null;
        $this->filter['order_label'] = $this->dt['orderList'][$this->filter['order']];
        $this->filter['thisMonth'] = request()->query('month', date('m'));
        $this->filter['thisYear'] = request()->query('year', date('Y'));

        $this->setRangeOnReload();
        $this->getTglCol();
        // dd($this->all());
    }

    public $pass;
    public function render()
    {
        return view('report.report_absen');
    }
}
