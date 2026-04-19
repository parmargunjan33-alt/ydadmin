<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\Course;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('course.university')->paginate(15);
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create()
    {
        $courses = Course::with('university')->where('is_active', true)->get();
        return view('admin.semesters.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'number' => 'required|integer',
            'label' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'end_date' => 'nullable|date',
        ]);

        Semester::create($validated);
        return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully!');
    }

    public function edit(Semester $semester)
    {
        $courses = Course::with('university')->where('is_active', true)->get();
        return view('admin.semesters.edit', compact('semester', 'courses'));
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'number' => 'required|integer',
            'label' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'end_date' => 'nullable|date',
        ]);

        $semester->update($validated);
        return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully!');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();
        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully!');
    }
}
