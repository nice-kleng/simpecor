@extends('layouts.app')

@section('title', 'Buat Pemesanan')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pemesanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori Cor</label>
                            <select name="kategori_cor_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoriCors as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Pengecoran</label>
                            <input type="date" name="tanggal_pengecoran" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Luas Cor (m²)</label>
                            <input type="number" step="0.01" name="luas_cor" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Volume Cor (m³)</label>
                            <input type="number" step="0.01" name="volume_cor" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Foto Lokasi</label>
                    <input type="file" name="foto_lokasi" class="form-control" required>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">Alamat Lengkap Lokasi Proyek</label>
                    <textarea name="alamat_lokasi" id="alamat_lokasi" rows="5" class="form-control"></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pemesanan.index') }}" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
