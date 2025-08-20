@extends('layouts.app')

@section('title', 'Data Gini Rasio - BPS Kota Semarang')
@section('page-title', 'Data Gini Rasio')
@section('page-subtitle', 'Statistik ketimpangan pendapatan Kota Semarang per kecamatan')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-3">
    <div class="col-md-4">
        <form method="GET">
            <div class="input-group">
                <label class="input-group-text" for="filter_tahun">Filter Tahun</label>
                <select class="form-select" id="filter_tahun" name="tahun" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @for($tahun = 2020; $tahun <= 2024; $tahun++)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Data Kemiskinan dan Gini Rasio Kota Semarang</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGiniRasioModal">
                    <i class="fas fa-plus me-2"></i>Tambah Data
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Jumlah Penduduk Miskin</th>
                                <th>Penduduk Miskin</th>
                                <th>Garis Kemiskinan</th>
                                <th>Gini Rasio</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ number_format($item->penduduk_miskin) }}</td>
                                <td>{{ number_format($item->penduduk_miskin_persen) }}%</td>
                                <td>Rp {{ number_format($item->garis_kemiskinan) }}</td>
                                <td>{{ number_format($item->gini_rasio) }}</td>
                                <td>{{ number_format($item->jumlah) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <a href="{{ route('ginirasio.hapus', $item->id) }}" class="text-white">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data gini rasio</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->

<!-- Informasi -->


<!-- Modal Tambah Gini Rasio -->
<div class="modal fade" id="addGiniRasioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ginirasio.mis.tambah') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Data Gini Rasio dan Kemiskinan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control" name="tahun" id="tahun" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Penduduk Miskin</label>
                        <input type="number" class="form-control" name="penduduk_miskin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penduduk Miskin Dalam Persen</label>
                        <input type="number" step="0.01" class="form-control" name="penduduk_miskin_persen" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Garis Kemiskinan</label>
                        <input type="number" class="form-control" name="garis_kemiskinan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gini Rasio</label>
                        <input type="number" step="0.001" class="form-control" name="gini_rasio" required>
                    </div>
                    
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

