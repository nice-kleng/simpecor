<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\Bahan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'direktur') {
            return $this->adminDashboard();
        } else {
            return $this->mitraDashboard();
        }
    }

    protected function adminDashboard()
    {
        $currentMonth = now()->format('Y-m');

        // Pendapatan bulan ini (pembayaran valid)
        $pendapatan_bulan_ini = Pembayaran::where('status', 'valid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah_pembayaran');

        // Tunggakan bulan ini (pemesanan belum lunas)
        $tagihan_bulan_ini = Pemesanan::where('status_pembayaran', '!=', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->sum(function ($pemesanan) {
                $total_bayar = $pemesanan->pembayaran->where('status', 'valid')->sum('jumlah_pembayaran');
                return max($pemesanan->harga - $total_bayar, 0);
            });

        // Data untuk grafik - pendapatan dan pemesanan 6 bulan terakhir
        $chart_data = collect();
        $chart_pendapatan = collect();
        $chart_labels = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chart_labels->push($month->format('M Y'));

            // Jumlah pemesanan
            $chart_data->push(Pemesanan::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count());

            // Pendapatan
            $chart_pendapatan->push(Pembayaran::where('status', 'valid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('jumlah_pembayaran'));
        }

        $data = [
            'total_mitra' => User::where('role', 'mitra')->count(),
            'total_pemesanan' => Pemesanan::count(),
            'pemesanan_pending' => Pemesanan::where('status_pengerjaan', 'menunggu_verifikasi')->count(),
            'pemesanan_proses' => Pemesanan::where('status_pengerjaan', 'proses_pengerjaan')->count(),
            'total_bahan' => Bahan::count(),
            'total_barang_masuk' => BarangMasuk::count(),
            'pengeluaran_bulan_ini' => BarangMasuk::whereMonth('tanggal', now()->month)
                ->sum('total'),
            'bahan_menipis' => Bahan::where('stok', '<=', DB::raw('batas_stok'))->get(),
            'pemesanan_terbaru' => Pemesanan::with(['mitra.user', 'kategoriCor'])
                ->latest()
                ->take(5)
                ->get(),
            'pendapatan_bulan_ini' => $pendapatan_bulan_ini,
            'tagihan_bulan_ini' => $tagihan_bulan_ini,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'chart_pendapatan' => $chart_pendapatan,
        ];

        return view('dashboard.admin', $data);
    }

    protected function mitraDashboard()
    {
        $mitraId = auth()->user()->mitra->id;
        $currentMonth = now()->format('Y-m');

        // Pendapatan bulan ini (pembayaran valid)
        $pendapatan_bulan_ini = Pembayaran::whereHas('pemesanan', function ($query) use ($mitraId) {
            $query->where('mitra_id', $mitraId);
        })
            ->where('status', 'valid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah_pembayaran');

        // Tunggakan bulan ini (pemesanan belum lunas)
        $tunggakan_bulan_ini = Pemesanan::where('mitra_id', $mitraId)
            ->where('status_pembayaran', '!=', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->sum(function ($pemesanan) {
                $total_bayar = $pemesanan->pembayaran->where('status', 'valid')->sum('jumlah_pembayaran');
                return max($pemesanan->harga - $total_bayar, 0);
            });

        // Total pembayaran (semua yang sudah lunas)
        $total_pembayaran = Pemesanan::where('mitra_id', $mitraId)
            ->where('status_pembayaran', 'paid')
            ->sum('harga');

        // Data untuk grafik - 6 bulan terakhir
        $chart_data = collect();
        $chart_pendapatan = collect();
        $chart_labels = collect();

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chart_labels->push($month->format('M Y'));

            // Jumlah pemesanan
            $chart_data->push(Pemesanan::where('mitra_id', $mitraId)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count());

            // Pendapatan
            $chart_pendapatan->push(Pembayaran::whereHas('pemesanan', function ($query) use ($mitraId) {
                $query->where('mitra_id', $mitraId);
            })
                ->where('status', 'valid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('jumlah_pembayaran'));
        }

        $data = [
            'total_pemesanan' => Pemesanan::where('mitra_id', $mitraId)->count(),
            'pemesanan_pending' => Pemesanan::where('mitra_id', $mitraId)
                ->where('status_pengerjaan', 'menunggu_verifikasi')
                ->count(),
            'pemesanan_proses' => Pemesanan::where('mitra_id', $mitraId)
                ->where('status_pengerjaan', 'proses_pengerjaan')
                ->count(),
            'pemesanan_selesai' => Pemesanan::where('mitra_id', $mitraId)
                ->where('status_pengerjaan', 'selesai')
                ->count(),
            'pemesanan_terbaru' => Pemesanan::where('mitra_id', $mitraId)
                ->with('kategoriCor')
                ->latest()
                ->take(5)
                ->get(),
            'total_pembayaran' => $total_pembayaran,
            'pendapatan_bulan_ini' => $pendapatan_bulan_ini,
            'tunggakan_bulan_ini' => $tunggakan_bulan_ini,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'chart_pendapatan' => $chart_pendapatan,
        ];

        return view('dashboard.mitra', $data);
    }
}
