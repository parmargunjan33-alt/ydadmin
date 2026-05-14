@extends('admin.layout')

@section('title', 'Subjects')
@section('page_title', 'Subjects')
@section('page_subtitle', 'Manage all subjects')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Subject
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="bi bi-funnel"></i> Filters
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-4">
                    <label for="course_id" class="form-label">Course</label>
                    <select class="form-control" id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->university->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select class="form-control" id="semester_id" name="semester_id">
                        <option value="">All Semesters</option>
                        <!-- Semesters will be loaded via AJAX -->
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="apply-filters" class="btn btn-secondary me-2">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <button type="button" id="clear-filters" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-table"></i> Subjects List
        </div>
        <div class="card-body">
            <div id="subjects-container">
                @include('admin.subjects.partials.subjects-table', ['subjects' => $subjects])
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const semesterSelect = document.getElementById('semester_id');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const subjectsContainer = document.getElementById('subjects-container');

    // Load semesters when course is selected
    courseSelect.addEventListener('change', function() {
        const courseId = this.value;
        semesterSelect.innerHTML = '<option value="">All Semesters</option>';

        if (courseId) {
            axios.get(`/api/semesters/${courseId}`)
                .then(response => {
                    const semesters = response.data.semesters || response.data.data || [];
                    semesters.forEach(semester => {
                        const option = document.createElement('option');
                        option.value = semester.id;
                        option.textContent = semester.label || semester.name || semester.title || 'Semester';
                        if (semester.id == '{{ request("semester_id") }}') {
                            option.selected = true;
                        }
                        semesterSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading semesters:', error);
                });
        }
    });

    // Apply filters
    applyFiltersBtn.addEventListener('click', function() {
        loadSubjects();
    });

    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        courseSelect.value = '';
        semesterSelect.innerHTML = '<option value="">All Semesters</option>';
        loadSubjects();
    });

    // Force Laravel AJAX detection for axios
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Load subjects via AJAX
    function loadSubjects(page = 1) {
        const params = {
            course_id: courseSelect.value,
            semester_id: semesterSelect.value,
            page: page
        };

        axios.get('/admin/subjects', { params })
            .then(response => {
                const html = response.data?.html ?? response.data;
                if (typeof html === 'string') {
                    subjectsContainer.innerHTML = html;
                } else {
                    console.error('Unexpected subjects response:', response.data);
                    subjectsContainer.innerHTML = '<div class="alert alert-danger">Unable to load subjects. Check console for details.</div>';
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectsContainer.innerHTML = '<div class="alert alert-danger">Failed to load subjects. Check console for details.</div>';
            });
    }

    // Handle pagination clicks
    subjectsContainer.addEventListener('click', function(e) {
        const link = e.target.closest('.page-link');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page');
            loadSubjects(page);
        }
    });

    // Load initial semesters if course is pre-selected
    if (courseSelect.value) {
        courseSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
