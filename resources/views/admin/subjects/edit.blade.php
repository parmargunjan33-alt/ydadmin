@extends('admin.layout')

@section('title', 'Edit Subject')
@section('page_title', 'Edit Subject')
@section('page_subtitle', 'Modify subject details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Edit Subject
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="semester_id" class="form-label">Semester *</label>
                            <select name="semester_id" id="semester_id" class="form-control @error('semester_id') is-invalid @enderror" required>
                                <option value="">-- Select Semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ old('semester_id', $subject->semester_id) == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->label }} - {{ $semester->course->name ?? 'N/A' }} ({{ $semester->course->university->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('semester_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Subject Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $subject->name) }}" placeholder="e.g., Mathematics, Physics, Chemistry" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', $subject->display_order) }}" placeholder="e.g., 1, 2, 3" min="1">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Subject
                            </button>
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
