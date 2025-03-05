<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::all();
        return view('admin.bahan_baku', compact('bahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'stok' => 'required|integer',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        Bahan::create($request->all());
        return redirect()->route('bahan.index')->with('success', 'Bahan Baku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahan $bahan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahan $bahan)
    {
        return response()->json($bahan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'stok' => 'required|integer',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        $bahan->update($request->all());
        return redirect()->route('bahan.index')->with('success', 'Bahan Baku berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahan $bahan)
    {
        $bahan->delete();
        return redirect()->route('bahan.index')->with('success', 'Bahan Baku berhasil dihapus');
    }
}
