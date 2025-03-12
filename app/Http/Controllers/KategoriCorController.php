<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\KategoriCor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriCorController extends Controller
{
    public function index()
    {
        $kategoris = KategoriCor::all();
        return view('admin.kategori_cor', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'required'
        ]);

        KategoriCor::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(KategoriCor $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriCor $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'required'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(KategoriCor $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
