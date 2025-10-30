<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataLov;
use Illuminate\Http\Request;

class LovController extends Controller
{
    public function getByKey(Request $request) {

        $data = DataLov::where('key', $request->key)->get()->toArray();
        $response = [
            'status' => "OK",
            'body' => $data
        ];

        return response()->json($response, 201);
    }
}
