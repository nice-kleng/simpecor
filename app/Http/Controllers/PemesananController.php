<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\KategoriCor;
use App\Models\Mitra;
use App\Models\Pembayaran;
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
            'tanggal_pengecoran' => $validated['tanggal_pengecoran'],
            'alamat_lokasi' => $request->alamat_lokasi,
            'jenis_pembayaran' => $request->jenis_pembayaran,
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
            'pj_lapangan' => 'required',
            'jumlah_unit_cor' => 'required|numeric',
            'jumlah_petugas' => 'required|numeric',
        ]);

        $pemesanan->update([
            'status_pengerjaan' => 'disetujui',
            'harga' => $validated['harga'],
            'pj_lapangan' => $validated['pj_lapangan'],
            'jumlah_unit_cor' => $validated['jumlah_unit_cor'],
            'jumlah_petugas' => $validated['jumlah_petugas'],
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil diverifikasi');
    }

    public function uploadBuktiPembayaran(Pemesanan $pemesanan, Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image',
            'jumlah_pembayaran' => 'required|numeric',
        ]);

        $bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

        if ($this->cekLunas($pemesanan)) {
            return redirect()->back()->with('warning', 'Pembayaran sudah lunas');
        }

        if ($pemesanan->pembayaran->count() > 0) {
            $pemesanan->update([
                'status_pembayaran' => 'angsur',
            ]);
        }

        Pembayaran::create([
            'pemesanan_id' => $pemesanan->id,
            'jumlah_pembayaran' => $request->jumlah_pembayaran,
            'bukti_pembayaran' => $bukti_pembayaran,
            'status_pembayaran' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload');
    }

    public function verifyPembayaran(Pembayaran $pembayaran, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:valid,invalid',
            'keterangan' => $request->status === 'invalid' ? 'required|string' : 'nullable|string'
        ]);

        $pembayaran->update([
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ]);

        if ($validated['status'] === 'valid') {
            $pemesanan = $pembayaran->pemesanan;
            if ($this->cekLunas($pemesanan)) {
                $pemesanan->update([
                    'status_pembayaran' => 'paid'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate');
    }

    protected function cekLunas($pemesanan)
    {
        $totalPembayaran = $pemesanan->pembayaran()->where('status', 'valid')->sum('jumlah_pembayaran');
        $totalHarga = $pemesanan->getHarga();

        return $totalPembayaran >= $totalHarga;
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
