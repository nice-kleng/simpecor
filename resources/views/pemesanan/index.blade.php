@extends('layouts.app')

@section('title', 'Daftar Pemesanan')

@if (auth()->user()->role === 'mitra')
    @section('action-button')
        <a href="{{ route('pemesanan.create') }}" class="btn btn-primary btn-round">
            <i class="fas fa-plus"></i> Buat Pemesanan
        </a>
    @endsection
@endif

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mitra</th>
                            <th>Kategori</th>
                            <th>Tanggal Pengecoran</th>
                            <th>Harga</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pengerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanans as $pemesanan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pemesanan->mitra->nama_mitra }}</td>
                                <td>{{ $pemesanan->kategoriCor->nama_kategori }}</td>
                                <td>{{ $pemesanan->tanggal_pengecoran }}</td>
                                <td>
                                    Rp. {{ number_format($pemesanan->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge {{ $pemesanan->status_pembayaran_label['class'] }}">
                                        {{ $pemesanan->status_pembayaran_label['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($pemesanan->status_pengerjaan) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('pemesanan.show', $pemesanan) }}" class="btn btn-link btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
