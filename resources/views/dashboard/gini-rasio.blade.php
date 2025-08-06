@extends('layouts.app')

@section('title', 'Data Gini Rasio - BPS Kota Semarang')
@section('page-title', 'Data Gini Rasio')
@section('page-subtitle', 'Statistik ketimpangan pendapatan Kota Semarang per kecamatan')

@section('content')
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
                                <th>Kecamatan</th>
                                <th>Nilai Gini Rasio</th>
                                <th>Kategori Ketimpangan</th>
                                <th>Pendapatan per Kapita (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->kecamatan }}</td>
                                <td>{{ number_format($item->nilai_gini_rasio, 3) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->nilai_gini_rasio < 0.4 ? 'success' : ($item->nilai_gini_rasio < 0.5 ? 'warning' : 'danger') }}">
                                        {{ $item->kategori_ketimpangan }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($item->pendapatan_per_kapita) }}</td>
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
                                <td colspan="7" class="text-center">Tidak ada data gini rasio</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- Modal Tambah Data -->
<div class="modal fade" id="addGiniRasioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Gini Rasio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" class="form-control" name="tahun" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <select class="form-select" name="kecamatan" required>
                                <option value="">Pilih Kecamatan</option>
                                <option value="Semarang Tengah">Semarang Tengah</option>
                                <option value="Semarang Utara">Semarang Utara</option>
                                <option value="Semarang Timur">Semarang Timur</option>
                                <option value="Semarang Selatan">Semarang Selatan</option>
                                <option value="Semarang Barat">Semarang Barat</option>
                                <option value="Candisari">Candisari</option>
                                <option value="Gajahmungkur">Gajahmungkur</option>
                                <option value="Pedurungan">Pedurungan</option>
                                <option value="Genuk">Genuk</option>
                                <option value="Gayamsari">Gayamsari</option>
                                <option value="Mijen">Mijen</option>
                                <option value="Gunungpati">Gunungpati</option>
                                <option value="Banyumanik">Banyumanik</option>
                                <option value="Tembalang">Tembalang</option>
                                <option value="Ngaliyan">Ngaliyan</option>
                                <option value="Tugu">Tugu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai Gini Rasio</label>
                            <input type="number" step="0.001" class="form-control" name="nilai_gini_rasio" min="0" max="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Ketimpangan</label>
                            <select class="form-select" name="kategori_ketimpangan" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Ketimpangan Rendah">Ketimpangan Rendah</option>
                                <option value="Ketimpangan Sedang">Ketimpangan Sedang</option>
                                <option value="Ketimpangan Tinggi">Ketimpangan Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pendapatan per Kapita (Rp)</label>
                        <input type="number" class="form-control" name="pendapatan_per_kapita" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample data untuk grafik gini rasio per kecamatan
const giniRasioCtx = document.getElementById('giniRasioChart').getContext('2d');
const giniRasioChart = new Chart(giniRasioCtx, {
    type: 'bar',
    data: {
        labels: ['Semarang Tengah', 'Semarang Utara', 'Semarang Timur', 'Semarang Selatan', 'Semarang Barat'],
        datasets: [{
            label: 'Gini Rasio',
            data: [0.385, 0.412, 0.398, 0.425, 0.401],
            backgroundColor: [
                '#667eea',
                '#764ba2',
                '#f093fb',
                '#f5576c',
                '#4facfe'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 1
            }
        }
    }
});

// Sample data untuk grafik trend gini rasio
const trendGiniCtx = document.getElementById('trendGiniChart').getContext('2d');
const trendGiniChart = new Chart(trendGiniCtx, {
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
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 1
            }
        }
    }
});
</script>
@endpush 