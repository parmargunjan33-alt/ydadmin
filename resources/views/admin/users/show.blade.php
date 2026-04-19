@extends('admin.layout')

@section('title', 'View User')
@section('page_title', 'User Details')
@section('page_subtitle', 'View user profile and information')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person"></i> User Information
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-bold">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-bold">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Mobile</label>
                            <p class="fw-bold">{{ $user->mobile ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Mobile Verification</label>
                            <p>
                                @if ($user->mobile_verified)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-circle"></i> Unverified
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">University</label>
                            <p class="fw-bold">{{ $user->university?->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Course</label>
                            <p class="fw-bold">{{ $user->course?->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Semester</label>
                            <p class="fw-bold">{{ $user->semester?->label ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                @if ($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Joined Date</label>
                            <p class="fw-bold">{{ $user->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit User
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
