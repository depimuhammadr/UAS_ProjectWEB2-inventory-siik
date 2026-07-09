<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Division;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        // Only superadmins can manage branches & divisions
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Aksi tidak diizinkan. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $branches = Branch::withCount(['users', 'products'])->get();
        $divisions = Division::withCount('users')->get();

        return view('master.index', compact('branches', 'divisions'));
    }

    public function storeBranch(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:branches,name',
            'code' => 'required|string|max:10|unique:branches,code',
            'address' => 'nullable|string',
        ]);

        Branch::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'address' => $request->address,
        ]);

        return redirect()->route('master.index')->with('success', 'Cabang baru berhasil didaftarkan.');
    }

    public function storeDivision(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
        ]);

        Division::create([
            'name' => $request->name,
        ]);

        return redirect()->route('master.index')->with('success', 'Divisi baru berhasil ditambahkan.');
    }
}
