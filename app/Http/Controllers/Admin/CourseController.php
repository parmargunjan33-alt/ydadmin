<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\University;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('university')->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $universities = University::where('is_active', true)->get();
        return view('admin.courses.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'language' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        Course::create($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $universities = University::where('is_active', true)->get();
        return view('admin.courses.edit', compact('course', 'universities'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'language' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $course->update($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully!');
    }
}