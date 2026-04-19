@extends('admin.layout')

@section('title', 'App Configuration')
@section('page_title', 'App Configuration')
@section('page_subtitle', 'Manage application settings')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-gear"></i> Configuration List
                    </div>
                </div>
                <div class="card-body">
                    @if ($configs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Key</th>
                                        <th>Value</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configs as $config)
                                        <tr>
                                            <td>
                                                <strong>{{ $config->key ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ Str::limit($config->value ?? '-', 50) }}</code>
                                            </td>
                                            <td>{{ $config->updated_at?->format('d M Y, H:i') ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.config.edit', $config) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $configs->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> No configurations found in the system.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
