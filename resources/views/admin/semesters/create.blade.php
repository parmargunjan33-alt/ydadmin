@extends('admin.layout')

@section('title', 'Create Semester')
@section('page_title', 'Create Semester')
@section('page_subtitle', 'Add a new semester to the system')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-plus-circle"></i> Add New Semester
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.semesters.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="course_id" class="form-label">Course *</label>
                            <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} - {{ $course->university->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="number" class="form-label">Semester Number *</label>
                            <input type="number" name="number" id="number" class="form-control @error('number') is-invalid @enderror"
                                value="{{ old('number') }}" placeholder="e.g., 1, 2, 3, 4" min="1" required>
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="label" class="form-label">Semester Label *</label>
                            <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror"
                                value="{{ old('label') }}" placeholder="e.g., First Year, Second Year" required>
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Semester
                            </button>
                            <a href="{{ route('admin.semesters.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
