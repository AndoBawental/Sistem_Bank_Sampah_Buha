<?php
// app/Http/Controllers/Penjualan/PembeliController.php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembeli::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }
        
        $pembeli = $query->orderBy('nama')->paginate(10);
        
        return view('pages.penjualan.pembeli.index', compact('pembeli'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.penjualan.pembeli.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20'
        ]);

        Pembeli::create($request->all());

        return redirect()->route('penjualan.pembeli.index')
            ->with('success', 'Pembeli berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        return view('pages.penjualan.pembeli.edit', compact('pembeli'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20'
        ]);

        $pembeli = Pembeli::findOrFail($id);
        $pembeli->update($request->all());

        return redirect()->route('penjualan.pembeli.index')
            ->with('success', 'Pembeli berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        
        // Check if has related sales
        if ($pembeli->penjualan()->exists()) {
            return back()->with('error', 'Pembeli tidak dapat dihapus karena sudah memiliki riwayat penjualan.');
        }
        
        $pembeli->delete();

        return redirect()->route('penjualan.pembeli.index')
            ->with('success', 'Pembeli berhasil dihapus.');
    }
}