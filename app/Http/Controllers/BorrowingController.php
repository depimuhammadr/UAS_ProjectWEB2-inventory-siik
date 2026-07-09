<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Borrowing::with(['user', 'product', 'product.branch', 'user.division']);

        // 1. Branch Separation & Role Boundaries
        if ($user->isSuperAdmin()) {
            // Can see all borrowings
        } elseif ($user->isAdmin()) {
            // Branch admin: only see borrowings of products belonging to their branch
            $branchId = $user->branch_id;
            $query->whereHas('product', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        } else {
            // Regular user: only see their own borrowings
            $query->where('user_id', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'notes' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // 1. Prevent borrowing from other branches
        if ($product->branch_id !== $user->branch_id) {
            return back()->with('error', 'Gagal meminjam. Anda tidak diperbolehkan meminjam barang dari cabang lain.');
        }

        // 2. Validate stock availability
        if ($product->available_stock < $request->qty) {
            return back()->with('error', "Stok barang tidak mencukupi. Tersedia: {$product->available_stock}.");
        }

        Borrowing::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'qty' => $request->qty,
            'borrow_date' => $request->borrow_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('borrowings.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function approve(Request $request, Borrowing $borrowing)
    {
        $user = auth()->user();

        // Security check
        if (!$user->isSuperAdmin() && $borrowing->product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Status transaksi sudah tidak dalam kondisi pending.');
        }

        $product = $borrowing->product;

        // Recheck stock
        if ($product->available_stock < $borrowing->qty) {
            return back()->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman ini.');
        }

        DB::transaction(function() use ($borrowing, $product, $request) {
            // Decrement available stock
            $product->decrement('available_stock', $borrowing->qty);
            
            // Update status
            $borrowing->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes ?? 'Disetujui oleh Admin'
            ]);
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $user = auth()->user();

        // Security check
        if (!$user->isSuperAdmin() && $borrowing->product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Status transaksi sudah tidak dalam kondisi pending.');
        }

        $borrowing->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes ?? 'Ditolak oleh Admin'
        ]);

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman ditolak.');
    }

    public function return(Request $request, Borrowing $borrowing)
    {
        $user = auth()->user();

        // Security check
        if (!$user->isSuperAdmin() && $borrowing->product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($borrowing->status !== 'approved') {
            return back()->with('error', 'Hanya peminjaman yang sedang aktif (Approved) yang dapat dikembalikan.');
        }

        $product = $borrowing->product;

        DB::transaction(function() use ($borrowing, $product, $request) {
            // Restore available stock
            $product->increment('available_stock', $borrowing->qty);

            // Update status and return date
            $borrowing->update([
                'status' => 'returned',
                'return_date' => now(),
                'admin_notes' => $request->admin_notes ? $borrowing->admin_notes . ' | ' . $request->admin_notes : $borrowing->admin_notes . ' | Dikembalikan'
            ]);
        });

        return redirect()->route('borrowings.index')->with('success', 'Barang berhasil dikembalikan ke inventaris.');
    }

    public function exportCSV()
    {
        $user = auth()->user();
        $query = Borrowing::with(['user', 'product', 'product.branch', 'user.division']);

        if (!$user->isSuperAdmin()) {
            $branchId = $user->branch_id;
            $query->whereHas('product', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'peminjaman_' . ($user->branch ? strtolower($user->branch->code) : 'semua_cabang') . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID Peminjaman', 'Peminjam', 'Divisi', 'Cabang', 'Nama Barang', 'Barcode', 'Jumlah', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status', 'Catatan Karyawan', 'Catatan Admin'];

        $callback = function() use($borrowings, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($borrowings as $borrow) {
                fputcsv($file, [
                    $borrow->id,
                    $borrow->user->name,
                    $borrow->user->division ? $borrow->user->division->name : 'N/A',
                    $borrow->product->branch->name,
                    $borrow->product->name,
                    $borrow->product->barcode,
                    $borrow->qty,
                    $borrow->borrow_date->format('Y-m-d'),
                    $borrow->return_date ? $borrow->return_date->format('Y-m-d') : '-',
                    strtoupper($borrow->status),
                    $borrow->notes,
                    $borrow->admin_notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        $user = auth()->user();
        $query = Borrowing::with(['user', 'product', 'product.branch', 'user.division']);

        if (!$user->isSuperAdmin()) {
            $branchId = $user->branch_id;
            $query->whereHas('product', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();
        $branchName = $user->branch ? $user->branch->name : 'Semua Cabang';

        return view('borrowings.pdf', compact('borrowings', 'branchName'));
    }
}
