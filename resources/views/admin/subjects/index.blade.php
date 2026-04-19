@extends('admin.layout')

@section('title', 'Subjects')
@section('page_title', 'Subjects')
@section('page_subtitle', 'Manage all subjects')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Subject
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-table"></i> Subjects List
        </div>
        <div class="card-body">
            @if ($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Semester</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                                <tr>
                                    <td>#{{ $subject->id }}</td>
                                    <td><strong>{{ $subject->name }}</strong></td>
                                    <td>{{ $subject->semester->label ?? 'N/A' }}</td>
                                    <td>{{ $subject->semester->course->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($subject->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Delete this subject?');">
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
                    {{ $subjects->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No subjects found. <a href="{{ route('admin.subjects.create') }}">Create one</a>
                </div>
            @endif
        </div>
    </div>
@endsection
