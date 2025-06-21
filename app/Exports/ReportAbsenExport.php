<?php

namespace App\Exports;

use App\Helpers\PublicHelper;
use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataLiburFace;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportAbsenExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $year, $month, $org, $pos;
    protected $dataLiburRepo;

    public function __construct($year, $month, $org, $pos, DataLiburFace $dataLiburRepo)
    {
        $this->year = $year;
        $this->month = $month;
        $this->org = $org;
        $this->pos = $pos;
        $this->dataLiburRepo = $dataLiburRepo;
    }

    public function styles(Worksheet $sheet)
    {
        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Wrap text pada baris header
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$highestColumn}4")->getAlignment()->setWrapText(true);

        return [];
    }

    public function view(): View
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();

        if ($this->year == now()->year && $this->month == now()->month) {
            $end = now()->startOfDay();
        } else {
            $end = $start->copy()->endOfMonth();
        }

        $tglCol = [];

        while ($start->lte($end)) {
            $tglCol[] = [
                'col_date' => $start->format('d'),
                'col_day' => $start->locale('id')->translatedFormat('l'),
            ];
            $start->addDay();
        }

        $data = DataEmployee::query()
            ->select([
                'data_employees.id',
                'data_employees.name',
                'data_employees.status',
                'data_employees.master_organization_id',
                'data_employees.master_position_id',
                'data_employees.master_location_id',
                'data_employees.master_function_id',
            ])
            ->with([
                'master_organizations:id,name',
                'master_locations:id,name',
                'master_functions:id,name',
                'master_positions:id,name',
                'log_attendances' => function ($q) {
                    $q->select('data_employee_id', 'time')
                        ->whereYear('time', $this->year)
                        ->whereMonth('time', $this->month);
                },
                'data_izins' => function ($q) {
                    $q->select('id', 'data_employee_id', 'jenis', 'from', 'to', 'desc')
                        ->where('status', 'Disetujui');
                },
            ])
            ->where('status', 'Aktif')
            ->has('master_schedules')
            ->orderBy('id', 'asc')
        ;

        if ($this->org) {
            $data->where('master_organization_id', $this->org);
        }
        if ($this->pos) {
            $data->where('master_position_id', $this->pos);
        }

        $dateInMonth = PublicHelper::dateInMonth($this->month, $this->year);
        $tglMerah = $this->dataLiburRepo->getByDate($this->month, $this->year);

        // Isi absensi ke setiap row
        $data = $data->get()->map(function ($row) use ($dateInMonth, $tglMerah) {
            $row->absensi = PublicHelper::getDtAbsen(
                $dateInMonth,
                $row->log_attendances->toArray(),
                $row->master_schedules->toArray(),
                $row->data_izins->toArray(),
                $tglMerah
            );
            return $row;
        })->toArray();

        $manajer = DataEmployee::whereHas('user_logins', function ($query) {
                $query->where('user_role_id', 500);
            })
            ->pluck('name')
            ->first()?? 'Manajer belum dipilih';

        return view('report.export.report_export_excel', [
            'data' => $data,
            'tglCol' => $tglCol,
            'year' => $this->year,
            'month' => $this->month,
            'manajer' => $manajer
        ]);
    }
}
