<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LogGpsFace;
use Illuminate\Http\Request;

class LogGpsController extends Controller
{
    protected $logGpsRepo;
    public function __construct(LogGpsFace $logGpsRepo)
    {
        $this->logGpsRepo = $logGpsRepo;
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'data_employee_id' => 'required|integer',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0|max:200',
        ]);

        $data = $this->logGpsRepo->store($data);

        return response()->json([
            'message' => 'Lokasi berhasil disimpan',
            'data'    => $data,
        ], 201);
    }
}
