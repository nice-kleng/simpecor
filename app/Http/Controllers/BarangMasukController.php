<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuk = BarangMasuk::with(['bahan', 'supplier', 'user'])->latest()->get();
        return view('admin.barang_masuk.barang_masuk', compact('barangMasuk'));
    }

    public function create()
    {
        $bahans = Bahan::all();
        $suppliers = Supplier::all();
        return view('admin.barang_masuk.create', compact('bahans', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required',
            'supplier_id' => 'required',
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $kodeTransaksi = 'BM-' . date('YmdHis');
        $total = $request->jumlah * $request->harga;

        BarangMasuk::create([
            'kode_transaksi' => $kodeTransaksi,
            'bahan_id' => $request->bahan_id,
            'supplier_id' => $request->supplier_id,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'total' => $total,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function updateStatus(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $barangMasuk->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            $bahan = Bahan::find($barangMasuk->bahan_id);
            $bahan->stok += $barangMasuk->jumlah;
            $bahan->save();
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Status berhasil diperbarui');
    }
}
