@extends('admin.layout')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('page_subtitle', 'Modify user information')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Edit User
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="User full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" placeholder="User email" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile', $user->mobile) }}" placeholder="User mobile number" disabled>
                            <small class="text-muted">Mobile cannot be changed</small>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="university" class="form-label">University</label>
                            <input type="text" name="university" id="university" class="form-control" 
                                value="{{ $user->university?->name ?? '-' }}" disabled>
                            <small class="text-muted">University cannot be changed from here</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" name="course" id="course" class="form-control" 
                                value="{{ $user->course?->name ?? '-' }}" disabled>
                            <small class="text-muted">Course cannot be changed from here</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="text" name="semester" id="semester" class="form-control" 
                                value="{{ $user->semester?->label ?? '-' }}" disabled>
                            <small class="text-muted">Semester cannot be changed from here</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="mobile_verified" value="1" {{ old('mobile_verified', $user->mobile_verified) ? 'checked' : '' }}>
                                <span>Mobile Verified</span>
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
