<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Models\Penduduk;
use App\Models\_Tenagakerja;

use App\Http\Controllers\PendudukUmurController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Debug route untuk mengecek status login
Route::get('/debug-auth', function () {
    dd([
        'is_logged_in' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all()
    ]);
});

// Debug route untuk mengecek database
Route::get('/debug-db', function () {
    $users = User::all();
    dd([
        'total_users' => $users->count(),
        'users' => $users->toArray(),
        'database_connection' => config('database.default'),
        'database_name' => config('database.connections.mysql.database')
    ]);
});

// Debug route untuk mengecek data penduduk
Route::get('/debug-penduduk', function () {
    $penduduk = Penduduk::all();
    dd([
        'total_penduduk' => $penduduk->count(),
        'penduduk_data' => $penduduk->toArray(),
        'sum_laki_laki' => $penduduk->sum('laki_laki'),
        'sum_perempuan' => $penduduk->sum('perempuan'),
        'sum_total' => $penduduk->sum('jumlah_penduduk')
    ]);
});

// Routes untuk CRUD data umur
Route::post('/penduduk/umur/tambah', [DashboardController::class, 'tambahDataUmur'])->name('penduduk.umur.tambah');
Route::put('/penduduk/umur/edit/{id}', [DashboardController::class, 'editDataUmur'])->name('penduduk.umur.edit');
Route::delete('/penduduk/umur/hapus/{id}', [DashboardController::class, 'hapusDataUmur'])->name('penduduk.umur.hapus');

// Routes untuk CRUD Data Kecamatan
Route::post('/penduduk/kecamatan/tambah', [DashboardController::class, 'tambahDataKecamatan'])->name('penduduk.kecamatan.tambah');
Route::put('/penduduk/kecamatan/edit/{id}', [DashboardController::class, 'editDataKecamatan'])->name('penduduk.kecamatan.edit');
Route::delete('/penduduk/kecamatan/hapus/{id}', [DashboardController::class, 'hapusDataKecamatan'])->name('penduduk.kecamatan.hapus');


// Routes untuk CRUD Data Jawa Tengah
Route::post('/penduduk/sejateng/tambah', [DashboardController::class, 'tambahDataSejateng'])->name('penduduk.sejateng.tambah');
Route::put('/penduduk/sejateng/edit/{id}', [DashboardController::class, 'editDataSejateng'])->name('penduduk.sejateng.edit');
Route::delete('/penduduk/sejateng/hapus/{id}', [DashboardController::class, 'hapusDataSejateng'])->name('penduduk.sejateng.hapus');
Route::post('/dashboard/import-penduduk-umur', [PendudukUmurController::class, 'import'])->name('penduduk.umur.import');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Statistik Penduduk
    Route::get('/penduduk', [DashboardController::class, 'penduduk'])->name('penduduk');
    Route::get('/penduduk/kecamatan', [DashboardController::class, 'pendudukKecamatan'])->name('penduduk.kecamatan');
    Route::get('/penduduk/sejateng', [DashboardController::class, 'pendudukSejateng'])->name('penduduk.sejateng');
    Route::get('/penduduk/seumur', [DashboardController::class, 'pendudukUmur'])->name('penduduk.umur');
    
    // Tenaga Kerja
    Route::get('/tenaga-kerja', [DashboardController::class, 'tenagaKerja'])->name('tenaga-kerja');

    // Kemiskinan & Gini Rasio
    Route::get('/kemiskinan', [DashboardController::class, 'kemiskinan'])->name('kemiskinan');
    Route::get('/gini-rasio', [DashboardController::class, 'giniRasio'])->name('gini-rasio');
    Route::post('/gini-rasio/tambah', [DashboardController::class, 'tambahGiniRasio'])->name('ginirasio.mis.tambah');
});


//Route tanppa autentikasi 

Route::get('/ketenagakerjaan', function (){
    return view('dashboard.tenaga-kerja');
});
Route::get('/tenagakerjadua', function (){
    return view('dashboard.tenagakerja-dua');
});

Route::prefix('admin')->group(function () {
    Route::get('/ketenagakerjaan', [DashboardController::class, 'ketenagakerjaan'])->name('ketenagakerjaan');
});

Route::post('/penduduk/kerja/tambah', [DashboardController::class, 'kerja'])->name('penduduk.kerja.tambah');
Route::post('/penduduk/kerja/tambahdua', [DashboardController::class, 'tambahTenagaKerjaDua'])->name('penduduk.kerja.tambahdua');

