@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page_title', 'Manajemen Kategori Barang')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Category List Table -->
        <div class="col-lg-8">
            <div class="glass-card">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-tags text-indigo-400 me-2"></i> Kategori Terdaftar
                </h5>

                <div class="table-responsive">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kode Kategori</th>
                                <th>Nama Kategori</th>
                                <th class="text-center">Jumlah Barang</th>
                                <th class="text-end" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($categories->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">
                                        <i class="bi bi-tag-fill fs-1 d-block mb-3 opacity-30"></i>
                                        Belum ada kategori terdaftar.
                                    </td>
                                </tr>
                            @else
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <code class="px-2 py-1 bg-secondary bg-opacity-25 text-indigo-300 rounded small fw-bold">
                                                {{ $category->code }}
                                            </code>
                                        </td>
                                        <td class="fw-bold text-light">{{ $category->name }}</td>
                                        <td class="text-center fw-bold">
                                            <span class="badge bg-secondary rounded-pill px-3 py-1.5">{{ $category->products_count }} Barang</span>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Penghapusan hanya bisa dilakukan jika tidak ada barang di dalamnya.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-glass btn-sm border-0" title="Hapus Kategori">
                                                    <i class="bi bi-trash-fill text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Category Form -->
        <div class="col-lg-4">
            <div class="glass-card">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-plus-circle text-indigo-400 me-2"></i> Tambah Kategori
                </h5>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label-custom">Nama Kategori</label>
                        <input type="text" name="name" id="name" class="form-control form-control-custom" placeholder="Contoh: Alat Tulis Kantor" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="code" class="form-label-custom">Kode Kategori (Singkatan)</label>
                        <input type="text" name="code" id="code" class="form-control form-control-custom" placeholder="Contoh: ATK" value="{{ old('code') }}" required style="text-transform: uppercase;">
                        <small class="text-secondary d-block mt-2">
                            * Maksimal 10 karakter huruf kapital. Digunakan sebagai prefiks pembuatan barcode otomatis.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-indigo w-100 fw-bold py-2.5" style="background-color: #4f46e5; color: white; border: none; border-radius: 10px;">
                        Simpan Kategori <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
