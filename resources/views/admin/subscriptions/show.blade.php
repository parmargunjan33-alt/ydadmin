@extends('admin.layout')

@section('title', 'Subscription Details')
@section('page_title', 'Subscription Details')
@section('page_subtitle', 'View subscription information')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-receipt"></i> Subscription Information
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">User Name</label>
                            <p class="fw-bold">{{ $subscription->user?->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">User Email</label>
                            <p class="fw-bold">{{ $subscription->user?->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Course</label>
                            <p class="fw-bold">{{ $subscription->semester?->course?->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Semester</label>
                            <p class="fw-bold">{{ $subscription->semester?->label ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Subscription Type</label>
                            <p>
                                <span class="badge bg-info">{{ ucfirst($subscription->subscription_type ?? 'standard') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                @if ($subscription->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Start Date</label>
                            <p class="fw-bold">{{ $subscription->start_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">End Date</label>
                            <p class="fw-bold">{{ $subscription->end_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Created Date</label>
                            <p class="fw-bold">{{ $subscription->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                        <button type="button" class="btn btn-danger float-end" onclick="deleteSubscription({{ $subscription->id }})">
                            <i class="bi bi-trash"></i> Delete Subscription
                        </button>
                    </div>
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
                                window.location.href = '/admin/subscriptions';
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete subscription.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
