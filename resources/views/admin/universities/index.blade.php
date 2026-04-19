@extends('admin.layout')

@section('title', 'Universities')
@section('page_title', 'Universities')
@section('page_subtitle', 'Manage all universities')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New University
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-table"></i> Universities List
        </div>
        <div class="card-body">
            @if ($universities->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Short Name</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $university)
                                <tr>
                                    <td>#{{ $university->id }}</td>
                                    <td><strong>{{ $university->name }}</strong></td>
                                    <td>{{ $university->short_name }}</td>
                                    <td>{{ $university->city }}</td>
                                    <td>
                                        @if ($university->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $university->display_order }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('admin.universities.edit', $university) }}" class="btn-edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.universities.destroy', $university) }}" method="POST" style="display: inline;">
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

                {{ $universities->links() }}
            @else
                <div style="text-align: center; padding: 40px; color: #999;">
                    <i class="bi bi-inbox" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>No universities found</p>
                    <a href="{{ route('admin.universities.create') }}" class="btn btn-primary" style="margin-top: 15px;"><i class="bi bi-plus-circle"></i> Create First University</a>
                </div>
            @endif
        </div>
    </div>
@endsection
