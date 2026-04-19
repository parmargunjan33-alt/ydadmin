@extends('admin.layout')

@section('title', 'PDF Management')
@section('page_title', 'PDF Management')
@section('page_subtitle', 'Manage all PDF files')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.pdfs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Upload New PDF
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-file-pdf"></i> PDFs List
        </div>
        <div class="card-body">
            @if ($pdfs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Subject</th>
                                <th>Semester</th>
                                <th>Language</th>
                                <th>Type</th>
                                <th>Free</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pdfs as $pdf)
                                <tr>
                                    <td>#{{ $pdf->id }}</td>
                                    <td><strong>{{ $pdf->title }}</strong></td>
                                    <td>{{ $pdf->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $pdf->semester->label ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($pdf->language) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $pdf->type)) }}</span>
                                    </td>
                                    <td>
                                        @if ($pdf->is_free)
                                            <i class="bi bi-check-circle text-success"></i> Yes
                                        @else
                                            <i class="bi bi-x-circle text-danger"></i> No
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pdf->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pdfs.edit', $pdf) }}" class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.pdfs.destroy', $pdf) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Delete this PDF?');">
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
                    {{ $pdfs->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No PDFs found. <a href="{{ route('admin.pdfs.create') }}">Upload one</a>
                </div>
            @endif
        </div>
    </div>
@endsection
