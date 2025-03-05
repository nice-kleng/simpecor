@extends('layouts.app', ['title' => 'Kategori Cor', 'pageDescrition' => 'Management Kategori Cor'])

@section('action-button')
    <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalKategori">Tambah
        Kategori</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $kategori)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>{{ $kategori->slug }}</td>
                                        <td>{{ $kategori->harga }}</td>
                                        <td>{{ $kategori->deskripsi }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $kategori->id }}"
                                                class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                                class="d-inline">
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
    <div class="modal fade" id="modalKategori" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formKategori" action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div id="method"></div>
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const modal = $('#modalKategori');
            const form = $('#formKategori');

            // Initialize DataTable
            $('.datatable').DataTable();

            // Reset form when modal is hidden
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('kategori.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
            });

            // Edit button handler
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');
                const url = `{{ route('kategori.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    modal.find('.modal-title').text('Edit Kategori');
                    form.attr('action', `/kategori/${id}`);
                    $('#method').html('@method('PUT')');

                    // Fill form data
                    form.find('[name="nama_kategori"]').val(data.nama_kategori);
                    form.find('[name="harga"]').val(data.harga);
                    form.find('[name="deskripsi"]').val(data.deskripsi);

                    modal.modal('show');
                });
            });

            // Delete confirmation
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const deleteForm = $(this).closest('form');

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
