@extends('admin.layout')

@section('title', 'Edit PDF')
@section('page_title', 'Edit PDF')
@section('page_subtitle', 'Update PDF details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil-square"></i> Edit PDF Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pdfs.update', $pdf) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" value="{{ $pdf->subject->name ?? 'N/A' }}" disabled>
                            <small class="form-text text-muted">Subject cannot be changed. Delete and re-upload if needed.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Semester</label>
                            <input type="text" class="form-control" value="{{ $pdf->semester->label ?? 'N/A' }}" disabled>
                            <small class="form-text text-muted">Semester cannot be changed. Delete and re-upload if needed.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="title" class="form-label">PDF Title *</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $pdf->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="language" class="form-label">Language *</label>
                            <select name="language" id="language" class="form-control @error('language') is-invalid @enderror" required>
                                <option value="english" {{ old('language', $pdf->language) == 'english' ? 'selected' : '' }}>English</option>
                                <option value="gujarati" {{ old('language', $pdf->language) == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="summary" {{ old('type', $pdf->type) == 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="past_papers" {{ old('type', $pdf->type) == 'past_papers' ? 'selected' : '' }}>Past Papers</option>
                                <option value="imp_questions" {{ old('type', $pdf->type) == 'imp_questions' ? 'selected' : '' }}>Important Questions</option>
                                <option value="notes" {{ old('type', $pdf->type) == 'notes' ? 'selected' : '' }}>Notes</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_free" value="1" {{ old('is_free', $pdf->is_free) ? 'checked' : '' }}>
                                <span>This PDF is Free</span>
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order', $pdf->display_order) }}" min="1">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pdf->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update PDF
                            </button>
                            <a href="{{ route('admin.pdfs.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>

                    <hr>

                    <div class="alert alert-info">
                        <strong>File Information:</strong><br>
                        <small>
                            File: {{ $pdf->file_path }}<br>
                            Size: {{ number_format($pdf->file_size / 1024 / 1024, 2) }} MB<br>
                            Uploaded: {{ $pdf->created_at->format('d-m-Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
