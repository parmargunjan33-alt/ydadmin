<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('semester.course.university')->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
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
