<?php

namespace App\Livewire\Employee;

use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataScheduleBebasFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use App\Repositories\Interfaces\UserRoleFace;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class EmployeeEdit extends Component
{
    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $masterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $userLoginRepository;
    protected $userRoleRepo;
    protected $dataScheduleBebasRepo;
    public function boot(
        MasterOrganizationFace $masterOrganizationRepo,
        MasterPositionFace $masterPositionRepo,
        MasterLocationFace $masterLocationRepo,
        MasterFunctionFace $masterFunctionRepo,
        MasterScheduleFace $masterScheduleRepo,
        DataEmployeeFace $dataEmployeeRepo,
        RelDataEmployeeMasterScheduleFace $relDataEmployeeMasterScheduleRepo,
        UserLoginInterface $userLoginRepository,
        UserRoleFace $userRoleRepo,
        DataScheduleBebasFace $dataScheduleBebasRepo
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->masterScheduleRepo = $masterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->userLoginRepository = $userLoginRepository;
        $this->userRoleRepo = $userRoleRepo;
        $this->dataScheduleBebasRepo = $dataScheduleBebasRepo;
    }

    // insert
    public function wireSubmit()
    {
        // dd($this->all());
        $this->validate();

        // Ambil semua ID yang dicentang dari activedSchedules
        $selectedIds = collect($this->activedSchedules['id'] ?? []);

        // Validasi jika user pilih lebih dari 1 jadwal
        if ($selectedIds->count() > 1) {
            // dd($selectedIds);
            $activeSchedules = [];

            foreach ($selectedIds as $id) {
                $effective = $this->activedSchedules['effective_at'][$id] ?? null;
                $expired   = $this->activedSchedules['expired_at'][$id] ?? null;

                if ($effective) {
                    $start = $effective;
                    $end   = $expired ? $expired : null;

                    $activeSchedules[] = [
                        'id'    => $id,
                        'start' => $start,
                        'end'   => $end,
                    ];
                }
            }

            // Validasi: hanya boleh satu jadwal tanpa tanggal selesai
            $activeWithoutEnd = collect($activeSchedules)->filter(fn($s) => is_null($s['end']));
            if ($activeWithoutEnd->count() > 1) {
                $this->addError('multi_schedule', 'Tanggal Selesai belum ditentukan, hanya 1 jadwal boleh tanpa tanggal selesai.');
                return;
            }

            // Validasi tanggal overlap
            $itemsToCheck = [];
            foreach ($activeSchedules as $item) {
                $start = Carbon::parse($item['start']);
                $end   = !is_null($item['end']) ? Carbon::parse($item['end']) : Carbon::parse('9999-12-31');

                if (!is_null($item['end']) && $start > $end) {
                    $this->addError('multi_schedule', 'Tanggal tidak valid, silakan periksa lagi.');
                    return;
                }

                $itemsToCheck[] = [
                    'start' => $start,
                    'end'   => $end,
                    'id'    => $item['id'] ?? null,
                ];
            }

            // Urutkan berdasarkan start date
            usort($itemsToCheck, fn($a, $b) => $a['start']->timestamp <=> $b['start']->timestamp);
            // Periksa overlap antar jadwal
            // dd($this->all(),$itemsToCheck);
            for ($i = 0; $i < count($itemsToCheck) - 1; $i++) {
                if ($itemsToCheck[$i + 1]['start'] <= $itemsToCheck[$i]['end']) {
                    $this->addError('multi_schedule', 'Tanggal tidak valid, ada jadwal yang overlap.');
                    return;
                }
            }
        }

        // Bangun data relasi pivot baru dari activedSchedules
        $dtRel = [];
        foreach ($this->activedSchedules['id'] as $index => $scheduleId) {
            $dtRel[$index] = [
                'data_employee_id'   => $this->pass['editId'],
                'master_schedule_id' => $scheduleId,
                'effective_at'       => $this->activedSchedules['effective_at'][$scheduleId] ?? null,
                'expired_at'         => $this->activedSchedules['expired_at'][$scheduleId] ?? null,
            ];
        }

        // Build ulang data login dan employee
        $dtEmployee = collect($this->dtForm)->except([
            'id',
            'master_schedules',
            'created_at',
            'updated_at',
            'deleted_at',
            'user_logins',
            'username',
            'password',
            'role'
        ])->toArray();

        $dtLogin['nickname']     = $this->dtForm['name'];
        $dtLogin['username']     = $this->dtForm['username'];
        $dtLogin['user_role_id'] = $this->dtForm['role'];

        if (isset($this->dtForm['password'])) {
            $dtLogin['password'] = Hash::make($this->dtForm['password']);
        }

        $flatten = collect($this->dtScheduleBebas)
            ->flatMap(function ($items) {
                return $items; // gabungkan semua array dari setiap schedule
            })
            ->values()
            ->toArray();


        try {
            DB::transaction(function () use ($dtEmployee, $dtRel, $dtLogin, $flatten) {
                // dd($dtEmployee, $dtRel, $dtLogin, $flatten);
                if (!is_null($this->dtForm['user_login_id'])) {
                    $this->userLoginRepository->update($this->dtForm['user_login_id'], $dtLogin);
                } else {
                    $userId = $this->userLoginRepository->create($dtLogin);
                    $dtEmployee['user_login_id'] = $userId;
                }
                $this->dataEmployeeRepo->update($this->dtForm['id'], $dtEmployee);
                $this->relDataEmployeeMasterScheduleRepo->update($this->dtForm['id'], $dtRel);
                $this->dataScheduleBebasRepo->bulkCreate($flatten);
            });

            $this->dispatch('alert', data: ['type' => 'success', 'message' => 'Perubahan data berhasil disimpan.']);
            // session()->flash('success', 'Perubahan data berhasil disimpan.');
            $this->dispatch('reloadPage');
        } catch (\Throwable $e) {
            dd($e);
            $this->dispatch('alert', data: ['type' => 'error', 'message' => 'Terjadi masalah, hubungi administrator.']);
        }
    }


    public array $selectedJadwal = [];
    public function wireSubmitScheduleBebas()
    {
        dd($this->all());
    }

    public function isReady($id): bool
    {
        return in_array($id, $this->activedSchedules['id'] ?? [])
            && !empty($this->activedSchedules['effective_at'][$id] ?? null)
            && !empty($this->activedSchedules['expired_at'][$id] ?? null);
    }

    public function resetTglSelesai($id)
    {
        if (!empty($this->dtForm['expired_at'][$id])) {
            $expired   = Carbon::parse($this->dtForm['expired_at'][$id]);
            $effective = Carbon::parse($this->dtForm['effective_at'][$id]);

            if ($expired->lt($effective)) {
                $this->dtForm['expired_at'][$id] = '';
            }
        }
    }

    public function toggleSchedule($id)
    {
        // Jika ID sudah ada (berarti di-UNcheck)
        if (in_array($id, $this->activedSchedules['id'])) {
            $this->activedSchedules['id'] = array_values(array_diff(
                $this->activedSchedules['id'],
                [$id]
            ));
            unset($this->activedSchedules['effective_at'][$id]);
            unset($this->activedSchedules['expired_at'][$id]);
        } else {
            // Jika ID belum ada (berarti di-check)
            $this->activedSchedules['id'][] = $id;
            if (!isset($this->activedSchedules['effective_at'][$id])) {
                $this->activedSchedules['effective_at'][$id] = '2025-02-01';
            }
            if (!isset($this->activedSchedules['expired_at'][$id])) {
                $this->activedSchedules['expired_at'][$id] = null;
            }
        }
    }


    public function updated($property)
    {

        $base = Str::beforeLast($property, '.');
        $id   = Str::afterLast($property, '.');

        if ($base === 'dtForm.effective_at') {
            $this->resetTglSelesai($id);
        }
        // dump($property, $this->all());
        $isBebas = collect($this->dtEdit['schedule'])->contains(fn($i) => $i['id'] == $id && $i['type'] === 'Bebas');
        if ($isBebas && ($base === 'activedSchedules.effective_at' || $base === 'activedSchedules.expired_at')) {
            if (
                !empty($this->activedSchedules['effective_at'][$id]) &&
                !empty($this->activedSchedules['expired_at'][$id])
            ) {
                $this->genScheduleTime($id);
            }
            // else {
            //     unset($this->dtScheduleBebas[$id]);
            // }
        }

    }

    public function genScheduleTime($id)
    {
        $start = Carbon::parse($this->activedSchedules['effective_at'][$id])->startOfDay();
        $end   = Carbon::parse($this->activedSchedules['expired_at'][$id])->startOfDay();

        // Ambil data existing jika ada
        $existing = collect($this->dtScheduleBebas[$id] ?? [])
            ->mapWithKeys(fn($item) => [$item['tanggal'] => $item])
            ->toArray();

        $result = [];

        // Loop seluruh tanggal dari effective sampai expired
        foreach (CarbonPeriod::create($start, $end) as $date) {
            $tgl = $date->format('Y-m-d');

            if (isset($existing[$tgl])) {
                //Jika tanggal sudah ada → pertahankan data lama
                $result[] = $existing[$tgl];
            } else {
                //Jika tanggal baru → buat default kosong
                $result[] = [
                    'master_schedule_id' => $id,
                    'tanggal' => $tgl,
                    'day_work' => [
                        'checkin_time' => null,
                        'work_time' => null,
                        'checkin_deadline_time' => null,
                        'checkout_time' => null,
                        'checkout_deadline_time' => null,
                    ],
                ];
            }
        }

        // Simpan hasil merge ke property utama
        $this->dtScheduleBebas[$id] = $result;
        // $this->modalListDates = $result;
        // dd($this->all());
    }

    public function applyTemplate($scheduleId, $index, $json)
    {
        if (!$json) return;
        $data = json_decode($json, true);

        // Pastikan key day_work sudah ada
        $this->dtScheduleBebas[$scheduleId][$index]['day_work'] = [
            'checkin_time' => $data['checkin_time'] ?? null,
            'work_time' => $data['work_time'] ?? null,
            'checkin_deadline_time' => $data['checkin_deadline_time'] ?? null,
            'checkout_time' => $data['checkout_time'] ?? null,
            'checkout_deadline_time' => $data['checkout_deadline_time'] ?? null,
        ];

        // dump($this->all());
    }

    public $dtForm = [];
    public function rules()
    {
        return [
            "dtForm.id" => "required|numeric|unique:data_employees,id,{$this->pass['editId']},id,deleted_at,NULL",
            "dtForm.number" => "required|unique:data_employees,number,{$this->pass['editId']},id,deleted_at,NULL",
            "dtForm.name" => "required",
            "dtForm.master_organization_id" => "required",
            "dtForm.master_position_id" => "required",
            "dtForm.master_location_id" => "required",
            "dtForm.master_function_id" => "required",
            "activedSchedules.id" => "required",
            "dtForm.status" => "required",
            "dtForm.effective_at" => "",
            "dtForm.expired_at" => "",
            "dtForm.username" => "required|unique:user_logins,username,{$this->dtForm['user_login_id']},id,deleted_at,NULL",
            "dtForm.password" => $this->dtForm['user_logins'] ? 'nullable' : 'required',

        ];
    }
    // // end insert tetap

    public $validationAttributes = [
        "dtForm.id" => "Kode",
        "dtForm.number" => "Nomor",
        "dtForm.name" => "Nama",
        "dtForm.master_organization_id" => "Perusahaan",
        "dtForm.master_position_id" => "Jabatan",
        "dtForm.master_location_id" => "Lokasi",
        "dtForm.master_function_id" => "Fungsi",
        "dtForm.master_schedule_id" => "Jadwal Kerja",
        "dtForm.status" => "Status",
        "dtForm.username" => "Username Login",
        "dtForm.password" => "Password Login",
    ];

    protected $messages = [
        "dtForm.master_schedule_id.required" => ":attribute wajib dipilih minimal 1 jadwal.",
    ];

    public $dtEdit;
    public function mount()
    {
        $this->dtEdit['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dtEdit['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dtEdit['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dtEdit['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dtEdit['schedule'] = $this->masterScheduleRepo->getAll()->toArray();
        $this->dtEdit['roles'] = $this->userRoleRepo
            ->getAll()
            ->filter(fn($role) => $role->name !== 'Super User')
            ->values() // reset index agar array rapi
            ->toArray();
        $this->genDataEdit();
    }

    public $activedSchedules = [
        'id' => [],
        'effective_at' => [],
        'expired_at' => [],
    ];
    public $dtScheduleBebas = [];
    public $modalTimeTemplate = [];
    public function genDataEdit()
    {
        $this->dtForm = $this->dataEmployeeRepo->getByKey($this->pass['editId'])->toArray();
        $this->dtForm['username'] = "";
        $this->dtForm['username'] = null;
        $this->dtForm['role'] = null;
        if ($this->dtForm['user_logins']) {
            $this->dtForm['username'] = $this->dtForm['user_logins']['username'];
            $this->dtForm['role'] = $this->dtForm['user_logins']['user_roles']['id'];
        }

        if ($this->dtForm['master_schedules']) {
            foreach ($this->dtForm['master_schedules'] as $key => $value) {
                $this->activedSchedules['id'][] = $value['id'];
                $this->activedSchedules['effective_at'][$value['id']] =
                    $value['pivot']['effective_at']
                    ? Carbon::parse($value['pivot']['effective_at'])->format('Y-m-d')
                    : null;

                $this->activedSchedules['expired_at'][$value['id']] =
                    $value['pivot']['expired_at']
                    ? Carbon::parse($value['pivot']['expired_at'])->format('Y-m-d')
                    : null;

                if ($value['pivot']['effective_at']) {
                    $effectiveAt[$value['id']] = Carbon::parse($value['pivot']['effective_at'])->format('Y-m-d');
                }
                if ($value['pivot']['expired_at']) {
                    $expiredAt[$value['id']] = Carbon::parse($value['pivot']['expired_at'])->format('Y-m-d');
                }

                if ($value['type'] === 'Bebas') {
                    $dataTgl = collect($value['data_schedule_bebas'])
                        ->map(function ($item) {
                            return collect($item)->except(['id', 'created_at', 'updated_at', 'deleted_at'])->toArray();
                        })
                        ->sortBy('tanggal')
                        ->values();
                    $this->dtScheduleBebas[$value['id']] = $dataTgl;
                }
            }
        }

        if ($this->dtForm['status'] == 'Belum Aktif') {
            $this->dtForm['status'] = "Aktif";
        }
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_edit');
    }
}
