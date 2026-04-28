@extends('admin.layout')

@section('title', 'Upload PDFs')
@section('page_title', 'Upload PDFs')
@section('page_subtitle', 'Upload new PDF files')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-cloud-upload"></i> Upload PDF Files
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pdfs.store') }}" method="POST" enctype="multipart/form-data" id="pdfForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="course_id" class="form-label">Course *</label>
                            <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->university->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="semester_id" class="form-label">Semester *</label>
                            <select name="semester_id" id="semester_id" class="form-control @error('semester_id') is-invalid @enderror" required>
                                <option value="">-- Select Semester --</option>
                            </select>
                            @error('semester_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="subject_id" class="form-label">Subject *</label>
                            <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                                <option value="">-- Select Subject --</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="language" class="form-label">Language *</label>
                            <select name="language" id="language" class="form-control @error('language') is-invalid @enderror" required>
                                <option value="">-- Select Language --</option>
                                <option value="english" {{ old('language') == 'english' ? 'selected' : '' }}>English</option>
                                <option value="gujarati" {{ old('language') == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="title" class="form-label">PDF Title *</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="e.g., Chapter 1 Summary" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">-- Select Type --</option>
                                <option value="summary" {{ old('type') == 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="past_papers" {{ old('type') == 'past_papers' ? 'selected' : '' }}>Past Papers</option>
                                <option value="imp_questions" {{ old('type') == 'imp_questions' ? 'selected' : '' }}>Important Questions</option>
                                <option value="notes" {{ old('type') == 'notes' ? 'selected' : '' }}>Notes</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="pdfs" class="form-label">Upload PDF Files * <small>(Max 100MB per file)</small></label>
                            <input type="file" name="pdfs[]" id="pdfs" class="form-control @error('pdfs') is-invalid @enderror"
                                accept=".pdf" multiple required>
                            <small class="form-text text-muted">You can select multiple PDF files at once.</small>
                            @error('pdfs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="fileList" class="mt-2"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="is_free" value="1" {{ old('is_free') ? 'checked' : '' }}>
                                <span>This PDF is Free</span>
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                value="{{ old('display_order') }}" placeholder="e.g., 1, 2, 3" min="1">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload PDFs
                            </button>
                            <a href="{{ route('admin.pdfs.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File list preview
        document.getElementById('pdfs').addEventListener('change', function() {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            if (this.files.length > 0) {
                const ul = document.createElement('ul');
                ul.className = 'list-group mt-2';
                for (let file of this.files) {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = '📄 ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    ul.appendChild(li);
                }
                fileList.appendChild(ul);
            }
        });

        // Dependent dropdowns logic
        const courseData = @json($courses);

        function resetDropdown(dropdown, placeholder) {
            dropdown.innerHTML = `<option value="">${placeholder}</option>`;
        }

        document.getElementById('course_id').addEventListener('change', function() {
            const courseId = this.value;
            const semesterSelect = document.getElementById('semester_id');
            const subjectSelect = document.getElementById('subject_id');
            resetDropdown(semesterSelect, '-- Select Semester --');
            resetDropdown(subjectSelect, '-- Select Subject --');
            if (courseId) {
                const course = courseData.find(c => c.id == courseId);
                if (course && course.semesters) {
                    course.semesters.forEach(function(sem) {
                        semesterSelect.innerHTML += `<option value="${sem.id}">${sem.label}</option>`;
                    });
                }
            }
        });

        document.getElementById('semester_id').addEventListener('change', function() {
            const courseId = document.getElementById('course_id').value;
            const semesterId = this.value;
            const subjectSelect = document.getElementById('subject_id');
            resetDropdown(subjectSelect, '-- Select Subject --');
            if (courseId && semesterId) {
                const course = courseData.find(c => c.id == courseId);
                if (course && course.semesters) {
                    const semester = course.semesters.find(s => s.id == semesterId);
                    if (semester && semester.subjects) {
                        semester.subjects.forEach(function(sub) {
                            subjectSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                        });
                    }
                }
            }
        });
    </script>
@endsection
