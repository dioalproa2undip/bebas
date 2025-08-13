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
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Data Gini Rasio Kota Semarang</h5>
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
                                <th>UHH</th>
                                <th>RLS</th>
                                <th>HLS</th>
                                <th>Pengeluaran</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ number_format($item->UHH) }}</td>
                                <td>{{ number_format($item->RLS) }}%</td>
                                <td>Rp {{ number_format($item->HLS) }}</td>
                                <td>{{ number_format($item->Pengeluaran) }}</td>
                                <td>{{ number_format($item->jumlah) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
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
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Gini Rasio per Kecamatan</h5>
            </div>
            <div class="card-body">
                <canvas id="giniRasioChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Trend Gini Rasio Kota Semarang</h5>
            </div>
            <div class="card-body">
                <canvas id="trendGiniChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Informasi -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Gini Rasio</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-success">Ketimpangan Rendah</h6>
                            <p class="mb-0">Gini Rasio < 0.4</p>
                            <small class="text-muted">Distribusi pendapatan relatif merata</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-warning">Ketimpangan Sedang</h6>
                            <p class="mb-0">Gini Rasio 0.4 - 0.5</p>
                            <small class="text-muted">Distribusi pendapatan sedang</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h6 class="text-danger">Ketimpangan Tinggi</h6>
                            <p class="mb-0">Gini Rasio > 0.5</p>
                            <small class="text-muted">Distribusi pendapatan tidak merata</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Gini Rasio -->
<div class="modal fade" id="addGiniRasioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ginirasio.mis.tambah') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Data Gini Rasio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control" name="tahun" id="tahun" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">UHH</label>
                        <input type="number" class="form-control" name="UHH" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">RLS</label>
                        <input type="number" step="0.01" class="form-control" name="RLS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">HLS</label>
                        <input type="number" class="form-control" name="HLS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengeluaran</label>
                        <input type="number" step="0.001" class="form-control" name="Pengeluaran" required>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart 1: Gini Rasio per Kecamatan
const giniRasioCtx = document.getElementById('giniRasioChart').getContext('2d');
new Chart(giniRasioCtx, {
    type: 'bar',
    data: {
        labels: ['Kec. A', 'Kec. B', 'Kec. C', 'Kec. D', 'Kec. E'],
        datasets: [{
            label: 'Gini Rasio',
            data: [0.385, 0.412, 0.398, 0.425, 0.401],
            backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 1 } }
    }
});

// Chart 2: Trend Gini Rasio
const trendGiniCtx = document.getElementById('trendGiniChart').getContext('2d');
new Chart(trendGiniCtx, {
    type: 'line',
    data: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Gini Rasio Kota Semarang',
            data: [0.405, 0.398, 0.412, 0.408, 0.401],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, max: 1 } }
    }
});
</script>
@endpush
