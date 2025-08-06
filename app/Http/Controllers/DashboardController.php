<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\TenagaKerja;
use App\Models\Kemiskinan;
use App\Models\GiniRasio;
use App\Models\PendudukJateng;
use App\Models\PendudukUmur;
use App\Imports\PendudukUmurImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenduduk = Penduduk::sum('jumlah_penduduk');
        $totalTenagaKerja = TenagaKerja::sum('jumlah_angkatan_kerja');
        $totalKemiskinan = Kemiskinan::sum('jumlah_penduduk_miskin');
        $rataGiniRasio = GiniRasio::avg('nilai_gini_rasio');

        return view('dashboard.index', compact('totalPenduduk', 'totalTenagaKerja', 'totalKemiskinan', 'rataGiniRasio'));
    }

    public function penduduk()
    {
        $data = Penduduk::orderBy('tahun', 'desc')->orderBy('kecamatan')->get();
        return view('dashboard.penduduk', compact('data'));
    }

    public function pendudukUmur(Request $request)
    {
        
        $tahunList = ['2021', '2022', '2023', '2024'];


        $tahun = $request->get('tahun');
        if (!$tahun || !in_array($tahun, $tahunList)) {
        $tahun = end($tahunList);
}
        $filterUmur = $request->get('umur', []); // Ambil filter umur sebagai array
        
        // Ambil data dari database berdasarkan tahun dan hanya data umur
        $dataUmur = PendudukUmur::where('tahun', $tahun)
            ->select('id', 'umur', 'laki_laki', 'perempuan', 'jumlah')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'umur' => $item->umur,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'jumlah' => $item->jumlah
                ];
            })
            ->toArray();

        // Filter berdasarkan umur jika ada
        if (!empty($filterUmur) && is_array($filterUmur)) {
            $dataUmur = array_filter($dataUmur, function($item) use ($filterUmur) {
                return in_array($item['umur'], $filterUmur);
            });
            $dataUmur = array_values($dataUmur); // Reset array keys
        }

        
        $umurList = [
            '0-4 tahun' => '0-4 tahun',
            '5-9 tahun' => '5-9 tahun',
            '10-14 tahun' => '10-14 tahun',
            '15-19 tahun' => '15-19 tahun',
            '20-24 tahun' => '20-24 tahun',
            '25-29 tahun' => '25-29 tahun',
            '30-34 tahun' => '30-34 tahun',
            '35-39 tahun' => '35-39 tahun',
            '40-44 tahun' => '40-44 tahun',
            '45-49 tahun' => '45-49 tahun',
            '50-54 tahun' => '50-54 tahun',
            '55-59 tahun' => '55-59 tahun',
            '60-64 tahun' => '60-64 tahun',
            '65-69 tahun' => '65-69 tahun',
            '70-74 tahun' => '70-74 tahun',
            '75+ tahun' => '75+ tahun',

        ];
        
        return view('dashboard.penduduk-umur', compact('dataUmur', 'tahun', 'tahunList', 'umurList', 'filterUmur'));
    }

    // Method untuk menambah data umur
    public function tambahDataUmur(Request $request)
    {
        $request->validate([
            'umur' => 'required|string',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer'
        ]);

        try {
            // Hitung total
            $total = $request->laki_laki + $request->perempuan;
            
            // Simpan ke database
            PendudukUmur::create([
                'tahun' => $request->tahun,
                 'umur' => $request->umur, // Gunakan field umur untuk menyimpan umur
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'jumlah' => $total
            ]);
            
            return redirect()->route('penduduk.umur', [
                'tahun' => $request->tahun
            ])->with('success', 'Data berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    // Method untuk edit data umur
    public function editDataUmur(Request $request, $id)
    {
        $request->validate([
            'umur' => 'required|string',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer'
        ]);

        try {
            // Hitung total
            $total = $request->laki_laki + $request->perempuan;
            
            // Update data di database
            $penduduk = PendudukUmur::findOrFail($id);
            $penduduk->update([
                'tahun' => $request->tahun,
                'umur' => $request->umur, // Gunakan field kecamatan untuk menyimpan umur
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'jumlah' => $total
            ]);
            
            return redirect()->route('penduduk.umur', [
                'tahun' => $request->tahun
            ])->with('success', 'Data berhasil diperbarui!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Method untuk hapus data umur
    public function hapusDataUmur($id)
    {
        try {
            // Hapus data dari database
            $penduduk = PendudukUmur::findOrFail($id);
            $penduduk->delete();
            
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function pendudukKecamatan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $filterKecamatan = $request->get('kecamatan', []); // Ambil filter kecamatan sebagai array
        
        // Ambil data dari database berdasarkan tahun dan hanya data kecamatan
        $dataKecamatan = Penduduk::where('tahun', $tahun)
            ->where('kecamatan', 'not like', '%tahun%') // Hanya ambil data yang TIDAK mengandung kata "tahun"
            ->select('id', 'kecamatan', 'laki_laki', 'perempuan', 'jumlah_penduduk as total')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'kecamatan' => $item->kecamatan,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'total' => $item->total
                ];
            })
            ->toArray();

        // Filter berdasarkan kecamatan jika ada
        if (!empty($filterKecamatan) && is_array($filterKecamatan)) {
            $dataKecamatan = array_filter($dataKecamatan, function($item) use ($filterKecamatan) {
                return in_array($item['kecamatan'], $filterKecamatan);
            });
            $dataKecamatan = array_values($dataKecamatan); // Reset array keys
        }

        $tahunList = ['2021', '2022', '2023', '2024'];
        
        return view('dashboard.penduduk-kecamatan', compact('dataKecamatan', 'tahun', 'tahunList', 'filterKecamatan'));
    }

    // Method untuk menambah data kecamatan
    public function tambahDataKecamatan(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer'
        ]);

        try {
            // Hitung total
            $total = $request->laki_laki + $request->perempuan;
            
            // Simpan ke database
            Penduduk::create([
                'tahun' => $request->tahun,
                'kecamatan' => $request->kecamatan,
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'jumlah_penduduk' => $total
            ]);
            
            return redirect()->route('penduduk.kecamatan', [
                'tahun' => $request->tahun
            ])->with('success', 'Data berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    // Method untuk edit data kecamatan
    public function editDataKecamatan(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'tahun' => 'required|integer'
        ]);

        try {
            // Hitung total
            $total = $request->laki_laki + $request->perempuan;
            
            // Update data di database
            $penduduk = Penduduk::findOrFail($id);
            $penduduk->update([
                'tahun' => $request->tahun,
                'kecamatan' => $request->kecamatan,
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'jumlah_penduduk' => $total
            ]);
            
            return redirect()->route('penduduk.kecamatan', [
                'tahun' => $request->tahun
            ])->with('success', 'Data berhasil diperbarui!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Method untuk hapus data kecamatan
    public function hapusDataKecamatan($id)
    {
        try {
            // Hapus data dari database
            $penduduk = Penduduk::findOrFail($id);
            $penduduk->delete();
            
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function importPendudukUmur(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xls,xlsx',
        'tahun' => 'required|numeric'
    ]);

    Excel::import(new PendudukUmurImport($request->tahun), $request->file('file'));

    return redirect()->back()->with('success', 'Data penduduk berdasarkan umur berhasil diimpor.');
}
    public function pendudukSejateng(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $filterProvinsi = $request->get('provinsi', []);

    // Ambil data dari database berdasarkan tahun
    $dataSejateng = PendudukJateng::where('tahun', $tahun)
        ->select('id', 'provinsi', 'pria', 'wanita', 'jumlahwarga as total')
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->id,
                'provinsi' => $item->provinsi,
                'pria' => $item->pria,
                'wanita' => $item->wanita,
                'total' => $item->total
            ];
        })
        ->toArray();

    // Filter jika ada provinsi
    if (!empty($filterProvinsi) && is_array($filterProvinsi)) {
        $dataSejateng = array_filter($dataSejateng, function($item) use ($filterProvinsi) {
            return in_array($item['provinsi'], $filterProvinsi);
        });
        $dataSejateng = array_values($dataSejateng);
    }

    $tahunList = ['2021','2022', '2023', '2024'];

    return view('dashboard.penduduk-sejateng', compact('dataSejateng', 'tahun', 'tahunList', 'filterProvinsi'));
}

public function tambahDataSejateng(Request $request)
{
    $request->validate([
        'provinsi' => 'required|string',
        'pria' => 'required|integer|min:0',
        'wanita' => 'required|integer|min:0',
        'tahun' => 'required|integer'
    ]);

    try {
        $total = $request->pria + $request->wanita;

        PendudukJateng::create([
            'tahun' => $request->tahun,
            'provinsi' => $request->provinsi,
            'pria' => $request->pria,
            'wanita' => $request->wanita,
            'jumlahwarga' => $total
        ]);

        return redirect()->route('penduduk.sejateng', [
            'tahun' => $request->tahun
        ])->with('success', 'Data berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
    }
}

public function editDataSejateng(Request $request, $id)
{
    $request->validate([
        'provinsi' => 'required|string',
        'pria' => 'required|integer|min:0',
        'wanita' => 'required|integer|min:0',
        'tahun' => 'required|integer'
    ]);

    try {
        $total = $request->pria + $request->wanita;

        $data = PendudukJateng::findOrFail($id);
        $data->update([
            'tahun' => $request->tahun,
            'provinsi' => $request->provinsi,
            'pria' => $request->pria,
            'wanita' => $request->wanita,
            'jumlahwarga' => $total
        ]);

        return redirect()->route('penduduk.sejateng', [
            'tahun' => $request->tahun
        ])->with('success', 'Data berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }
}

public function hapusDataSejateng($id)
{
    try {
        $data = PendudukJateng::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}


    public function tenagaKerja()
    {
        $data = TenagaKerja::orderBy('tahun', 'desc')->orderBy('kecamatan')->get();
        return view('dashboard.tenaga-kerja', compact('data'));
    }

    public function kemiskinan()
    {
        $data = Kemiskinan::orderBy('tahun', 'desc')->orderBy('kecamatan')->get();
        return view('dashboard.kemiskinan', compact('data'));
    }

    public function giniRasio()
    {
        $data = GiniRasio::orderBy('tahun', 'desc')->orderBy('kecamatan')->get();
        return view('dashboard.gini-rasio', compact('data'));
    }
} 