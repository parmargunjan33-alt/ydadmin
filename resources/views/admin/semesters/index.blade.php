@extends('admin.layout')

@section('title', 'Semesters')
@section('page_title', 'Semesters')
@section('page_subtitle', 'Manage all semesters')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.semesters.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Semester
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-table"></i> Semesters List
        </div>
        <div class="card-body">
            @if ($semesters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course</th>
                                <th>Name</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semesters as $semester)
                                <tr>
                                    <td>#{{ $semester->id }}</td>
                                    <td>{{ $semester->course->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $semester->label }}</strong></td>
                                    <td>{{ $semester->end_date ? $semester->end_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if ($semester->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.semesters.edit', $semester) }}" class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.semesters.destroy', $semester) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Delete this semester?');">
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
                    {{ $semesters->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No semesters found. <a href="{{ route('admin.semesters.create') }}">Create one</a>
                </div>
            @endif
        </div>
    </div>
@endsection
