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
                            @if ($config->key === 'subscription_price')
                                <label for="value" class="form-label">Subscription Price (Rs.) *</label>
                                <input type="number" name="value" id="value"
                                    class="form-control @error('value') is-invalid @enderror"
                                    value="{{ old('value', number_format(((int) $config->value) / 100, 2, '.', '')) }}"
                                    min="1" step="0.01" required>
                                <small class="text-muted">Enter the amount in rupees. Example: 75 means Rs. 75.</small>
                            @else
                                <label for="value" class="form-label">Value *</label>
                                <textarea name="value" id="value" class="form-control @error('value') is-invalid @enderror" 
                                    rows="6" required>{{ old('value', $config->value ?? '') }}</textarea>
                            @endif
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($config->description)
                            <div class="form-group mb-3">
                                <label class="form-label">Description</label>
                                <p class="form-control-plaintext">{{ $config->description }}</p>
                            </div>
                        @endif

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
