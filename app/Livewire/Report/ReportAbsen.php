<?php

namespace App\Livewire\Report;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use Carbon\Carbon;
use Livewire\Attributes\On;
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


    public function getTglCol()
    {
        $dates = collect();
        while ($this->start->lte($this->end)) {
            $dates->push([
                'col_date' => $this->start->format('d-M-Y'),
                'col_day' => PublicHelper::hariIndoByEng($this->start->format('l')),
            ]);

            $this->start->addDay();
        }

        $this->dt['tglCol'] = $dates->toArray();
        // dd($this->all());
    }

    public $dt;
    public $start;
    public $end;
    public $month;
    public $year;
    public function mount()
    {
        $this->dt['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dt['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dt['indoMonthList'] = PublicHelper::indoMonthList();

        $this->month = request()->query('month');
        $this->year = request()->query('year');

        if ($this->month && $this->year) {
            $this->start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
            if ($this->month == Carbon::now()->format('m') && $this->year == Carbon::now()->format('Y')) {
                $this->end = Carbon::now();
            } else {
                $this->end = Carbon::create($this->year, $this->month, 1)->endOfMonth();
            }
        } else {
            $this->month = date('m');
            $this->year = date('Y');
            $this->start = Carbon::now()->startOfMonth();
            $this->end = Carbon::now();
        }


        $this->getTglCol();
    }

    public $pass;
    public function render()
    {
        return view('report.report_absen');
    }
}
