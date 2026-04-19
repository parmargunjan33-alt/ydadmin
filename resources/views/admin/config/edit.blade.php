@extends('admin.layout')

@section('title', 'Edit Configuration')
@section('page_title', 'Edit Configuration')
@section('page_subtitle', 'Modify application configuration')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Edit Configuration
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.config.update', $config) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="key" class="form-label">Configuration Key</label>
                            <input type="text" name="key" id="key" class="form-control" 
                                value="{{ $config->key ?? '' }}" disabled>
                            <small class="text-muted">Key cannot be changed</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="value" class="form-label">Value *</label>
                            <textarea name="value" id="value" class="form-control @error('value') is-invalid @enderror" 
                                rows="6" required>{{ old('value', $config->value ?? '') }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Configuration
                            </button>
                            <a href="{{ route('admin.config.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
