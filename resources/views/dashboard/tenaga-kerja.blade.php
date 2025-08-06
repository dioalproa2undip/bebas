@extends('layouts.app')

@section('title', 'Data Tenaga Kerja - BPS Kota Semarang')
@section('page-title', 'Data Tenaga Kerja')
@section('page-subtitle', 'Statistik tenaga kerja Kota Semarang per kecamatan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Data Tenaga Kerja Kota Semarang</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTenagaKerjaModal">
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
                                <th>Angkatan Kerja</th>
                                <th>Bekerja</th>
                                <th>Pengangguran</th>
                                <th>Tingkat Pengangguran (%)</th>
                                <th>Tingkat Partisipasi (%)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->kecamatan }}</td>
                                <td>{{ number_format($item->jumlah_angkatan_kerja) }}</td>
                                <td>{{ number_format($item->jumlah_bekerja) }}</td>
                                <td>{{ number_format($item->jumlah_pengangguran) }}</td>
                                <td>{{ number_format($item->tingkat_pengangguran, 2) }}%</td>
                                <td>{{ number_format($item->tingkat_partisipasi_kerja, 2) }}%</td>
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
                                <td colspan="9" class="text-center">Tidak ada data tenaga kerja</td>
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
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Tenaga Kerja</h5>
            </div>
            <div class="card-body">
                <canvas id="tenagaKerjaChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Trend Tingkat Pengangguran</h5>
            </div>
            <div class="card-body">
                <canvas id="pengangguranChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addTenagaKerjaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Tenaga Kerja</h5>
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
                            <label class="form-label">Jumlah Angkatan Kerja</label>
                            <input type="number" class="form-control" name="jumlah_angkatan_kerja" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Bekerja</label>
                            <input type="number" class="form-control" name="jumlah_bekerja" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Pengangguran</label>
                            <input type="number" class="form-control" name="jumlah_pengangguran" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tingkat Pengangguran (%)</label>
                            <input type="number" step="0.01" class="form-control" name="tingkat_pengangguran" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkat Partisipasi Kerja (%)</label>
                        <input type="number" step="0.01" class="form-control" name="tingkat_partisipasi_kerja" required>
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
// Sample data untuk grafik status tenaga kerja
const tenagaKerjaCtx = document.getElementById('tenagaKerjaChart').getContext('2d');
const tenagaKerjaChart = new Chart(tenagaKerjaCtx, {
    type: 'doughnut',
    data: {
        labels: ['Bekerja', 'Pengangguran'],
        datasets: [{
            data: [850000, 50000],
            backgroundColor: [
                '#667eea',
                '#f5576c'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Sample data untuk grafik tingkat pengangguran
const pengangguranCtx = document.getElementById('pengangguranChart').getContext('2d');
const pengangguranChart = new Chart(pengangguranCtx, {
    type: 'line',
    data: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Tingkat Pengangguran (%)',
            data: [5.5, 6.2, 5.8, 5.3, 5.0],
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
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush 