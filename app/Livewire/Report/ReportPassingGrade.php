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
use Livewire\Component;

class ReportPassingGrade extends Component
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
        if ($this->filter['thisMonth'] == date('m') && $this->filter['thisYear'] == date('Y')) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        } else {
            $start = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->startOfMonth();
            $end = Carbon::create($this->filter['thisYear'], $this->filter['thisMonth'], 1)->endOfMonth();
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
        $this->filter['thisMonth'] = request()->query('month', date('m'));
        $this->filter['thisYear'] = request()->query('year', date('Y'));
        $this->filter['master_organization_id'] = request()->query('master_organization_id', null);
        $this->filter['master_position_id'] = request()->query('master_position_id', null);
        $this->filter['name'] = request()->query('name', null);
        $this->filter['org_label'] = collect($this->dt['organization'])->firstWhere('id', $this->filter['master_organization_id'])['name'] ?? null;
        $this->filter['pos_label'] = collect($this->dt['position'])->firstWhere('id', $this->filter['master_position_id'])['name'] ?? null;


        $this->getTglCol();
        // dd($this->all());
    }

    public $pass;
    public function render()
    {
        return view('report.report_rank');
    }
}
