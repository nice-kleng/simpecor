@extends('layouts.app', ['title' => 'Komposisi Cor', 'pageDescrition' => 'Kompisi Cor Type ' . $kategori->nama_kategori . ' untuk setiap m³'])

@section('action-button')
    <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalKomposisi">Tambah
        Bahan</a>
    <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-secondary" title="Kembali">Kembali</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bahan Baku</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th><i class="fas fa-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori->komposisi as $komposisi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $komposisi->bahanBaku->nama_bahan }}
                                        </td>
                                        <td>{{ $komposisi->bahanBaku->satuan }}</td>
                                        <td>{{ $komposisi->jumlah }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $komposisi->id }}"
                                                class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('komposisi.destroy', $komposisi->id) }}" method="POST"
                                                class="d-inline" id="deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"><i
                                                        class="fas fa-trash"></i></button>
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
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="modalKomposisi" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Komposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('komposisi.store') }}" method="POST" id="formKomposisi">
                    @csrf
                    <div id="method"></div>
                    <div class="modal-body">
                        <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="bahan_baku" class="form-label">Bahan Baku</label>
                            <select name="bahan_baku" id="bahan_baku" class="form-select">
                                <option value="" selected disabled>Pilih Bahan Baku</option>
                                @foreach ($bahanbakus as $bahanbaku)
                                    <option value="{{ $bahanbaku->id }}">
                                        {{ $bahanbaku->nama_bahan . ' (' . $bahanbaku->satuan . ')' }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah per (m³)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const modal = $('#modalKomposisi');
            const form = $('#formKomposisi');

            // Reset form ketika model ditutup
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('kategori.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
            });

            // Edit button
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');
                const url = `{{ route('komposisi.edit', ['id' => ':id']) }}`.replace(':id', id);
                $.get(url)
                    .done(function(data) {
                        modal.modal('show');
                        modal.find('.modal-title').text('Edit Komposisi');
                        form.attr('action', "{{ route('komposisi.update', ['id' => ':id']) }}".replace(
                            ':id',
                            id));
                        $('#method').html('@method('PUT')');

                        form.find('[name="id"]').val(data.id);
                        form.find('[name="bahan_baku"]').val(data.bahan_baku_id);
                        form.find('[name="jumlah"]').val(data.jumlah);

                        modal.modal('show');
                    });
            });

            // Konfirmasi hapus data
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: "Are you sure?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            visible: true,
                            text: "Batal",
                            className: "btn btn-danger",
                        },
                        confirm: {
                            text: "Hapus",
                            className: "btn btn-success",
                        },
                    },
                }).then((willDelete) => {
                    if (willDelete) {
                        deleteForm.submit();
                    } else {
                        swal("Hapus data dibatalkan!", {
                            buttons: {
                                confirm: {
                                    className: "btn btn-success",
                                },
                            },
                        });
                    }
                });
            });
        });
    </script>
@endpush
