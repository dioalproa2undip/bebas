<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Models\Penduduk;
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

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Penduduk Routes
    Route::get('/penduduk', [DashboardController::class, 'penduduk'])->name('penduduk');

    Route::get('/penduduk/kecamatan', [DashboardController::class, 'pendudukKecamatan'])->name('penduduk.kecamatan');
    Route::get('/penduduk/sejateng', [DashboardController::class, 'pendudukSejateng'])->name('penduduk.sejateng');
    Route::get('/penduduk/seumur', [DashboardController::class, 'pendudukUmur'])->name('penduduk.umur');
    Route::get('/tenaga-kerja', [DashboardController::class, 'tenagaKerja'])->name('tenaga-kerja');
    Route::get('/kemiskinan', [DashboardController::class, 'kemiskinan'])->name('kemiskinan');
    Route::get('/gini-rasio', [DashboardController::class, 'giniRasio'])->name('gini-rasio');
});
