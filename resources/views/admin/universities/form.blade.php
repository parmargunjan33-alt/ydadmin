@extends('admin.layout')

@section('title', 'Universities')
@section('page_title', isset($university) ? 'Edit University' : 'Universities')
@section('page_subtitle', isset($university) ? 'Modify university details' : 'Manage all universities')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-building"></i> {{ isset($university) ? 'Edit University' : 'Add New University' }}
                </div>
                <div class="card-body">
                    <form action="{{ isset($university) ? route('admin.universities.update', $university) : route('admin.universities.store') }}" method="POST">
                        @csrf
                        @if (isset($university))
                            @method('PUT')
                        @endif

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">University Name *</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $university->name ?? '') }}" placeholder="e.g., Delhi University" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_name">Short Name *</label>
                                <input type="text" name="short_name" id="short_name" class="form-control @error('short_name') is-invalid @enderror"
                                    value="{{ old('short_name', $university->short_name ?? '') }}" placeholder="e.g., DU" required>
                                @error('short_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city', $university->city ?? '') }}" placeholder="e.g., New Delhi" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="display_order">Display Order</label>
                                <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                    value="{{ old('display_order', $university->display_order ?? 0) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_active">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $university->is_active ?? true) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check-circle"></i> {{ isset($university) ? 'Update' : 'Create' }}
                            </button>
                            <a href="{{ route('admin.universities.index') }}" class="btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
