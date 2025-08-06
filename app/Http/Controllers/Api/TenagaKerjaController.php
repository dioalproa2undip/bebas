<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TenagaKerja;

class TenagaKerjaController extends Controller
{
    public function index()
    {
        $data = TenagaKerja::all();
        return response()->json($data);
    }
}
