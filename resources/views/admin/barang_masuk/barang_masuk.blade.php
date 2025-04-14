@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Barang Masuk</h3>
                <div class="card-tools">
                    <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary">
                        Tambah Barang Masuk
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Bahan</th>
                                <th>Supplier</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                {{-- <th>Status</th> --}}
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_transaksi }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->bahan->nama_bahan }}</td>
                                    <td>{{ $item->supplier->nama_supplier }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->harga) }}</td>
                                    <td>Rp {{ number_format($item->total) }}</td>
                                    {{-- <td>
                                        <span
                                            class="badge badge-{{ $item->status == 'pending' ? 'warning' : ($item->status == 'approved' ? 'success' : 'danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td> --}}
                                    <td>
                                        {{ $item->keterangan }}
                                    </td>
                                    {{-- <td>
                                        @if ($item->status == 'pending')
                                            <form action="{{ route('barang-masuk.status', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('barang-masuk.status', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        @endif
                                    </td> --}}
                                    <td>
                                        <a href="{{ route('barang-masuk.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('barang-masuk.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
