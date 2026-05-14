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

    <div class="card mb-3">
        <div class="card-header">
            <i class="bi bi-funnel"></i> PDF Filters
        </div>
        <div class="card-body">
            <form id="pdf-filter-form" class="row g-3" method="GET" action="{{ route('admin.pdfs.index') }}">
                <div class="col-md-3">
                    <label for="course_id" class="form-label">Course</label>
                    <select class="form-control" id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->university->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select class="form-control" id="semester_id" name="semester_id">
                        <option value="">All Semesters</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-control" id="subject_id" name="subject_id">
                        <option value="">All Subjects</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" id="apply-filters" class="btn btn-secondary w-100">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Clear
                    </button>
                </div>
            </form>
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
                                <th>Course</th>
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
                                    <td>{{ $pdf->semester->course->name ?? $pdf->subject->semester->course->name ?? 'N/A' }}</td>
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
                    {{ $pdfs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No PDFs found. <a href="{{ route('admin.pdfs.create') }}">Upload one</a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courseSelect = document.getElementById('course_id');
        const semesterSelect = document.getElementById('semester_id');
        const subjectSelect = document.getElementById('subject_id');
        const clearFiltersBtn = document.getElementById('clear-filters');
        const selectedSemesterId = '{{ request("semester_id") }}';
        const selectedSubjectId = '{{ request("subject_id") }}';
        const apiBaseUrl = '{{ url('api') }}';

        function setOptions(selectElement, items, selectedId, labelKey = 'label') {
            selectElement.innerHTML = '<option value="">All ' + (selectElement.id === 'semester_id' ? 'Semesters' : 'Subjects') + '</option>';
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item[labelKey] || item.name || item.title || 'Item';
                if (String(item.id) === String(selectedId)) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
        }

        function loadSemesters(courseId, callback = null) {
            semesterSelect.innerHTML = '<option value="">All Semesters</option>';
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';

            if (!courseId) {
                if (callback) callback();
                return;
            }

            axios.get(`${apiBaseUrl}/semesters/${courseId}`)
                .then(response => {
                    const semesters = response.data.semesters || [];
                    setOptions(semesterSelect, semesters, selectedSemesterId, 'label');
                    if (callback) callback();
                })
                .catch(error => {
                    console.error('Error loading semesters:', error);
                    if (callback) callback();
                });
        }

        function loadSubjects(semesterId, callback = null) {
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';

            if (!semesterId) {
                if (callback) callback();
                return;
            }

            axios.get(`${apiBaseUrl}/subjects/${semesterId}`)
                .then(response => {
                    const subjects = response.data.subjects || [];
                    setOptions(subjectSelect, subjects, selectedSubjectId, 'name');
                    if (callback) callback();
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    if (callback) callback();
                });
        }

        courseSelect.addEventListener('change', function () {
            loadSemesters(this.value);
        });

        semesterSelect.addEventListener('change', function () {
            loadSubjects(this.value);
        });

        if (courseSelect.value) {
            loadSemesters(courseSelect.value, function () {
                if (semesterSelect.value) {
                    loadSubjects(semesterSelect.value);
                }
            });
        }

        clearFiltersBtn.addEventListener('click', function () {
            courseSelect.value = '';
            semesterSelect.innerHTML = '<option value="">All Semesters</option>';
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';
            document.getElementById('pdf-filter-form').submit();
        });

        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    });
</script>
@endsection
