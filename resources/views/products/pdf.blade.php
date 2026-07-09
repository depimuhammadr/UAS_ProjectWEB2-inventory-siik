<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Barang - {{ $branchName }}</title>
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
            font-size: 0.85rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .barcode-container {
            padding: 4px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            display: inline-block;
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
            <h5 class="fw-bold mb-1">Pratinjau Cetak Laporan</h5>
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
            <h2 class="report-title">Laporan Inventaris Barang</h2>
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
                <th style="width: 25%;">Barcode</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Cabang</th>
                <th class="text-center" style="width: 7%;">Stok</th>
                <th class="text-center" style="width: 8%;">Tersedia</th>
            </tr>
        </thead>
        <tbody>
            @if($products->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data produk.</td>
                </tr>
            @else
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="barcode-container">
                                <svg class="barcode-svg" jsbarcode-value="{{ $product->barcode }}" jsbarcode-height="25" jsbarcode-width="1.2" style="font-size: 10px; display: block;"></svg>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            @if($product->description)
                                <span class="text-muted d-block" style="font-size: 0.75rem;">{{ $product->description }}</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->branch->name }}</td>
                        <td class="text-center fw-bold">{{ $product->stock }}</td>
                        <td class="text-center fw-bold">{{ $product->available_stock }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- JsBarcode CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Render barcodes
            JsBarcode(".barcode-svg").init();
            
            // Auto open browser printer dialog after a small delay to ensure rendering completes
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
