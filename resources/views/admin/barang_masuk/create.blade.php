@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Barang Masuk</h3>
                <div class="card-tools">
                    <a href="{{ route('barang-masuk.index') }}" class="btn btn-primary">
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('barang-masuk.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bahan_id">Bahan</label>
                                <select name="bahan_id" id="bahan_id"
                                    class="form-control @error('bahan_id') is-invalid @enderror" required>
                                    <option value="">Pilih Bahan</option>
                                    @foreach ($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                                    @endforeach
                                </select>
                                @error('bahan_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="form-control @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah"
                                    class="form-control @error('jumlah') is-invalid @enderror" required min="1"
                                    onchange="hitungTotal()">
                                @error('jumlah')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga">Harga Satuan</label>
                                <input type="number" name="harga" id="harga"
                                    class="form-control @error('harga') is-invalid @enderror" required min="0"
                                    onchange="hitungTotal()">
                                @error('harga')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="text" id="total" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror" required
                                    value="{{ date('Y-m-d') }}">
                                @error('tanggal')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                    rows="3"></textarea>
                                @error('keterangan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function hitungTotal() {
                const jumlah = document.getElementById('jumlah').value || 0;
                const harga = document.getElementById('harga').value || 0;
                const total = jumlah * harga;
                document.getElementById('total').value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(total);
            }
        </script>
    @endpush
@endsection
