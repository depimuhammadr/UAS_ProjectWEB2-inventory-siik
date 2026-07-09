@extends('layouts.app')

@section('title', 'Daftar Barang')
@section('page_title', 'Manajemen Inventaris Barang')

@section('content')
<div class="container-fluid px-0">
    <!-- Toolbar Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill text-indigo-400 fs-4"></i>
                    <h5 class="fw-bold mb-0">Total: {{ $products->total() }} Produk Terdaftar</h5>
                </div>
                
                @if(auth()->user()->isAdmin())
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-glass btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-arrow-up-fill text-info"></i> Impor CSV
                        </button>
                        <a href="{{ route('products.export.csv') }}" class="btn btn-glass btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> Ekspor CSV
                        </a>
                        <a href="{{ route('products.export.pdf') }}" target="_blank" class="btn btn-glass btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Cetak / PDF
                        </a>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2 px-3 py-2">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Barang Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card py-3">
                <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control form-control-custom border-start-0 ps-0" placeholder="Cari nama barang atau barcode..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select form-select-custom">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-indigo w-100 fw-bold" style="background-color: #4f46e5; color: white; border: none; border-radius: 10px; padding: 10px;">
                            Filter <i class="bi bi-funnel ms-1"></i>
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-glass px-3" title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card p-0 overflow-hidden border-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 250px;">Barcode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Cabang</th>
                                <th class="text-center">Total Stok</th>
                                <th class="text-center">Tersedia</th>
                                @if(auth()->user()->isUser())
                                    <th class="text-center">Peminjaman</th>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <th class="text-end" style="width: 150px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($products->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-secondary">
                                        <i class="bi bi-box-fill fs-1 d-block mb-3 opacity-30"></i>
                                        Tidak ada data produk ditemukan.
                                    </td>
                                </tr>
                            @else
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="p-2 bg-white rounded-3 d-inline-block shadow-sm">
                                                <svg class="barcode-svg" jsbarcode-value="{{ $product->barcode }}" jsbarcode-textmargin="0" jsbarcode-height="25" jsbarcode-width="1.2" style="font-size: 10px; display: block;"></svg>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-light">{{ $product->name }}</div>
                                            @if($product->description)
                                                <small class="text-secondary text-truncate d-block" style="max-width: 250px;" title="{{ $product->description }}">{{ $product->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-light px-2.5 py-1.5 rounded-3" style="font-size: 0.75rem;">{{ $product->category->name }}</span>
                                        </td>
                                        <td>
                                            <span class="small text-secondary fw-semibold">{{ $product->branch->name }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $product->stock }}</td>
                                        <td class="text-center">
                                            @if($product->available_stock > 0)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold">{{ $product->available_stock }}</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold">Habis</span>
                                            @endif
                                        </td>
                                        
                                        @if(auth()->user()->isUser())
                                            <td class="text-center">
                                                @if($product->available_stock > 0)
                                                    <button type="button" class="btn btn-action btn-sm btn-indigo" style="background-color: #4f46e5; color: white; border: none;" data-bs-toggle="modal" data-bs-target="#borrowModal{{ $product->id }}">
                                                        <i class="bi bi-box-arrow-right"></i> Pinjam
                                                    </button>
                                                @else
                                                    <button class="btn btn-action btn-sm btn-glass text-secondary" disabled>
                                                        Kosong
                                                    </button>
                                                @endif
                                            </td>
                                        @endif

                                        @if(auth()->user()->isAdmin())
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-2">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-glass btn-sm border-0" title="Edit">
                                                        <i class="bi bi-pencil-fill text-indigo-400"></i>
                                                    </a>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-glass btn-sm border-0" title="Hapus">
                                                            <i class="bi bi-trash-fill text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Borrow Request Modal (User Only) -->
                                    @if(auth()->user()->isUser() && $product->available_stock > 0)
                                        <div class="modal fade" id="borrowModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-white border-opacity-10 bg-slate-900 text-light" style="background-color: #0f172a;">
                                                    <div class="modal-header border-bottom-white border-opacity-10">
                                                        <h5 class="modal-title fw-bold">Ajukan Peminjaman Barang</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('borrowings.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Barang yang Dipinjam</label>
                                                                <input type="text" class="form-control form-control-custom bg-secondary-subtle" value="{{ $product->name }} ({{ $product->barcode }})" readonly style="opacity: 0.8;">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="qty" class="form-label-custom">Jumlah Pinjam (Maks: {{ $product->available_stock }})</label>
                                                                <input type="number" name="qty" id="qty" class="form-control form-control-custom" min="1" max="{{ $product->available_stock }}" value="1" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="borrow_date" class="form-label-custom">Tanggal Peminjaman</label>
                                                                <input type="date" name="borrow_date" id="borrow_date" class="form-control form-control-custom" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="notes" class="form-label-custom">Keterangan / Keperluan Peminjaman</label>
                                                                <textarea name="notes" id="notes" class="form-control form-control-custom" rows="3" placeholder="Contoh: Kebutuhan rapat cabang atau instalasi operasional..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-white border-opacity-10">
                                                            <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-indigo" style="background-color: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 8px;">Kirim Pengajuan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top border-white border-opacity-10">
                        <span class="text-secondary small">Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} barang</span>
                        <div>
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Import CSV Modal (Admin Only) -->
@if(auth()->user()->isAdmin())
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-white border-opacity-10" style="background-color: #0f172a; color: white;">
                <div class="modal-header border-bottom border-white border-opacity-10">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up-fill text-indigo-400"></i> Impor Produk dari CSV</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.import.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-secondary mb-3">
                            Unduh atau buat file CSV dengan format kolom berikut agar data terimpor dengan benar.
                        </p>
                        
                        <div class="p-3 bg-secondary bg-opacity-10 rounded-3 mb-3 border border-white border-opacity-10">
                            <span class="small fw-bold d-block mb-1 text-light">Format Tajuk/Header CSV:</span>
                            @if(auth()->user()->isSuperAdmin())
                                <code class="small text-indigo-300">name,category_code,description,stock,branch_code</code>
                            @else
                                <code class="small text-indigo-300">name,category_code,description,stock</code>
                            @endif
                            
                            <hr class="my-2 border-white border-opacity-10">
                            
                            <span class="small fw-bold d-block mb-1 text-light">Contoh isi baris:</span>
                            @if(auth()->user()->isSuperAdmin())
                                <code class="small text-secondary">Laptop ASUS,ELEK,Core i7 Ram 16GB,10,JKT</code>
                            @else
                                <code class="small text-secondary">Laptop ASUS,ELEK,Core i7 Ram 16GB,10</code>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="csv_file" class="form-label-custom">Pilih File CSV (.csv)</label>
                            <input type="file" name="csv_file" id="csv_file" class="form-control form-control-custom" accept=".csv" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white border-opacity-10">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-indigo" style="background-color: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 8px;">Mulai Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<!-- JsBarcode CDN -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Barcode SVG rendering
        JsBarcode(".barcode-svg").init();
    });
</script>
@endsection
