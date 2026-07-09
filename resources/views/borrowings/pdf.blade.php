<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Barang - {{ $branchName }}</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            color: #334155;
            background-color: #fff;
            padding: 20px;
        }

        .report-header {
            border-bottom: 3px double #cbd5e1;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .report-title {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .report-subtitle {
            font-weight: 500;
            color: #64748b;
        }

        .table-report th {
            background-color: #f1f5f9 !important;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .table-report td {
            padding: 12px;
            vertical-align: middle;
            font-size: 0.825rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .badge {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 5px 8px;
            font-weight: 700;
            border-radius: 4px;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .badge-approved {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .badge-returned {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Header Actions (Visible in Browser, Hidden in Print) -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 border no-print">
        <div>
            <h5 class="fw-bold mb-1">Pratinjau Cetak Laporan Peminjaman</h5>
            <p class="small text-muted mb-0">Klik tombol di samping untuk mencetak atau menyimpan sebagai file PDF.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup Pratinjau</button>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-4 fw-bold">Mulai Cetak / Simpan PDF</button>
        </div>
    </div>

    <!-- Report Paper -->
    <div class="report-header d-flex justify-content-between align-items-end">
        <div>
            <h2 class="report-title">Laporan Peminjaman Barang</h2>
            <h5 class="report-subtitle">Cabang: {{ $branchName }}</h5>
        </div>
        <div class="text-end text-muted small">
            <div>Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
            <div>Dicetak Oleh: {{ auth()->user()->name }} ({{ auth()->user()->role }})</div>
        </div>
    </div>

    <table class="table table-report table-striped w-100">
        <thead>
            <tr>
                <th style="width: 15%;">Nama Peminjam</th>
                <th style="width: 15%;">Divisi</th>
                <th style="width: 20%;">Barang</th>
                <th style="width: 10%;">Barcode</th>
                <th class="text-center" style="width: 5%;">Jumlah</th>
                <th style="width: 12%;">Tgl Pinjam</th>
                <th style="width: 12%;">Tgl Kembali</th>
                <th class="text-center" style="width: 11%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @if($borrowings->isEmpty())
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Tidak ada data transaksi peminjaman.</td>
                </tr>
            @else
                @foreach($borrowings as $borrow)
                    <tr>
                        <td><strong>{{ $borrow->user->name }}</strong></td>
                        <td>{{ $borrow->user->division ? $borrow->user->division->name : 'N/A' }}</td>
                        <td>{{ $borrow->product->name }}</td>
                        <td><code>{{ $borrow->product->barcode }}</code></td>
                        <td class="text-center fw-bold">{{ $borrow->qty }}</td>
                        <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                        <td>{{ $borrow->return_date ? $borrow->return_date->format('d M Y') : '-' }}</td>
                        <td class="text-center">
                            @if($borrow->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($borrow->status === 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($borrow->status === 'returned')
                                <span class="badge badge-returned">Returned</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto open browser printer dialog after a small delay
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
