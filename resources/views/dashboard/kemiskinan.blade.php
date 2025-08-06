@extends('layouts.app')

@section('title', 'Data Kemiskinan - BPS Kota Semarang')
@section('page-title', 'Data Kemiskinan')
@section('page-subtitle', 'Statistik kemiskinan Kota Semarang per kecamatan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Data Kemiskinan Kota Semarang</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKemiskinanModal">
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
                                <th>Jumlah Penduduk Miskin</th>
                                <th>Persentase (%)</th>
                                <th>Garis Kemiskinan (Rp)</th>
                                <th>Indeks Kedalaman</th>
                                <th>Indeks Keparahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->kecamatan }}</td>
                                <td>{{ number_format($item->jumlah_penduduk_miskin) }}</td>
                                <td>{{ number_format($item->persentase_penduduk_miskin, 2) }}%</td>
                                <td>Rp {{ number_format($item->garis_kemiskinan) }}</td>
                                <td>{{ number_format($item->indeks_kedalaman_kemiskinan, 2) }}</td>
                                <td>{{ number_format($item->indeks_keparahan_kemiskinan, 2) }}</td>
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
                                <td colspan="9" class="text-center">Tidak ada data kemiskinan</td>
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
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Persentase Kemiskinan per Kecamatan</h5>
            </div>
            <div class="card-body">
                <canvas id="kemiskinanChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Trend Garis Kemiskinan</h5>
            </div>
            <div class="card-body">
                <canvas id="garisKemiskinanChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addKemiskinanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Kemiskinan</h5>
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
                            <label class="form-label">Jumlah Penduduk Miskin</label>
                            <input type="number" class="form-control" name="jumlah_penduduk_miskin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Persentase Penduduk Miskin (%)</label>
                            <input type="number" step="0.01" class="form-control" name="persentase_penduduk_miskin" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Garis Kemiskinan (Rp)</label>
                            <input type="number" class="form-control" name="garis_kemiskinan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Indeks Kedalaman Kemiskinan</label>
                            <input type="number" step="0.01" class="form-control" name="indeks_kedalaman_kemiskinan" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Indeks Keparahan Kemiskinan</label>
                        <input type="number" step="0.01" class="form-control" name="indeks_keparahan_kemiskinan" required>
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
// Sample data untuk grafik persentase kemiskinan
const kemiskinanCtx = document.getElementById('kemiskinanChart').getContext('2d');
const kemiskinanChart = new Chart(kemiskinanCtx, {
    type: 'bar',
    data: {
        labels: ['Semarang Tengah', 'Semarang Utara', 'Semarang Timur', 'Semarang Selatan', 'Semarang Barat'],
        datasets: [{
            label: 'Persentase Kemiskinan (%)',
            data: [8.5, 9.2, 7.8, 10.1, 8.9],
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
                beginAtZero: true
            }
        }
    }
});

// Sample data untuk grafik garis kemiskinan
const garisKemiskinanCtx = document.getElementById('garisKemiskinanChart').getContext('2d');
const garisKemiskinanChart = new Chart(garisKemiskinanCtx, {
    type: 'line',
    data: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Garis Kemiskinan (Rp)',
            data: [450000, 480000, 520000, 550000, 580000],
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