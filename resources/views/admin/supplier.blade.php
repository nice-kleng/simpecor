@extends('layouts.app', ['title' => 'Supplier', 'pageDescrition' => 'Supplier Management'])

@section('action-button')
    <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalSupplier">Tambah
        Supplier</a>
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
                                    <th>Nama Supplier</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->nama_supplier }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->telp }}</td>
                                        <td>{{ $supplier->alamat }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $supplier->id }}"
                                                class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST"
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
    <div class="modal fade" id="modalSupplier" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formSupplier" action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div id="method"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" name="nama_supplier" required>
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
            const modal = $('#modalSupplier');
            const form = $('#formSupplier');

            // Initialize DataTable
            $('.datatable').DataTable();

            // Reset form when modal is hidden
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('supplier.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
            });

            // Edit button handler
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');

                $.get(`/supplier/${id}/edit`, function(data) {
                    modal.find('.modal-title').text('Edit Supplier');
                    form.attr('action', `/supplier/${id}`);
                    $('#method').html('@method('PUT')');

                    // Fill form data
                    form.find('[name="nama_supplier"]').val(data.nama_supplier);
                    form.find('[name="email"]').val(data.email);
                    form.find('[name="telp"]').val(data.telp);
                    form.find('[name="alamat"]').val(data.alamat);

                    modal.modal('show');
                });
            });

            // Delete confirmation
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $(this).closest('form').submit();
                }
            });

            // Form submission handler
            form.on('submit', function(e) {
                e.preventDefault();
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        modal.modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(field => {
                                const input = form.find(`[name="${field}"]`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(errors[field][
                                    0
                                ]).show();
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
