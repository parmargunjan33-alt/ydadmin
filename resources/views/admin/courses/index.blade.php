@extends('admin.layout')

@section('title', 'Courses')
@section('page_title', 'Courses')
@section('page_subtitle', 'Manage all courses')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Course
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-table"></i> Courses List
        </div>
        <div class="card-body">
            @if ($courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>University</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Language</th>
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
                                        <span class="badge" style="background-color: {{ $course->language === 'english' ? '#3498db' : '#e74c3c' }};">
                                            {{ ucfirst($course->language ?? 'English') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($course->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Delete this course?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No courses found. <a href="{{ route('admin.courses.create') }}">Create one</a>
                </div>
            @endif
        </div>
    </div>
@endsection
