@extends('layouts.app', ['title' => 'Bahan Baku', 'pageDescrition' => 'Management Bahan Baku'])

@if (auth()->user()->role == 'admin')
    @section('action-button')
        <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalBahan">Tambah
            Bahan Baku</a>
    @endsection
@endif

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
                                    <th>Nama Bahan Baku</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th>Batas Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahans as $bahan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bahan->nama_bahan }}</td>
                                        <td>{{ $bahan->stok }}</td>
                                        <td>{{ $bahan->satuan }}</td>
                                        <td>{{ $bahan->batas_stok }}</td>
                                        <td>
                                            @if (auth()->user()->role == 'admin')
                                                <a href="javascript:void(0)" data-id="{{ $bahan->id }}"
                                                    class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('bahan.destroy', $bahan->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-delete"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
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
    <div class="modal fade" id="modalBahan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formBahan" action="{{ route('bahan.store') }}" method="POST">
                        @csrf
                        <div id="method"></div>
                        <div class="mb-3">
                            <label for="nama_bahan" class="form-label">Nama Bahan Baku</label>
                            <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="batas_stok" class="form-label">Batas Stok</label>
                            <input type="number" class="form-control" id="batas_stok" name="batas_stok" required>
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
            const modal = $('#modalBahan');
            const form = $('#formBahan');

            // Initialize DataTable
            $('.datatable').DataTable();

            // Reset form when modal is hidden
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('bahan.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
                modal.find('.modal-title').text('Tambah Bahan Baku');
                form.find('[name="stok"]').prop('readonly', false);
            });

            // Edit button handler
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');
                const url = `{{ route('bahan.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    modal.find('.modal-title').text('Edit Bahan Baku');
                    form.attr('action', `/bahan/${id}`);
                    $('#method').html('@method('PUT')');

                    // Fill form data
                    form.find('[name="nama_bahan"]').val(data.nama_bahan);
                    form.find('[name="stok"]').val(data.stok).prop('readonly', true);
                    form.find('[name="batas_stok"]').val(data.batas_stok);
                    form.find('[name="satuan"]').val(data.satuan);
                    // form.find('[name="harga"]').val(data.harga);

                    modal.modal('show');
                });
            });

            // Delete confirmation with SweetAlert2
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
