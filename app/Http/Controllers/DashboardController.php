<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Hitung pendapatan bulan ini
            $pendapatan_bulan_ini = Pemesanan::whereMonth('created_at', now()->month)
                ->where('status_pembayaran', 'valid')
                ->sum('harga');

            // Data untuk grafik admin - 6 bulan terakhir
            $chart_data = collect();
            $chart_labels = collect();
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $chart_labels->push($month->format('M Y'));
                $chart_data->push(Pemesanan::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count());
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
                'pemesanan_terbaru' => Pemesanan::with(['mitra', 'kategoriCor'])
                    ->latest()
                    ->take(5)
                    ->get(),
                'pendapatan_bulan_ini' => $pendapatan_bulan_ini,
                'chart_labels' => $chart_labels,
                'chart_data' => $chart_data,
            ];
            return view('dashboard.admin', $data);
        } else {
            $mitraId = auth()->user()->mitra->id;

            // Hitung total pembayaran mitra
            $total_pembayaran = Pemesanan::where('mitra_id', $mitraId)
                ->where('status_pembayaran', 'lunas')
                ->sum('harga');

            // Data untuk grafik mitra - 6 bulan terakhir
            $chart_data = collect();
            $chart_labels = collect();
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $chart_labels->push($month->format('M Y'));
                $chart_data->push(Pemesanan::where('mitra_id', $mitraId)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count());
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
                'chart_labels' => $chart_labels,
                'chart_data' => $chart_data,
            ];
            return view('dashboard.mitra', $data);
        }
    }
}
