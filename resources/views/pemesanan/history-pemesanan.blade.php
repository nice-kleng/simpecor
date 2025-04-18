@extends('layouts.app', ['title' => 'Detail Pemesanan', 'pageDescrition' => 'Detail Pemesanan'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#filterModal">
                        Filter Data
                    </button>
                    <a href="{{ route('pemesanan.export-history', request()->query()) }}" class="btn btn-success mb-3 ms-2">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-history">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mitra</th>
                                    <th>Kategori Cor</th>
                                    <th>Luas</th>
                                    <th>Volume Cor</th>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Harga</th>
                                    <th>Tanggal Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->mitra->nama_mitra }}</td>
                                        <td>{{ $item->kategoriCor->nama_kategori }}</td>
                                        <td>{{ $item->luas_cor }} m²</td>
                                        <td>{{ $item->volume_cor }} m³</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                        </td>
                                        <td>
                                            {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Data Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="GET">
                        <div class="mb-3">
                            <label for="mitra" class="form-label">Mitra</label>
                            <select name="mitra" id="mitra" class="form-control">
                                <option value="">Semua Mitra</option>
                                @foreach ($mitras as $mitra)
                                    <option value="{{ $mitra->id }}">{{ $mitra->nama_mitra }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategoriCor" class="form-label">Kategori Cor</label>
                            <select name="kategoriCor" id="kategoriCor" class="form-control">
                                <option value="">Semua Kategori Cor</option>
                                @foreach ($kategoriCors as $kategoriCor)
                                    <option value="{{ $kategoriCor->id }}">{{ $kategoriCor->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
