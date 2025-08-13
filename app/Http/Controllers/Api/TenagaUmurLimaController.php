<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\_Tenagakerja;

class TenagaUmurLimaController extends Controller
{
    public function index(Request $request)
    {
         $query = _Tenagakerja::query();
        if($request->filled('tahun')){
            $query->where('tahun', $request->tahun);

        }
        $data = $query->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'tpak' => 'required|string',
            'tkk' => 'required|string',
            'tpt' => 'required|string',
            'bekerja_laki_laki' => 'required|integer|min:0',
            'bekerja_perempuan' => 'required|integer|min:0',
            'pengangguran_laki_laki' => 'required|integer|min:0',
            'pengangguran_perempuan' => 'required|integer|min:0',
        ]);

        // Hitung jumlah total angkatan kerja
        $validated['jumlah'] =
            $validated['bekerja_laki_laki'] +
            $validated['bekerja_perempuan'] +
            $validated['pengangguran_laki_laki'] +
            $validated['pengangguran_perempuan'];

        $data = _Tenagakerja::create($validated);

        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        $data = _Tenagakerja::find($id);
        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
