@extends('admin.layout')

@section('title', 'Subscriptions')
@section('page_title', 'Subscriptions Management')
@section('page_subtitle', 'View and manage user subscriptions')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-receipt"></i> Subscriptions List
                    </div>
                    <div>
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search subscriptions..." style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
                    @if ($subscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Course</th>
                                        <th>Semester</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscriptions as $sub)
                                        <tr>
                                            <td>
                                                <strong>{{ $sub->user?->name ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $sub->user?->email ?? '-' }}</small>
                                            </td>
                                            <td>{{ $sub->semester?->course?->name ?? '-' }}</td>
                                            <td>{{ $sub->semester?->label ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($sub->subscription_type ?? 'standard') }}</span>
                                            </td>
                                            <td>{{ $sub->start_date?->format('d M Y') ?? '-' }}</td>
                                            <td>{{ $sub->end_date?->format('d M Y') ?? '-' }}</td>
                                            <td>
                                                @if ($sub->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.subscriptions.show', $sub) }}" class="btn btn-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" onclick="deleteSubscription({{ $sub->id }})" title="Delete">
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
                            {{ $subscriptions->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No subscriptions found in the system.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteSubscription(subscriptionId) {
            Swal.fire({
                title: 'Delete Subscription?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/subscriptions/${subscriptionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Deleted!', 'Subscription deleted successfully.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete subscription.', 'error');
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
