<?php

namespace App\Livewire\Employee;

use App\Models\DataEmployee;
use App\Repositories\Interfaces\DataEmployeeFace;
use App\Repositories\Interfaces\DataScheduleBebasFace;
use App\Repositories\Interfaces\MasterFunctionFace;
use App\Repositories\Interfaces\MasterLocationFace;
use App\Repositories\Interfaces\MasterOrganizationFace;
use App\Repositories\Interfaces\MasterPositionFace;
use App\Repositories\Interfaces\MasterScheduleFace;
use App\Repositories\Interfaces\RelDataEmployeeMasterScheduleFace;
use App\Repositories\Interfaces\UserLoginInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Livewire\Component;

class EmployeeCreate extends Component
{
    use WithFileUploads;

    protected $masterOrganizationRepo;
    protected $masterPositionRepo;
    protected $masterLocationRepo;
    protected $masterFunctionRepo;
    protected $masterScheduleRepo;
    protected $dataEmployeeRepo;
    protected $relDataEmployeeMasterScheduleRepo;
    protected $userLoginRepository;
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
        DataScheduleBebasFace $dataScheduleBebasRepo,
    ) {
        $this->masterOrganizationRepo = $masterOrganizationRepo;
        $this->masterPositionRepo = $masterPositionRepo;
        $this->masterLocationRepo = $masterLocationRepo;
        $this->masterFunctionRepo = $masterFunctionRepo;
        $this->masterScheduleRepo = $masterScheduleRepo;
        $this->dataEmployeeRepo = $dataEmployeeRepo;
        $this->relDataEmployeeMasterScheduleRepo = $relDataEmployeeMasterScheduleRepo;
        $this->userLoginRepository = $userLoginRepository;
        $this->dataScheduleBebasRepo = $dataScheduleBebasRepo;
    }

    public function wireSubmit()
    {
        $this->validate();

        $dtEmployee = $this->dtForm;
        $dtEmployee['status'] = 'Aktif';

        unset(
            $dtEmployee['username'],
            $dtEmployee['password'],
        );

        $selectedIds = collect($this->activedSchedules['id'] ?? []);

        if ($selectedIds->count() > 1) {
            $activeSchedules = [];

            foreach ($selectedIds as $id) {
                $effective = $this->activedSchedules['effective_at'][$id] ?? null;
                $expired   = $this->activedSchedules['expired_at'][$id] ?? null;

                if ($effective) {
                    $start = $effective;
                    $end = $expired ? $expired : null;

                    $activeSchedules[] = [
                        'id' => $id,
                        'start' => $start,
                        'end' => $end,
                    ];
                }
            }

            $activeWithoutEnd = collect($activeSchedules)->filter(fn($s) => is_null($s['end']));

            if ($activeWithoutEnd->count() > 1) {
                $this->addError('multi_schedule', 'Tanggal Selesai belum ditentukan, hanya jadwal aktif yang boleh tanpa Tanggal Selesai');
                return;
            }

            $itemsToCheck = [];

            foreach ($activeSchedules as $item) {
                $start = Carbon::parse($item['start']);
                $end = array_key_exists('end', $item) && !is_null($item['end'])
                    ? Carbon::parse($item['end'])
                    : Carbon::parse('9999-12-31');
                // Jika end ada (bukan null), cek apakah start <= end
                if (array_key_exists('end', $item) && !is_null($item['end'])) {
                    if ($start > $end) {
                        $this->addError('multi_schedule', 'Tanggal tidak valid, silahkan periksa lagi');
                        return;
                    }
                }

                $itemsToCheck[] = [
                    'start' => $start,
                    'end' => $end,
                    'id' => $item['id'] ?? null,
                ];
            }

            // Urutkan berdasarkan start
            usort($itemsToCheck, function ($a, $b) {
                return $a['start']->timestamp <=> $b['start']->timestamp;
            });

            // Cek overlap antar jadwal
            for ($i = 0; $i < count($itemsToCheck) - 1; $i++) {
                $currentEnd = $itemsToCheck[$i]['end'];
                $nextStart = $itemsToCheck[$i + 1]['start'];

                if ($nextStart <= $currentEnd) {
                    $this->addError('multi_schedule', 'Tanggal tidak valid, silahkan periksa lagi');
                    return;
                }
            }


        }

        $index = 0;
        $dtRel = [];
        foreach ($selectedIds as $scheduleId) {
            $dtRel[$index]['data_employee_id'] = $dtEmployee['id'];
            $dtRel[$index]['master_schedule_id'] = $scheduleId;
            $dtRel[$index]['effective_at'] = $this->activedSchedules['effective_at'][$scheduleId] ?? null;
            $dtRel[$index]['expired_at'] = $this->activedSchedules['expired_at'][$scheduleId] ?? null;
            $dtRel[$index]['created_at'] = Carbon::now();
            $dtRel[$index]['updated_at'] = Carbon::now();
            $index++;
        }


        $dtLogin['nickname'] = $this->dtForm['name'];
        $dtLogin['username'] = $this->dtForm['username'];
        $dtLogin['password'] = Hash::make($this->dtForm['password']);

        $uploadedFiles = [];
        if (!empty($this->dtForm['ttd'])) {
            $ttdFile = $this->dtForm['ttd'];
            $ttdName = uniqid('ttd_', true) . '.' . $ttdFile->extension();

            $uploadedFiles['ttd'] = [
                'file' => $ttdFile,
                'path' => 'employees/ttd/' . $ttdName,
                'name' => $ttdName,
            ];
        }

        if (!empty($this->dtForm['paraf'])) {
            $parafFile = $this->dtForm['paraf'];
            $parafName = uniqid('paraf_', true) . '.' . $parafFile->extension();

            $uploadedFiles['paraf'] = [
                'file' => $parafFile,
                'path' => 'employees/paraf/' . $parafName,
                'name' => $parafName,
            ];
        }

        $flatten = collect($this->dtScheduleBebas)
            ->flatMap(function ($items) {
                return $items;
            })
            ->values()
            ->toArray();

        try {
            DB::transaction(function () use ($dtEmployee, $dtRel, $dtLogin, $flatten, $uploadedFiles) {
                if (count($uploadedFiles) != 0) {
                    foreach ($uploadedFiles as $file) {
                        $file['file']->storeAs(
                            dirname($file['path']),
                            basename($file['path']),
                            'public'
                        );
                    }

                    foreach ($uploadedFiles as $key => $file) {
                        $dtEmployee[$key] = $file['name'];
                    }
                }

                $userId = $this->userLoginRepository->create($dtLogin);
                if ($userId) {
                    $dtEmployee['user_login_id'] = $userId;
                    $this->dataEmployeeRepo->createForm($dtEmployee);
                    $this->relDataEmployeeMasterScheduleRepo->insert($dtRel);
                    if (!empty($flatten)) {
                        $this->dataScheduleBebasRepo->bulkCreate($flatten);
                    }
                } else {
                    throw new \Exception('Gagal membuat user login.');
                }
            });

            session()->flash('success', 'Data karyawan baru berhasil ditambahkan.');
            return redirect()->route('karyawan.index');
        } catch (\Throwable $e) {
            $this->dispatch('alert', data: ['type' => 'error',  'message' => 'Terjadi masalah, hubungi administrator..']);
        }
    }

    public function isReady($id): bool
    {
        return in_array($id, $this->activedSchedules['id'] ?? [])
            && !empty($this->activedSchedules['effective_at'][$id] ?? null)
            && !empty($this->activedSchedules['expired_at'][$id] ?? null);
    }

    public function toggleSchedule($id)
    {
        if (in_array($id, $this->activedSchedules['id'])) {
            $this->activedSchedules['id'] = array_values(array_diff(
                $this->activedSchedules['id'],
                [$id]
            ));
            unset($this->activedSchedules['effective_at'][$id]);
            unset($this->activedSchedules['expired_at'][$id], $this->dtScheduleBebas[$id]);
        } else {
            $this->activedSchedules['id'][] = $id;
            $this->activedSchedules['effective_at'][$id] = date('Y-m-d');
            $this->activedSchedules['expired_at'][$id] = null;
        }
    }

    public function updated($property)
    {
        $parts = explode('.', $property);
        $scheduleId = end($parts);

        if (
            count($parts) === 3
            && $parts[0] === 'activedSchedules'
            && in_array($parts[1], ['effective_at', 'expired_at'])
        ) {
            if (
                !empty($this->activedSchedules['effective_at'][$scheduleId] ?? null)
                && !empty($this->activedSchedules['expired_at'][$scheduleId] ?? null)
                && $this->isScheduleBebas($scheduleId)
            ) {
                $this->genScheduleTime($scheduleId);
            }
        }
    }

    public function genScheduleTime($id)
    {
        $start = Carbon::parse($this->activedSchedules['effective_at'][$id])->startOfDay();
        $end   = Carbon::parse($this->activedSchedules['expired_at'][$id])->startOfDay();

        $existing = collect($this->dtScheduleBebas[$id] ?? [])
            ->mapWithKeys(fn($item) => [$item['tanggal'] => $item])
            ->toArray();

        $result = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $tgl = $date->format('Y-m-d');

            if (isset($existing[$tgl])) {
                $result[] = $existing[$tgl];
            } else {
                $result[] = [
                    'master_schedule_id' => $id,
                    'data_employee_id' => $this->dtForm['id'],
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

        $this->dtScheduleBebas[$id] = $result;
    }

    public function applyTemplate($scheduleId, $index, $json)
    {
        if (!$json) return;

        $data = json_decode($json, true);

        $this->dtScheduleBebas[$scheduleId][$index]['day_work'] = [
            'checkin_time' => $data['checkin_time'] ?? null,
            'work_time' => $data['work_time'] ?? null,
            'checkin_deadline_time' => $data['checkin_deadline_time'] ?? null,
            'checkout_time' => $data['checkout_time'] ?? null,
            'checkout_deadline_time' => $data['checkout_deadline_time'] ?? null,
        ];
    }

    public function isScheduleBebas($scheduleId): bool
    {
        return collect($this->dtCreate['schedule'])
            ->contains(fn($item) => $item['id'] == $scheduleId && $item['type'] === 'Bebas');
    }

    public $dtForm = [];

    public function rules()
    {
        return [
            "dtForm.id" => "required|numeric|unique:data_employees,id,NULL,id,deleted_at,NULL",
            "dtForm.number" => "required|unique:data_employees,number,NULL,id,deleted_at,NULL",
            "dtForm.name" => "required",
            "dtForm.master_organization_id" => "required",
            "dtForm.master_position_id" => "required",
            "dtForm.master_location_id" => "required",
            "dtForm.master_function_id" => "required",
            "activedSchedules.id" => "required",
            "dtForm.ttd" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
            "dtForm.paraf" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
            "dtForm.username" => "required|unique:user_logins,username,NULL,id,deleted_at,NULL",
            "dtForm.password" => "required",
        ];
    }

    public $validationAttributes = [
        "dtForm.id" => "Kode",
        "dtForm.number" => "Nomor",
        "dtForm.name" => "Nama",
        "dtForm.master_organization_id" => "Perusahaan",
        "dtForm.master_position_id" => "Jabatan",
        "dtForm.master_location_id" => "Lokasi",
        "dtForm.master_function_id" => "Fungsi",
        "activedSchedules.id" => "Jadwal Kerja",
        "dtForm.username" => "Username Login",
        "dtForm.password" => "Password Login",
        "dtForm.ttd" => "File Tanda Tangan",
        "dtForm.paraf" => "File Paraf",
    ];

    protected $messages = [
        "activedSchedules.id.required" => ":attribute wajib dipilih minimal 1 jadwal.",
    ];

    public $dtCreate;
    public $activedSchedules = [
        'id' => [],
        'effective_at' => [],
        'expired_at' => [],
    ];
    public $dtScheduleBebas = [];

    public function mount()
    {
        $this->dtCreate['organization'] = $this->masterOrganizationRepo->getAll()->toArray();
        $this->dtCreate['position'] = $this->masterPositionRepo->getAll()->toArray();
        $this->dtCreate['location'] = $this->masterLocationRepo->getAll()->toArray();
        $this->dtCreate['function'] = $this->masterFunctionRepo->getAll()->toArray();
        $this->dtCreate['schedule'] = $this->masterScheduleRepo->getAll()->toArray();
        $this->initForm();
    }

    public function initForm()
    {
        $this->pass['ttd'] = "Pilih file gambar tanda tangan";
        $this->pass['paraf'] = "Pilih file gambar paraf";
        $this->dtForm['id'] = (DataEmployee::max('id') ?? 0) + 1;
        $this->dtForm['status'] = 'Aktif';
        $this->dtForm['password'] = "123456";
    }

    public $pass;
    public function render()
    {
        return view('employee.employee_create');
    }
}
