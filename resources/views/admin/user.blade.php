@extends('layouts.app', ['title' => 'Data User'])

@section('action-button')
    <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser">Tambah
        user</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ Str::upper($user->role) }}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-warning btn-sm btn-edit"
                                                title="Edit User" data-id="{{ $user->id }}"><i
                                                    class="fas fa-edit"></i></a>
                                            <form action="javascript:void(0)" method="POST" class="d-inline delete-form"
                                                data-id="{{ $user->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-danger btn-sm btn-delete"><i
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
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUser" action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div id="method"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama User</label>
                            <input type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" class="form-control" name="password" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="direktur">Direktur</option>
                            </select>
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
            const modal = $('#modalUser');
            const form = $('#formUser');

            // Initialize DataTable
            $('.datatable').DataTable();

            // Reset form when modal is hidden
            modal.on('hidden.bs.modal', function() {
                form.trigger('reset');
                form.attr('action', "{{ route('user.store') }}");
                $('#method').html('');
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');
            });

            // Edit button handler
            $('.btn-edit').on('click', function() {
                const id = $(this).data('id');

                $.get(`/user/${id}/edit`, function(data) {
                    modal.find('.modal-title').text('Edit User');
                    form.attr('action', `/user/${id}`);
                    $('#method').html('@method('PUT')');

                    // Fill form data
                    form.find('[name="name"]').val(data.name);
                    form.find('[name="email"]').val(data.email);
                    form.find('[name="role"]').val(data.role);

                    modal.modal('show');
                });
            });

            // Delete confirmation
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    const id = $(this).closest('.delete-form').data('id');
                    $.ajax({
                        url: `/user/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    });
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
