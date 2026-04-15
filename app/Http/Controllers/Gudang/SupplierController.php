<?php
// app/Http/Controllers/Gudang/SupplierController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Penerimaan;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::withCount('penerimaan');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }
        
        $perPage = $request->get('per_page', 10);
        $suppliers = $query->orderBy('nama')->paginate($perPage)->withQueryString();
        
        // Total penerimaan
        $totalPenerimaan = Penerimaan::count();
        
        return view('dashboard.gudang.supplier.index', compact('suppliers', 'totalPenerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.gudang.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:supplier,nama',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20'
        ], [
            'nama.unique' => 'Nama supplier sudah ada, gunakan nama lain.'
        ]);

        Supplier::create($request->all());

        return redirect()->route('gudang.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('dashboard.gudang.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:supplier,nama,' . $id,
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20'
        ], [
            'nama.unique' => 'Nama supplier sudah ada, gunakan nama lain.'
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('gudang.supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::withCount('penerimaan')->findOrFail($id);
        
        // Cek apakah supplier punya data penerimaan
        if ($supplier->penerimaan_count > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki data penerimaan.');
        }
        
        $supplier->delete();

        return redirect()->route('gudang.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}