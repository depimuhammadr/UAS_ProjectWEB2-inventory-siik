@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.05) 100%);">
                <div>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-secondary mb-0">
                        @if(auth()->user()->isSuperAdmin())
                            Anda masuk sebagai **Super Admin**. Anda memiliki kontrol penuh atas semua inventaris cabang.
                        @elseif(auth()->user()->isAdmin())
                            Anda masuk sebagai **Admin Cabang** untuk **{{ auth()->user()->branch->name }}**. Anda mengelola stok cabang ini.
                        @else
                            Anda terdaftar sebagai karyawan di **{{ auth()->user()->branch->name }}** ({{ auth()->user()->division->name }}).
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-secondary small d-block text-md-end">Waktu Sistem</span>
                    <span class="fw-bold text-light"><i class="bi bi-clock-fill text-indigo-400 me-2"></i> {{ now()->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Products Stat -->
        <div class="col-md-6 col-lg-3">
            <div class="glass-card stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Produk / Stok</span>
                    <h2 class="fw-extrabold mb-0">{{ number_format($totalProducts) }}</h2>
                </div>
                <div class="bg-indigo-600 bg-opacity-20 p-3 rounded-4" style="color: #818cf8;">
                    <i class="bi bi-box-seam-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Active Borrowing Stat -->
        <div class="col-md-6 col-lg-3">
            <div class="glass-card stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Peminjaman Aktif</span>
                    <h2 class="fw-extrabold mb-0">{{ $activeBorrowings }}</h2>
                </div>
                <div class="bg-success bg-opacity-20 p-3 rounded-4" style="color: #34d399;">
                    <i class="bi bi-arrow-down-up fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approval Stat -->
        <div class="col-md-6 col-lg-3">
            <div class="glass-card stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Menunggu Persetujuan</span>
                    <h2 class="fw-extrabold mb-0">{{ $pendingBorrowings }}</h2>
                </div>
                <div class="bg-warning bg-opacity-20 p-3 rounded-4" style="color: #fbbf24;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Branches / Division Stat -->
        <div class="col-md-6 col-lg-3">
            @if(auth()->user()->isSuperAdmin())
                <div class="glass-card stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Total Cabang Kantor</span>
                        <h2 class="fw-extrabold mb-0">{{ $totalBranches }}</h2>
                    </div>
                    <div class="bg-purple bg-opacity-20 p-3 rounded-4" style="color: #c084fc;">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                </div>
            @else
                <div class="glass-card stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Divisi Kerja Anda</span>
                        <h5 class="fw-bold mb-0 text-truncate mt-1" style="max-width: 150px;">{{ auth()->user()->division ? auth()->user()->division->name : 'N/A' }}</h5>
                    </div>
                    <div class="bg-purple bg-opacity-20 p-3 rounded-4" style="color: #c084fc;">
                        <i class="bi bi-briefcase-fill fs-3"></i>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart & Recent Activity -->
    <div class="row g-4">
        <!-- Line Chart -->
        <div class="col-lg-7">
            <div class="glass-card h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2 text-indigo-400"></i> Tren Peminjaman Barang (6 Bulan Terakhir)</h5>
                <div style="height: 300px; position: relative;">
                    <canvas id="borrowingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="col-lg-5">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-indigo-400"></i> Aktivitas Terakhir</h5>
                    <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-glass px-3">Lihat Semua</a>
                </div>
                
                @if($recentBorrowings->isEmpty())
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-clipboard2-data fs-1 d-block mb-3 opacity-30"></i>
                        Belum ada riwayat aktivitas peminjaman.
                    </div>
                @else
                    <div class="list-group list-group-flush" style="background: transparent;">
                        @foreach($recentBorrowings as $borrow)
                            <div class="list-group-item bg-transparent border-white border-opacity-10 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex gap-3">
                                        <div class="p-2 rounded-3 bg-secondary text-secondary-emphasis mt-1">
                                            <i class="bi bi-arrow-right-left"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-light mb-1">{{ $borrow->product->name }}</h6>
                                            <p class="small text-secondary mb-0">
                                                Dipinjam oleh <strong>{{ $borrow->user->name }}</strong>
                                                @if(auth()->user()->isSuperAdmin() && $borrow->user->branch)
                                                    ({{ $borrow->user->branch->code }})
                                                @endif
                                            </p>
                                            <span class="small text-secondary-emphasis" style="font-size: 0.75rem;">{{ $borrow->borrow_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        @if($borrow->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-2 py-1 rounded-pill">Pending</span>
                                        @elseif($borrow->status === 'approved')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-2 py-1 rounded-pill">Approved</span>
                                        @elseif($borrow->status === 'returned')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2 py-1 rounded-pill">Returned</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-1 rounded-pill">Rejected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('borrowingChart').getContext('2d');
        
        const labels = {!! json_encode($monthlyChartData['labels']) !!};
        const dataset = {!! json_encode($monthlyChartData['dataset']) !!};
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: dataset,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#a855f7',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8',
                            precision: 0
                        },
                        suggestedMin: 0
                    }
                }
            }
        });
    });
</script>
@endsection
