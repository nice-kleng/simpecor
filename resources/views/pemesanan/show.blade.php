@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Pemesanan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Nama Mitra</th>
                                <td>{{ $pemesanan->mitra->nama_mitra }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Cor</th>
                                <td>{{ $pemesanan->kategoriCor->nama_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Luas Cor</th>
                                <td>{{ $pemesanan->luas_cor }} m²</td>
                            </tr>
                            <tr>
                                <th>Volume Cor</th>
                                <td>{{ $pemesanan->volume_cor }} m³</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengecoran</th>
                                <td>{{ date('d F Y', strtotime($pemesanan->tanggal_pengecoran)) }}</td>
                            </tr>
                            @if ($pemesanan->harga)
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($pemesanan->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Unit Cor</th>
                                    <td>{{ $pemesanan->jumlah_unit_cor }} Unit</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Petugas</th>
                                    <td>{{ $pemesanan->jumlah_petugas }} Orang</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $pemesanan->status_pembayaran == 'valid' ? 'success' : ($pemesanan->status_pembayaran == 'invalid' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($pemesanan->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status Pengerjaan</th>
                                <td><span class="badge bg-info">{{ ucfirst($pemesanan->status_pengerjaan) }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Foto Lokasi</h4>
                </div>
                <div class="card-body">
                    <img src="{{ Storage::url($pemesanan->foto_lokasi) }}" alt="Foto Lokasi" class="img-fluid rounded">
                </div>
            </div>

            @if ($pemesanan->bukti_pembayaran)
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Bukti Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <img src="{{ Storage::url($pemesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                            class="img-fluid rounded">
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (auth()->user()->role === 'admin' && $pemesanan->status_pengerjaan === 'menunggu_verifikasi')
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Verifikasi Pemesanan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pemesanan.verify', $pemesanan) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="harga" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Unit Cor</label>
                                <input type="number" name="jumlah_unit_cor" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Petugas</label>
                                <input type="number" name="jumlah_petugas" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Verifikasi Pemesanan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($pemesanan->status_pengerjaan === 'disetujui' && !$pemesanan->bukti_pembayaran)
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Upload Bukti Pembayaran</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pemesanan.upload-bukti', $pemesanan) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" required>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Upload Bukti Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if (auth()->user()->role === 'admin' && $pemesanan->bukti_pembayaran && $pemesanan->status_pembayaran === 'pending')
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Verifikasi Pembayaran</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pemesanan.verify-pembayaran', $pemesanan) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Keterangan (Wajib diisi jika pembayaran invalid)</label>
                        <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="btn-group">
                        <button type="submit" name="status" value="valid" class="btn btn-success">Valid</button>
                        <button type="submit" name="status" value="invalid" class="btn btn-danger">Invalid</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($pemesanan->status_pembayaran === 'invalid')
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Pembayaran Invalid</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <h5>Alasan Pembayaran Invalid:</h5>
                    <p>{{ $pemesanan->keterangan_pembayaran }}</p>
                </div>

                @if (auth()->user()->role === 'mitra')
                    <form action="{{ route('pemesanan.upload-bukti', $pemesanan) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Upload Ulang Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" required>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Upload Ulang Bukti Pembayaran</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

    @if (auth()->user()->role === 'admin' && $pemesanan->status_pengerjaan === 'proses_pengerjaan')
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Update Status Pengerjaan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pemesanan.update-status', $pemesanan) }}" method="POST">
                    @csrf
                    <button type="submit" name="status" value="selesai" class="btn btn-success">Selesai</button>
                </form>
            </div>
        </div>
    @endif
@endsection
