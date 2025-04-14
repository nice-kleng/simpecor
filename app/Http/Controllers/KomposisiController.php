<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\KategoriCor;
use App\Models\Komposisi;
use Illuminate\Http\Request;

class KomposisiController extends Controller
{
    public function create(string $id)
    {
        $kategori = KategoriCor::find($id);
        $bahanbakus = Bahan::orderBy('nama_bahan', 'asc')->get();
        return view('admin.komposisi', compact('kategori', 'bahanbakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_cors,id',
            'bahan_baku' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric',
        ]);

        $kategori = KategoriCor::find($request->kategori_id);
        $kategori->komposisi()->create([
            'kategori_id' => $request->kategori_id,
            'bahan_baku_id' => $request->bahan_baku,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('komposisi.create', $request->kategori_id)
            ->with('success', 'Komposisi berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $komposisi = Komposisi::with('kategoriCor', 'bahanBaku')->find($id);
        return response()->json($komposisi);
    }

    public function update(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_cors,id',
            'bahan_baku' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $komposisi = Komposisi::find($request->id);
        $komposisi->update([
            'kategori_id' => $request->kategori_id,
            'bahan_baku_id' => $request->bahan_baku,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('komposisi.create', $request->kategori_id)
            ->with('success', 'Komposisi berhasil diubah');
    }

    public function destroy(string $id)
    {
        $komposisi = Komposisi::find($id);
        $kategori_id = $komposisi->kategoriCor->id;
        $komposisi->delete();

        return redirect()->route('komposisi.create', $kategori_id)
            ->with('success', 'Komposisi berhasil dihapus');
    }
}
