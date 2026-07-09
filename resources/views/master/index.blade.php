@extends('layouts.app')

@section('title', 'Master Data')
@section('page_title', 'Pengaturan Master Data Perusahaan')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Branches Section -->
        <div class="col-xl-6">
            <div class="glass-card mb-4">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-building text-indigo-400 me-2"></i> Cabang Kantor
                </h5>

                <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Cabang</th>
                                <th class="text-center">Karyawan</th>
                                <th class="text-center">Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($branches->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-secondary">Belum ada cabang terdaftar.</td>
                                </tr>
                            @else
                                @foreach($branches as $branch)
                                    <tr>
                                        <td>
                                            <code class="px-2 py-1 bg-secondary bg-opacity-25 text-indigo-300 rounded small fw-bold">
                                                {{ $branch->code }}
                                            </code>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-light">{{ $branch->name }}</div>
                                            @if($branch->address)
                                                <small class="text-secondary d-block text-truncate" style="max-width: 180px;">{{ $branch->address }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center small">{{ $branch->users_count }}</td>
                                        <td class="text-center small">{{ $branch->products_count }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <hr class="my-4 border-white border-opacity-10">

                <h6 class="fw-bold text-light mb-3">Registrasi Cabang Baru</h6>
                <form action="{{ route('master.branches.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="branch_name" class="form-label-custom">Nama Cabang</label>
                            <input type="text" name="name" id="branch_name" class="form-control form-control-custom" placeholder="Contoh: Cabang Yogyakarta" required>
                        </div>
                        <div class="col-md-4">
                            <label for="branch_code" class="form-label-custom">Kode Cabang</label>
                            <input type="text" name="code" id="branch_code" class="form-control form-control-custom" placeholder="YGY" required style="text-transform: uppercase;">
                        </div>
                        <div class="col-12">
                            <label for="branch_address" class="form-label-custom">Alamat Cabang</label>
                            <input type="text" name="address" id="branch_address" class="form-control form-control-custom" placeholder="Alamat lengkap...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-indigo w-100 fw-bold py-2.5 mt-3" style="background-color: #4f46e5; color: white; border: none; border-radius: 10px;">
                        Daftarkan Cabang <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Divisions Section -->
        <div class="col-xl-6">
            <div class="glass-card">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-briefcase text-indigo-400 me-2"></i> Divisi Kerja
                </h5>

                <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Nama Divisi</th>
                                <th class="text-center" style="width: 30%;">Total Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($divisions->isEmpty())
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-secondary">Belum ada divisi terdaftar.</td>
                                </tr>
                            @else
                                @foreach($divisions as $division)
                                    <tr>
                                        <td class="fw-bold text-light">{{ $division->name }}</td>
                                        <td class="text-center small">{{ $division->users_count }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <hr class="my-4 border-white border-opacity-10">

                <h6 class="fw-bold text-light mb-3">Tambah Divisi Baru</h6>
                <form action="{{ route('master.divisions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="division_name" class="form-label-custom">Nama Divisi</label>
                        <input type="text" name="name" id="division_name" class="form-control form-control-custom" placeholder="Contoh: Procurement Division" required>
                    </div>
                    <button type="submit" class="btn btn-indigo w-100 fw-bold py-2.5 mt-3" style="background-color: #4f46e5; color: white; border: none; border-radius: 10px;">
                        Tambah Divisi <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
