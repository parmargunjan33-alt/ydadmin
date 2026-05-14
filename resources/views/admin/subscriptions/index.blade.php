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
                        <form method="GET" class="d-flex gap-2 align-items-center">
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id', $userId) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>

                            <select name="course_id" class="form-select form-select-sm">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" @selected(old('course_id', $courseId) == $course->id)>{{ $course->name }}</option>
                                @endforeach
                            </select>

                            <select name="semester_id" class="form-select form-select-sm">
                                <option value="">All Semesters</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" @selected(old('semester_id', $semesterId) == $semester->id)>{{ $semester->label }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        </form>
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
                                            <td>{{ $sub->paid_at?->format('d M Y') ?? '-' }}</td>
                                            <td>{{ $sub->expires_at?->format('d M Y') ?? '-' }}</td>
                                            <td>
                                                @if ($sub->expires_at >= now())
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
