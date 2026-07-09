@extends('layouts.app')

@section('title', 'Tambah Barang Baru')
@section('page_title', 'Tambah Barang Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-white border-opacity-10">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-plus-circle text-indigo-400 me-2"></i> Form Registrasi Barang Baru
                    </h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-glass d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label-custom">Nama Barang</label>
                        <input type="text" name="name" id="name" class="form-control form-control-custom" placeholder="Masukkan nama barang lengkap..." value="{{ old('name') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label-custom">Kategori Barang</label>
                            <select name="category_id" id="category_id" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label-custom">Jumlah Stok Awal</label>
                            <input type="number" name="stock" id="stock" class="form-control form-control-custom" placeholder="0" min="0" value="{{ old('stock', 0) }}" required>
                        </div>
                    </div>

                    @if(auth()->user()->isSuperAdmin())
                        <div class="mb-3">
                            <label for="branch_id" class="form-label-custom">Cabang Kepemilikan Barang</label>
                            <select name="branch_id" id="branch_id" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Pilih Cabang Kantor...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="barcode" class="form-label-custom">Barcode Khusus (Opsional)</label>
                        <input type="text" name="barcode" id="barcode" class="form-control form-control-custom" placeholder="Masukkan barcode kustom jika ada..." value="{{ old('barcode') }}">
                        <small class="text-secondary d-block mt-2">
                            * Kosongkan kolom ini jika ingin sistem menghasilkan barcode unik secara otomatis berdasarkan kode Cabang dan Kategori.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label-custom">Deskripsi Barang</label>
                        <textarea name="description" id="description" class="form-control form-control-custom" rows="4" placeholder="Tuliskan spesifikasi barang atau keterangan tambahan...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top border-white border-opacity-10 pt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-glass px-4">Batal</a>
                        <button type="submit" class="btn btn-indigo" style="background-color: #4f46e5; color: white; border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                            Simpan Barang <i class="bi bi-check-circle ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
