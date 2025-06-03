<?php

namespace App\Livewire\Libur;

use App\Helpers\PublicHelper;
use App\Repositories\Interfaces\DataLiburFace;
use Carbon\Carbon;
use Livewire\Component;

class TglMerah extends Component
{
    protected $dataLiburRepo;
    public function boot(
        DataLiburFace $dataLiburRepo,
    )
    {
        $this->dataLiburRepo = $dataLiburRepo;
    }

    public function addLibur($tgl)
    {
        if($this->dataLiburRepo->insert(['date' => $tgl])){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Tanggal '.Carbon::parse($tgl)->format('d-m-Y').' dijadikan tanggal merah.']);
            $this->genCalendar();
            $this->getHariLibur();
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public function delLibur($tgl)
    {
        if($this->dataLiburRepo->remove($tgl)){
            $this->dispatch('alert', data:['type' => 'success',  'message' => 'Tanggal '.Carbon::parse($tgl)->format('d-m-Y').' dibatalkan.']);
            $this->genCalendar();
            return;
        }
        $this->dispatch('alert', data:['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator.']);
    }

    public $calendars;
    public function genCalendar()
    {
        $calendars = [];
        foreach ($this->monthNames as $month => $monthLabel) {
            $monthNum = (int) $month;
            $start = \Carbon\Carbon::create($this->year, $monthNum, 1);
            $end = $start->copy()->endOfMonth();
            $startDayOfWeek = $start->dayOfWeek;
            $totalDays = $end->day;

            $dates = [];
            $date = $start->copy();
            while ($date->lte($end)) {
                $dates[] = [
                    'day' => $date->day,
                    'dow' => $date->dayOfWeek,
                    'date' => $date->format('Y-m-d'),
                ];
                $date->addDay();
            }

            $calendars[] = [
                'label' => $month.'-'.$monthLabel,
                'start_dow' => $startDayOfWeek,
                'dates' => $dates,
            ];
            $this->calendars = $calendars;
        }

        $this->getHariLibur();

    }

    public $hariLibur;
    public function getHariLibur()
    {
        $this->hariLibur = $this->dataLiburRepo->getByDate(null, $this->year);
    }

    public $year;
    public $monthNames;
    public function mount()
    {
        $this->year = now()->year;
        $this->monthNames = PublicHelper::indoMonthList();
        $this->genCalendar();
    }

    public $pass;
    public function render()
    {
        return view('libur.libur_tgl_merah');
    }
}
