@extends('admin.layout')

@section('title', 'Admin Users')
@section('page_title', 'Admin Users Management')
@section('page_subtitle', 'Manage system administrator accounts')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-shield-lock"></i> Admin Users List
                    </div>
                    <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Admin User
                    </a>
                </div>
                <div class="card-body">
                    @if ($admins->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>
                                                <strong>{{ $admin->name }}</strong>
                                            </td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                @if ($admin->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.admin-users.edit', $admin) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" onclick="deleteAdmin({{ $admin->id }})" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $admins->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No admin users found in the system.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteAdmin(adminId) {
            Swal.fire({
                title: 'Delete Admin User?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/admin-users/${adminId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Deleted!', 'Admin user deleted successfully.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete admin user.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
