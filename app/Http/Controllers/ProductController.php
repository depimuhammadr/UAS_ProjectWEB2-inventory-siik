<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Product::with(['category', 'branch']);

        // 1. Branch Separation logic: Users and branch admins can only see their own branch items
        if (!$user->isSuperAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $categories = Category::all();
        $branches = Branch::all();

        return view('products.index', compact('products', 'categories', 'branches'));
    }

    public function create()
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('products.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode'
        ];

        // If super admin, they can choose which branch the product belongs to
        if ($user->isSuperAdmin()) {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        $request->validate($rules);

        $productData = $request->only(['name', 'category_id', 'description', 'stock', 'barcode']);
        $productData['branch_id'] = $user->isSuperAdmin() ? $request->branch_id : $user->branch_id;
        $productData['available_stock'] = $request->stock;

        Product::create($productData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        
        // Prevent editing cross-branch products unless Super Admin
        if (!$user->isSuperAdmin() && $product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan. Barang ini milik cabang lain.');
        }

        $categories = Category::all();
        $branches = Branch::all();
        return view('products.edit', compact('product', 'categories', 'branches'));
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin() && $product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan. Barang ini milik cabang lain.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'barcode' => "nullable|string|unique:products,barcode,{$product->id}"
        ];

        if ($user->isSuperAdmin()) {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        $request->validate($rules);

        $productData = $request->only(['name', 'category_id', 'description', 'stock', 'barcode']);
        if ($user->isSuperAdmin()) {
            $productData['branch_id'] = $request->branch_id;
        }

        // Stock available_stock adjust automatically inside model boot update callback

        $product->update($productData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin() && $product->branch_id !== $user->branch_id) {
            abort(403, 'Aksi tidak diizinkan. Barang ini milik cabang lain.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function exportCSV()
    {
        $user = auth()->user();
        $query = Product::with(['category', 'branch']);

        if (!$user->isSuperAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        $products = $query->get();

        $fileName = 'inventaris_' . ($user->branch ? strtolower($user->branch->code) : 'semua_cabang') . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Barcode', 'Nama Barang', 'Kategori', 'Kode Kategori', 'Cabang', 'Deskripsi', 'Stok Total', 'Stok Tersedia'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding of special characters
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->barcode,
                    $product->name,
                    $product->category->name,
                    $product->category->code,
                    $product->branch->name,
                    $product->description,
                    $product->stock,
                    $product->available_stock
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF()
    {
        $user = auth()->user();
        $query = Product::with(['category', 'branch']);

        if (!$user->isSuperAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        $products = $query->get();
        $branchName = $user->branch ? $user->branch->name : 'Semua Cabang';

        return view('products.pdf', compact('products', 'branchName'));
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membuka file CSV.');
        }

        // Clean UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau format salah.');
        }

        // clean columns
        $header = array_map(function($h) {
            return trim(strtolower($h));
        }, $header);

        $rowNum = 1;
        $successCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;
                
                // Pad or trim row if size mismatch
                if (count($header) !== count($row)) {
                    $errors[] = "Baris {$rowNum}: Keselarasan kolom tidak sama dengan header.";
                    continue;
                }

                $data = array_combine($header, $row);

                $name = $data['name'] ?? null;
                $categoryCode = $data['category_code'] ?? null;
                $description = $data['description'] ?? '';
                $stock = isset($data['stock']) ? intval($data['stock']) : 0;

                if (empty($name)) {
                    $errors[] = "Baris {$rowNum}: Nama barang kosong.";
                    continue;
                }

                $category = Category::where('code', strtoupper($categoryCode))->first();
                if (!$category) {
                    $errors[] = "Baris {$rowNum}: Kategori dengan kode '{$categoryCode}' tidak ditemukan.";
                    continue;
                }

                if (auth()->user()->isSuperAdmin()) {
                    $branchCode = $data['branch_code'] ?? null;
                    $branch = Branch::where('code', strtoupper($branchCode))->first();
                    if (!$branch) {
                        $errors[] = "Baris {$rowNum}: Cabang dengan kode '{$branchCode}' tidak ditemukan.";
                        continue;
                    }
                    $branchId = $branch->id;
                } else {
                    $branchId = auth()->user()->branch_id;
                }

                Product::create([
                    'name' => $name,
                    'category_id' => $category->id,
                    'branch_id' => $branchId,
                    'description' => $description,
                    'stock' => $stock,
                    'available_stock' => $stock
                ]);

                $successCount++;
            }

            if (count($errors) > 0) {
                DB::rollBack();
                fclose($handle);
                return back()->withErrors($errors)->with('error', 'Beberapa baris data gagal divalidasi. Proses impor dibatalkan.');
            }

            DB::commit();
            fclose($handle);
            return back()->with('success', "Berhasil mengimpor {$successCount} produk ke database.");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Terjadi kesalahan impor: ' . $e->getMessage());
        }
    }
}
