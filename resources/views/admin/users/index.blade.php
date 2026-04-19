@extends('admin.layout')

@section('title', 'Users')
@section('page_title', 'Users Management')
@section('page_subtitle', 'Manage system users and their accounts')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-people"></i> Users List
                    </div>
                    <div>
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search users..." style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
                    @if ($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>University</th>
                                        <th>Course</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->university?->name ?? '-' }}</td>
                                            <td>{{ $user->course?->name ?? '-' }}</td>
                                            <td>
                                                @if ($user->mobile_verified)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-exclamation-circle"></i> Unverified
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" onclick="deleteUser({{ $user->id }})" title="Delete">
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
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No users found in the system.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteUser(userId) {
            Swal.fire({
                title: 'Delete User?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Deleted!', 'User deleted successfully.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete user.', 'error');
                        }
                    });
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection
