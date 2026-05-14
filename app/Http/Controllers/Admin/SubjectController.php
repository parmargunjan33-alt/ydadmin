<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with('semester.course.university');

        // Apply filters
        if ($request->filled('course_id')) {
            $query->whereHas('semester', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        $subjects = $query->paginate(15);

        // If AJAX request, return JSON
        if ($request->ajax()) {
            $html = view('admin.subjects.partials.subjects-table', compact('subjects'))->render();
            return response()->json([
                'html' => $html,
                'pagination' => [
                    'current_page' => $subjects->currentPage(),
                    'last_page' => $subjects->lastPage(),
                    'per_page' => $subjects->perPage(),
                    'total' => $subjects->total(),
                ]
            ]);
        }

        // Get courses for filter dropdown
        $courses = \App\Models\Course::with('university')->where('is_active', true)->get();

        return view('admin.subjects.index', compact('subjects', 'courses'));
    }

    public function create()
    {
        $semesters = Semester::with('course.university')
            ->where('is_active', true)
            ->orderBy('course_id')
            ->orderBy('number')
            ->get();
        return view('admin.subjects.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'display_order' => 'required|integer',
        ]);

        Subject::create($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully!');
    }

    public function edit(Subject $subject)
    {
        $semesters = Semester::with('course.university')
            ->where('is_active', true)
            ->orderBy('course_id')
            ->orderBy('number')
            ->get();
        return view('admin.subjects.edit', compact('subject', 'semesters'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'display_order' => 'required|integer',
        ]);

        $subject->update($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
