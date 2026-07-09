@extends('layouts.app')

@section('title', 'Transaksi Peminjaman')
@section('page_title', 'Manajemen Transaksi Peminjaman')

@section('content')
<div class="container-fluid px-0">
    <!-- Toolbar Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-down-up text-indigo-400 fs-4"></i>
                    <h5 class="fw-bold mb-0">Total Transaksi: {{ $borrowings->total() }} Data</h5>
                </div>
                
                @if(auth()->user()->isAdmin())
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('borrowings.export.csv') }}" class="btn btn-glass btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> Ekspor CSV
                        </a>
                        <a href="{{ route('borrowings.export.pdf') }}" target="_blank" class="btn btn-glass btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Cetak Laporan PDF
                        </a>
                    </div>
                @else
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2 px-3 py-2">
                        <i class="bi bi-plus-circle-fill"></i> Ajukan Peminjaman Baru
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Tab & Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card py-2 px-3">
                <form action="{{ route('borrowings.index') }}" method="GET" class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <a href="{{ route('borrowings.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-indigo' : 'btn-glass' }}" style="{{ !request('status') ? 'background-color: #4f46e5; border-color: #4f46e5; color: white;' : '' }}">Semua</a>
                        <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-indigo' : 'btn-glass' }}" style="{{ request('status') == 'pending' ? 'background-color: #4f46e5; border-color: #4f46e5; color: white;' : '' }}">Pending</a>
                        <a href="{{ route('borrowings.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-indigo' : 'btn-glass' }}" style="{{ request('status') == 'approved' ? 'background-color: #4f46e5; border-color: #4f46e5; color: white;' : '' }}">Aktif (Approved)</a>
                        <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="btn btn-sm {{ request('status') == 'returned' ? 'btn-indigo' : 'btn-glass' }}" style="{{ request('status') == 'returned' ? 'background-color: #4f46e5; border-color: #4f46e5; color: white;' : '' }}">Dikembalikan</a>
                        <a href="{{ route('borrowings.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-indigo' : 'btn-glass' }}" style="{{ request('status') == 'rejected' ? 'background-color: #4f46e5; border-color: #4f46e5; color: white;' : '' }}">Ditolak</a>
                    </div>
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Borrowing Records Table -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card p-0 overflow-hidden border-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Barang</th>
                                <th>Cabang</th>
                                <th class="text-center">Jumlah</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th class="text-center">Status</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="text-end" style="width: 200px;">Aksi Admin</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($borrowings->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-secondary">
                                        <i class="bi bi-clipboard2-x-fill fs-1 d-block mb-3 opacity-30"></i>
                                        Tidak ada transaksi peminjaman ditemukan.
                                    </td>
                                </tr>
                            @else
                                @foreach($borrowings as $borrow)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-light">{{ $borrow->user->name }}</div>
                                            <span class="small text-secondary">{{ $borrow->user->division ? $borrow->user->division->name : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-indigo-300">{{ $borrow->product->name }}</div>
                                            <small class="small text-secondary d-block" style="font-size: 0.75rem;">{{ $borrow->product->barcode }}</small>
                                        </td>
                                        <td>
                                            <span class="small text-secondary">{{ $borrow->product->branch->name }}</span>
                                        </td>
                                        <td class="text-center fw-bold text-light">{{ $borrow->qty }}</td>
                                        <td>
                                            <span class="small text-light">{{ $borrow->borrow_date->format('d M Y') }}</span>
                                        </td>
                                        <td>
                                            @if($borrow->return_date)
                                                <span class="small text-success fw-bold">{{ $borrow->return_date->format('d M Y') }}</span>
                                            @else
                                                <span class="small text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($borrow->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-2.5 py-1.5 rounded-3">Pending</span>
                                            @elseif($borrow->status === 'approved')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-2.5 py-1.5 rounded-3">Aktif (Approved)</span>
                                            @elseif($borrow->status === 'returned')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-3">Dikembalikan</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1.5 rounded-3">Ditolak</span>
                                            @endif
                                        </td>

                                        @if(auth()->user()->isAdmin())
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-2">
                                                    @if($borrow->status === 'pending')
                                                        <!-- Approve Action -->
                                                        <button class="btn btn-action btn-sm btn-success d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#approveModal{{ $borrow->id }}">
                                                            <i class="bi bi-check-lg"></i> Setuju
                                                        </button>
                                                        <!-- Reject Action -->
                                                        <button class="btn btn-action btn-sm btn-danger d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $borrow->id }}">
                                                            <i class="bi bi-x-lg"></i> Tolak
                                                        </button>
                                                    @elseif($borrow->status === 'approved')
                                                        <!-- Return Action -->
                                                        <button class="btn btn-action btn-sm btn-indigo d-flex align-items-center gap-1" style="background-color: #4f46e5; color: white; border: none;" data-bs-toggle="modal" data-bs-target="#returnModal{{ $borrow->id }}">
                                                            <i class="bi bi-arrow-return-left"></i> Selesai
                                                        </button>
                                                    @else
                                                        <span class="small text-secondary px-2">Selesai diproses</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Modals for Actions -->
                                    @if(auth()->user()->isAdmin())
                                        <!-- Approve Modal -->
                                        @if($borrow->status === 'pending')
                                            <div class="modal fade" id="approveModal{{ $borrow->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-white border-opacity-10" style="background-color: #0f172a; color: white;">
                                                        <div class="modal-header border-bottom border-white border-opacity-10">
                                                            <h5 class="modal-title fw-bold text-success"><i class="bi bi-check-circle-fill"></i> Setujui Peminjaman</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('borrowings.approve', $borrow->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p class="small text-secondary mb-3">
                                                                    Anda menyetujui peminjaman <strong>{{ $borrow->qty }} unit {{ $borrow->product->name }}</strong> oleh <strong>{{ $borrow->user->name }}</strong>.
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="admin_notes" class="form-label-custom">Catatan Persetujuan (Opsional)</label>
                                                                    <textarea name="admin_notes" id="admin_notes" class="form-control form-control-custom" rows="3" placeholder="Tuliskan nomor seri unit, lokasi pengambilan, atau pesan khusus..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top border-white border-opacity-10">
                                                                <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-success px-4 fw-bold">Setujui Sekarang</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $borrow->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-white border-opacity-10" style="background-color: #0f172a; color: white;">
                                                        <div class="modal-header border-bottom border-white border-opacity-10">
                                                            <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill"></i> Tolak Pengajuan Peminjaman</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('borrowings.reject', $borrow->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p class="small text-secondary mb-3">
                                                                    Menolak pengajuan <strong>{{ $borrow->qty }} unit {{ $borrow->product->name }}</strong> oleh <strong>{{ $borrow->user->name }}</strong>.
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="admin_notes" class="form-label-custom">Alasan Penolakan (Wajib)</label>
                                                                    <textarea name="admin_notes" id="admin_notes" class="form-control form-control-custom" rows="3" placeholder="Sebutkan alasan penolakan, misalnya: Stok habis secara fisik, barang sedang rusak, dll..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top border-white border-opacity-10">
                                                                <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger px-4 fw-bold">Tolak Pengajuan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Return Modal -->
                                        @if($borrow->status === 'approved')
                                            <div class="modal fade" id="returnModal{{ $borrow->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-white border-opacity-10" style="background-color: #0f172a; color: white;">
                                                        <div class="modal-header border-bottom border-white border-opacity-10">
                                                            <h5 class="modal-title fw-bold text-info"><i class="bi bi-arrow-return-left"></i> Pengembalian Barang Inventaris</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('borrowings.return', $borrow->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p class="small text-secondary mb-3">
                                                                    Apakah Anda mengonfirmasi bahwa <strong>{{ $borrow->qty }} unit {{ $borrow->product->name }}</strong> yang dipinjam oleh <strong>{{ $borrow->user->name }}</strong> telah dikembalikan dengan kondisi baik?
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="admin_notes" class="form-label-custom">Catatan Pengembalian (Opsional)</label>
                                                                    <textarea name="admin_notes" id="admin_notes" class="form-control form-control-custom" rows="3" placeholder="Masukkan kondisi barang saat kembali, misal: Kembali lengkap dan bersih..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top border-white border-opacity-10">
                                                                <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-indigo" style="background-color: #4f46e5; color: white; border: none; padding: 8px 16px; border-radius: 8px;">Konfirmasi Kembali</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($borrowings->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top border-white border-opacity-10">
                        <span class="text-secondary small">Menampilkan {{ $borrowings->firstItem() }} - {{ $borrowings->lastItem() }} dari {{ $borrowings->total() }} data peminjaman</span>
                        <div>
                            {{ $borrowings->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
