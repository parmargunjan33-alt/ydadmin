@extends('admin.layout')

@section('title', 'Create Course')
@section('page_title', 'Create Course')
@section('page_subtitle', 'Add a new course to the system')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-plus-circle"></i> Add New Course
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="university_id" class="form-label">University *</label>
                            <select name="university_id" id="university_id" class="form-control @error('university_id') is-invalid @enderror" required>
                                <option value="">-- Select University --</option>
                                @foreach ($universities as $university)
                                    <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('university_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Course Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g., B.Tech Computer Science" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Course Type *</label>
                            <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                value="{{ old('type') }}" placeholder="e.g., Bachelor, Master" required>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="language" class="form-label">Language *</label>
                            <select name="language" id="language" class="form-control @error('language') is-invalid @enderror" required>
                                <option value="">-- Select Language --</option>
                                <option value="english" {{ old('language', 'english') == 'english' ? 'selected' : '' }}>
                                    English
                                </option>
                                <option value="gujarati" {{ old('language', 'english') == 'gujarati' ? 'selected' : '' }}>
                                    Gujarati
                                </option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', 0) }}" min="0">
                            @error('display_order')
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
                                <i class="bi bi-check-circle"></i> Create Course
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
