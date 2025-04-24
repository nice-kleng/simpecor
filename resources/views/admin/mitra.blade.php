@extends('layouts.app', ['title' => 'Mitra', 'pageDescrition' => 'Mitra Management'])

@if (auth()->user()->role == 'admin')
    @section('action-button')
        <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalMitra">Tambah
            Mitra</a>
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
                                    <th>Nama Mitra</th>
                                    <th>Nama Pemilik</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mitras as $mitra)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mitra->nama_mitra }}</td>
                                        <td>{{ $mitra->nama_pemilik }}</td>
                                        <td>{{ $mitra->email }}</td>
                                        <td>{{ $mitra->telp }}</td>
                                        <td>{{ $mitra->alamat }}</td>
                                        <td>
                                            @if (auth()->user()->role == 'admin')
                                                <a href="javascript:void(0)" data-id="{{ $mitra->id }}"
                                                    class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('mitra.destroy', $mitra->id) }}" method="POST"
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
    <div class="modal fade" id="modalMitra" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formMitra" action="{{ route('mitra.store') }}" method="POST">
                    @csrf
                    <div id="method"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Mitra</label>
                            <input type="text" class="form-control" name="nama_mitra" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control" name="nama_pemilik" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NPWP</label>
                            <input type="npwp" class="form-control" name="npwp" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="telp" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const modal = $('#modalMitra');
            const form = $('#formMitra');

            // Initialize DataTable
            $('.datatable').DataTable();

            // Reset form when modal is hidden
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('mitra.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
            });

            // Edit button handler
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');
                const url = `{{ route('mitra.edit', ':id') }}`.replace(':id', id);

                $.get(url, function(data) {
                    modal.find('.modal-title').text('Edit Mitra');
                    form.attr('action', `/mitra/${id}`);
                    $('#method').html('@method('PUT')');

                    // Fill form data
                    form.find('[name="nama_mitra"]').val(data.nama_mitra);
                    form.find('[name="nama_pemilik"]').val(data.nama_pemilik);
                    form.find('[name="npwp"]').val(data.npwp);
                    form.find('[name="email"]').val(data.email);
                    form.find('[name="telp"]').val(data.telp);
                    form.find('[name="alamat"]').val(data.alamat);

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

            // Form submission handler
            // form.on('submit', function(e) {
            //     e.preventDefault();
            //     const submitBtn = $(this).find('button[type="submit"]');
            //     submitBtn.prop('disabled', true);

            //     $.ajax({
            //         url: $(this).attr('action'),
            //         method: $(this).attr('method'),
            //         data: $(this).serialize(),
            //         success: function(response) {
            //             this.submit();
            //         },
            //         error: function(xhr) {
            //             submitBtn.prop('disabled', false);
            //             if (xhr.status === 422) {
            //                 const errors = xhr.responseJSON.errors;
            //                 Object.keys(errors).forEach(field => {
            //                     const input = form.find(`[name="${field}"]`);
            //                     input.addClass('is-invalid');
            //                     input.siblings('.invalid-feedback').text(errors[field][
            //                         0
            //                     ]).show();
            //                 });
            //             }
            //         }
            //     });
            // });
        });
    </script>
@endpush
