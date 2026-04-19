@extends('admin.layout')

@section('title', 'Courses')
@section('page_title', isset($course) ? 'Edit Course' : 'Courses')
@section('page_subtitle', isset($course) ? 'Modify course details' : 'Manage courses')

@section('content')
    @if (!isset($course))
        <!-- Courses List View -->
        <div class="page-actions">
            <a href="{{ route('admin.courses.create') }}" class="btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Course
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-table"></i> Courses List
            </div>
            <div class="card-body">
                @if ($courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>University</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>#{{ $course->id }}</td>
                                        <td>{{ $course->university->name ?? 'N/A' }}</td>
                                        <td><strong>{{ $course->name }}</strong></td>
                                        <td>{{ $course->type }}</td>
                                        <td>
                                            @if ($course->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn-edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $courses->links() }}
                @else
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <p>No courses found</p>
                        <a href="{{ route('admin.courses.create') }}" class="btn-primary" style="margin-top: 15px;">Create First Course</a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Course Form View (Create/Edit) -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-pencil"></i> {{ isset($course) && $course->id ? 'Edit Course' : 'Add New Course' }}
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($course) && $course->id ? route('admin.courses.update', $course) : route('admin.courses.store') }}" method="POST">
                            @csrf
                            @if (isset($course) && $course->id)
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="university_id">University *</label>
                                <select name="university_id" id="university_id" class="form-control @error('university_id') is-invalid @enderror" required>
                                    <option value="">-- Select University --</option>
                                    @foreach ($universities as $university)
                                        <option value="{{ $university->id }}"
                                            {{ old('university_id', $course->university_id ?? '') == $university->id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('university_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Course Name *</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $course->name ?? '') }}" placeholder="e.g., B.Tech Computer Science" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">Type *</label>
                                <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                    value="{{ old('type', $course->type ?? '') }}" placeholder="e.g., UG, PG" required>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="language">Language *</label>
                                <select name="language" id="language" class="form-control @error('language') is-invalid @enderror" required>
                                    <option value="">-- Select Language --</option>
                                    <option value="english" {{ old('language', $course->language ?? 'english') == 'english' ? 'selected' : '' }}>
                                        English
                                    </option>
                                    <option value="gujarati" {{ old('language', $course->language ?? 'english') == 'gujarati' ? 'selected' : '' }}>
                                        Gujarati
                                    </option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_order">Display Order</label>
                                <input type="number" name="display_order" id="display_order" class="form-control"
                                    value="{{ old('display_order', $course->display_order ?? 0) }}" min="0">
                            </div>

                            <div class="form-group">
                                <label for="is_active">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        {{ old('is_active', $course->is_active ?? true) ? 'checked' : '' }}>
                                    <span>Active</span>
                                </label>
                            </div>

                            <div style="margin-top: 30px;">
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-check-circle"></i> {{ isset($course) && $course->id ? 'Update' : 'Create' }}
                                </button>
                                <a href="{{ route('admin.courses.index') }}" class="btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
