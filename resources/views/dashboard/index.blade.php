@extends('layouts.app')

@section('title', 'Dashboard - BPS Kota Semarang')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data statistik Kota Semarang')

@section('content')
<div class="row">
    <!-- Statistik Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ number_format($totalPenduduk) }}</div>
                    <div class="label">Total Penduduk</div>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ number_format($totalTenagaKerja) }}</div>
                    <div class="label">Angkatan Kerja</div>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ number_format($totalKemiskinan) }}</div>
                    <div class="label">Penduduk Miskin</div>
                </div>
                <div class="icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ number_format($rataGiniRasio, 3) }}</div>
                    <div class="label">Rata-rata Gini Rasio</div>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik dan Informasi -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Trend Statistik Kota Semarang</h5>
            </div>
            <div class="card-body">
                <canvas id="statisticsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi BPS</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi</h6>
                    <p class="mb-0">Jl. Dr. Wahidin No.1, Semarang Tengah, Kota Semarang</p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-phone text-primary me-2"></i>Kontak</h6>
                    <p class="mb-0">(024) 351-1234</p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                    <p class="mb-0">bps3374@bps.go.id</p>
                </div>
                <div>
                    <h6><i class="fas fa-globe text-primary me-2"></i>Website</h6>
                    <p class="mb-0">semarangkota.bps.go.id</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Menu Cepat -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Akses Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('penduduk') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Data Penduduk
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('tenaga-kerja') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-briefcase me-2"></i>Data Tenaga Kerja
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('kemiskinan') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-heart me-2"></i>Data Kemiskinan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('gini-rasio') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-chart-line me-2"></i>Data Gini Rasio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample data untuk grafik
const ctx = document.getElementById('statisticsChart').getContext('2d');
const statisticsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
            label: 'Penduduk',
            data: [1800000, 1820000, 1840000, 1860000, 1880000],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4
        }, {
            label: 'Angkatan Kerja',
            data: [900000, 920000, 940000, 960000, 980000],
            borderColor: '#764ba2',
            backgroundColor: 'rgba(118, 75, 162, 0.1)',
            tension: 0.4
        }, {
            label: 'Penduduk Miskin',
            data: [180000, 175000, 170000, 165000, 160000],
            borderColor: '#f093fb',
            backgroundColor: 'rgba(240, 147, 251, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Trend Statistik Kota Semarang (2019-2023)'
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