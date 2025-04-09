<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\KategoriCor;
use App\Models\Mitra;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PemesananController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'mitra') {
            $pemesanans = Pemesanan::with(['mitra', 'kategoriCor'])
                ->where('mitra_id', auth()->user()->mitra->id)
                ->latest()
                ->get();
        } else {
            $pemesanans = Pemesanan::with(['mitra', 'kategoriCor'])->latest()->get();
        }
        return view('pemesanan.index', compact('pemesanans'));
    }

    public function create()
    {
        $kategoriCors = KategoriCor::all();
        return view('pemesanan.create', compact('kategoriCors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_cor_id' => 'required',
            'luas_cor' => 'required|numeric',
            'volume_cor' => 'required|numeric',
            'foto_lokasi' => 'required|image',
            'tanggal_pengecoran' => 'required|date'
        ]);

        $foto_lokasi = $request->file('foto_lokasi')->store('foto-lokasi', 'public');

        $pemesanan = Pemesanan::create([
            'mitra_id' => auth()->user()->mitra->id,
            'kategori_cor_id' => $validated['kategori_cor_id'],
            'luas_cor' => $validated['luas_cor'],
            'volume_cor' => $validated['volume_cor'],
            'foto_lokasi' => $foto_lokasi,
            'tanggal_pengecoran' => $validated['tanggal_pengecoran']
        ]);

        return redirect()->route('pemesanan.show', $pemesanan)->with('success', 'Pemesanan berhasil dibuat');
    }

    public function show(Pemesanan $pemesanan)
    {
        return view('pemesanan.show', compact('pemesanan'));
    }

    public function verify(Pemesanan $pemesanan, Request $request)
    {
        $validated = $request->validate([
            'harga' => 'required|numeric',
            'jumlah_unit_cor' => 'required|numeric',
            'jumlah_petugas' => 'required|numeric',
        ]);

        $pemesanan->update([
            'status_pengerjaan' => 'disetujui',
            'harga' => $validated['harga'],
            'jumlah_unit_cor' => $validated['jumlah_unit_cor'],
            'jumlah_petugas' => $validated['jumlah_petugas'],
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil diverifikasi');
    }

    public function uploadBuktiPembayaran(Pemesanan $pemesanan, Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image'
        ]);

        $bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        $pemesanan->update([
            'bukti_pembayaran' => $bukti_pembayaran,
            'status_pembayaran' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload');
    }

    public function verifyPembayaran(Pemesanan $pemesanan, Request $request)
    {
        DB::beginTransaction();
        try {
            $status = $request->status;
            $keterangan = $request->keterangan;

            if ($status == 'valid') {
                // Load komposisi dengan bahan baku
                $komposisis = $pemesanan->kategoriCor->komposisi()->with('bahanBaku')->get();

                foreach ($komposisis as $komposisi) {
                    // Hitung total pengurangan: jumlah komposisi per m³ * volume cor
                    $pengurangan = $komposisi->jumlah * $pemesanan->volume_cor;

                    // Update stok bahan baku
                    $komposisi->bahanBaku->update([
                        'stok' => $komposisi->bahanBaku->stok - $pengurangan
                    ]);
                }
            }

            $pemesanan->update([
                'status_pembayaran' => $status,
                'status_pengerjaan' => $status == 'valid' ? 'proses_pengerjaan' : 'disetujui',
                'keterangan_pembayaran' => $status == 'invalid' ? $keterangan : null
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function updateStatus(Pemesanan $pemesanan, Request $request)
    {
        $pemesanan->update([
            'status_pengerjaan' => $request->status,
            'catatan_pengerjaan' => $request->catatan_pengerjaan,
            'tanggal_selesai' => $request->status == 'selesai' ? now() : null
        ]);
        return redirect()->back()->with('success', 'Status pengerjaan berhasil diupdate');
    }

    public function downloadSuratJalan(Pemesanan $pemesanan)
    {
        $pemesanan->load(['mitra', 'kategoriCor']);
        $pdf = Pdf::loadView('pemesanan.surat-jalan', compact('pemesanan'));
        $pdf->setPaper('A4', 'portrait');
        $filename = 'surat_jalan_' . $pemesanan->id . '_' . date('Ymd') . '.pdf';
        return $pdf->stream($filename);
    }

    public function historyPemesanan(Request $request)
    {
        $mitras = Mitra::orderBy('nama_mitra', 'asc')->get();
        $kategoriCors = KategoriCor::orderBy('nama_kategori', 'asc')->get();

        $query = Pemesanan::with(['mitra', 'kategoriCor'])
            ->where('status_pengerjaan', 'selesai');

        // Filter berdasarkan mitra
        if ($request->filled('mitra')) {
            $query->where('mitra_id', $request->mitra);
        }

        // Filter berdasarkan kategori cor
        if ($request->filled('kategoriCor')) {
            $query->where('kategori_cor_id', $request->kategoriCor);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $data = $query->latest()->get();

        return view('pemesanan.history-pemesanan', compact(['data', 'mitras', 'kategoriCors']));
    }

    public function exportHistoryPDF(Request $request)
    {
        $query = Pemesanan::with(['mitra', 'kategoriCor'])
            ->where('status_pengerjaan', 'selesai');

        // Filter berdasarkan mitra
        if ($request->filled('mitra')) {
            $query->where('mitra_id', $request->mitra);
        }

        if ($request->filled('kategoriCor')) {
            $query->where('kategori_cor_id', $request->kategoriCor);
        }

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $data = $query->latest()->get();

        $filterInfo = [];
        if ($request->filled('mitra')) {
            $mitra = Mitra::find($request->mitra);
            $filterInfo[] = 'Mitra: ' . ($mitra ? $mitra->nama_mitra : 'Unknown');
        }

        if ($request->filled('kategoriCor')) {
            $kategori = KategoriCor::find($request->kategoriCor);
            $filterInfo[] = 'Kategori: ' . ($kategori ? $kategori->nama_kategori : 'Unknown');
        }

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $filterInfo[] = 'Periode: ' . $request->tanggal_awal . ' sampai ' . $request->tanggal_akhir;
        }

        $pdf = PDF::loadView('pemesanan.export-history-pdf', compact('data', 'filterInfo'));
        $pdf->setPaper('A4', 'landscape');
        $filename = 'history_pemesanan_' . date('YmdHis') . '.pdf';

        return $pdf->stream($filename);
    }
}
