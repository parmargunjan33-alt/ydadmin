<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PdfFile;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index()
    {
        $pdfs = PdfFile::with('subject.semester.course.university')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.pdfs.index', compact('pdfs'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        $semesters = Semester::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.pdfs.create', compact('courses', 'semesters', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:summary,past_papers,imp_questions,notes',
            'language' => 'required|in:gujarati,english',
            'pdfs' => 'required|array|min:1',
            'pdfs.*' => 'required|file|mimes:pdf|max:102400', // 100MB per file
            'is_free' => 'boolean',
            'display_order' => 'required|integer',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $displayOrder = PdfFile::where('subject_id', $validated['subject_id'])->max('display_order') ?? 0;

        foreach ($request->file('pdfs') as $file) {
            $displayOrder++;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pdfs/' . $validated['subject_id'], $fileName, 'public');

            PdfFile::create([
                'subject_id' => $validated['subject_id'],
                'semester_id' => $validated['semester_id'],
                'title' => $validated['title'],
                'type' => $validated['type'],
                'language' => $validated['language'],
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'is_free' => $validated['is_free'] ?? false,
                'display_order' => $displayOrder,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.pdfs.index')->with('success', 'PDFs uploaded successfully!');
    }

    public function edit(PdfFile $pdf)
    {
        $courses = Course::where('is_active', true)->get();
        $semesters = Semester::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.pdfs.edit', compact('pdf', 'courses', 'semesters', 'subjects'));
    }

    public function update(Request $request, PdfFile $pdf)
    {
        $request->merge([
            'is_free' => $request->boolean('is_free'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:summary,past_papers,imp_questions,notes',
            'language' => 'required|in:gujarati,english',
            'is_free' => 'boolean',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $pdf->update($validated);
        return redirect()->route('admin.pdfs.index')->with('success', 'PDF updated successfully!');
    }

    public function destroy($id)
    {
        $pdf = PdfFile::findOrFail($id);
        if (Storage::disk('public')->exists($pdf->file_path)) {
            Storage::disk('public')->delete($pdf->file_path);
        }
        $pdf->delete();
        return redirect()->back()->with('success', 'PDF deleted successfully!');
    }
}