Route::delete('/penduduk/kerja/hapus/{id}', [DashboardController::class, 'hapusKerja'])->name('penduduk.kerja.hapus');
Route::post('/ginirasio/mis/tambah', [DashboardController::class, 'tambahGiniRasio'])->name('ginirasio.mis.tambah');

// routes/web.php
Route::get('/tenagakerjaduaa', [DashboardController::class, 'tenagakerjadua'])->name('tenagakerjadua');
Route::get('/ginimenu', [DashboardController::class, 'ipmidg'])->name('ginimenu');
Route::get('/ipmdata', [DashboardController::class, 'ipm'])->name('ipmdata');
Route::post('/ipm/data/tahun', [DashboardController::class, 'tambahIpM'])->name('ipm.data.tahun');
Route::get('/ipgdata', [DashboardController::class, 'ipg'])->name('ipgdata');
Route::post('/ipg/data/tahun', [DashboardController::class, 'tambahIpG'])->name('ipg.data.tahun');
Route::get('/inflasi', function (){
    return view('dashboard.inflasimenu');
});
Route::get('/tenagakerjadual', [DashboardController::class, 'infasimakanan'])->name('inflasimakanan');
Route::post('/inflasi/makanan/tambah', [DashboardController::class, 'tambahInflasiMakanan'])->name('inflasimakanan.tambah');
Route::get('/inflasipakaian', [DashboardController::class, 'PakaianInflasi'])->name('PakaianInflasi');
Route::post('/inflasi/pakaian/tambah', [DashboardController::class, 'tambahInflasiPakaian'])->name('inflasipakaian.tambah');
Route::get('/inflasippl', [DashboardController::class, 'PPLInflasi'])->name('PPLInflasi');
Route::post('/inflasi/ppl/tambah', [DashboardController::class, 'PPLTambahInflasi'])->name('inflasippl.tambah');
Route::get('/inflasirt', [DashboardController::class, 'PemeliharaRT'])->name('PemeliharaRT');
Route::post('/inflasi/pemelihara/tambah', [DashboardController::class, 'PemeliharaRTTambahInflasi'])->name('inflasipemelihara.tambah');
Route::get('/perlengkapan', [DashboardController::class, 'inflasiSehat'])->name('PerlengkapanSehat');
Route::post('/inflasi/perlengkapan/tambah', [DashboardController::class, 'inflasiSehatTambah'])->name('inflasisehat.tambah');  
Route::get('/trans', [DashboardController::class, 'inflasiSehatTrans'])->name('inflasiSehatTrans'); 
Route::post('/inflasi/trans/tambah', [DashboardController::class, 'inflasiSehatTransTambah'])->name('inflasitrans.tambah');
Route::get('/informasi', [DashboardController::class, 'inflasiInformasi'])->name('informasi');
Route::post('/inflasi/informasi/tambah', [DashboardController::class, 'inflasiInformasiTambah'])->name('inflasiinformasi.tambah');
Route::get('/rekreasi', [DashboardController::class, 'rekreasi'])->name('inflasirekreasi');
Route::post('/inflasi/rekreasi/tambah', [DashboardController::class, 'rekreasiTambah'])->name('inflasirekreasi.tambah');
Route::get('/inflasi/data', [DashboardController::class, 'createInflasi'])->name('inflasi.data');
Route::post('/inflasi', [DashboardController::class, 'storeInflasi'])->name('inflasi.store');
Route::get('/pendidikan', function () {
    return view('dashboard.pendidikanmenu');
});
Route::get('/pendidikan/data', [DashboardController::class, 'pendidikan'])->name('pendidikan.data');
Route::post('/pendidikan/tambah', [DashboardController::class, 'PendidikanTambah'])->name('pendidikan.tambah');
Route::get('/pendidikan/dua', [DashboardController::class, 'PendidikanDua'])->name('pendidikan.dua');
Route::post('/pendidikan/tambah/duaa', [DashboardController::class, 'tambahPendidikanDua' ])->name('tambah.pendidikan.duakali');
Route::get('ekonomi', function(){
    return view('dashboard.ekonomi');
});
Route::get('/ekonomisatu', [DashboardController::class, 'Ekonomi'])->name('ekonomi.satu');
Route::post('/tambah/ekonomi', [DashboardController::class, 'tambahEkonomi'])->name('tambah.ekonom');
Route::get('/ekonomi/sef', [DashboardController::class, 'EkonomiSE'])->name('ekonomi.tambah.se');
Route::post('/nambah/ekonomi/se', [DashboardController::class, 'nambahEkonomi'])->name('nambah.ekonomi.se');