<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\InteractsWithApiFilters;
use App\Http\Controllers\Controller;
use App\Models\{PdfFile, Semester, Subject, Subscription};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfManagementController extends Controller
{
    use InteractsWithApiFilters;

    /**
     * Get semesters for a course (for dropdown selection)
     */
    public function getSemesters(Request $request, $courseId)
    {
        $query = Semester::where('course_id', $courseId)
            ->when($request->filled('number'), function (Builder $query) use ($request) {
                $query->where('number', $request->integer('number'));
            });

        $this->applySearch($query, $request->query('search'), ['label']);
        $this->applyBooleanFilter($query, $request, 'is_active', 'is_active', true);

        $semesters = $query
            ->orderBy('number')
            ->get(['id', 'number', 'label']);

        return response()->json(['success' => true, 'semesters' => $semesters]);
    }

    /**
     * Get subjects for a semester (for dropdown selection)
     */
    public function getSubjects(Request $request, $semesterId)
    {
        $query = Subject::where('semester_id', $semesterId);

        $this->applySearch($query, $request->query('search'), ['name']);
        $this->applyBooleanFilter($query, $request, 'is_active', 'is_active', true);

        $subjects = $query
            ->orderBy('display_order')
            ->get(['id', 'name']);

        return response()->json(['success' => true, 'subjects' => $subjects]);
    }

    /**
     * Upload multiple PDFs for a subject
     */
    public function uploadPdfs(Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'language' => 'required|in:gujarati,english',
            'pdfs' => 'required|array|min:1',
            'pdfs.*' => 'required|file|mimes:pdf|max:102400', // 100MB max per file
        ]);

        $uploadedPdfs = [];
        $displayOrder = PdfFile::where('subject_id', $validated['subject_id'])
            ->max('display_order') ?? 0;

        foreach ($request->file('pdfs') as $file) {
            $displayOrder++;

            // Store file
            $filePath = $file->store('pdfs', 'public');
            
            // Create PDF record
            $pdf = PdfFile::create([
                'subject_id' => $validated['subject_id'],
                'semester_id' => $validated['semester_id'],
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'type' => 'pdf',
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'language' => $validated['language'],
                'is_free' => false,
                'display_order' => $displayOrder,
                'is_active' => true,
            ]);

            $uploadedPdfs[] = [
                'id' => $pdf->id,
                'title' => $pdf->title,
                'language' => $pdf->language,
                'file_size' => $pdf->file_size,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedPdfs) . ' PDF(s) uploaded successfully',
            'pdfs' => $uploadedPdfs,
        ], 201);
    }

    /**
     * Get PDFs for a subject with semester and language filter
     */
    public function getPdfs(Request $request, $semesterId, $subjectId)
    {
        $query = PdfFile::where('subject_id', $subjectId)
            ->where('semester_id', $semesterId)
            ->when($request->filled('language'), function (Builder $query) use ($request) {
                $query->where('language', $request->query('language'));
            })
            ->when($request->filled('type'), function (Builder $query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($request->filled('is_free'), function (Builder $query) use ($request) {
                $query->where('is_free', $request->boolean('is_free'));
            });

        $this->applySearch($query, $request->query('search'), ['title']);
        $this->applyBooleanFilter($query, $request, 'is_active', 'is_active', true);

        $pdfs = $query->orderBy('display_order')
            ->get(['id', 'title', 'language', 'is_free', 'display_order']);

        $isSubscribed = Subscription::where('user_id', $request->user()->id)
            ->where('semester_id', $semesterId)
            ->where('status', 'paid')
            ->where(function (Builder $query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->exists();

        return response()->json([
            'success' => true,
            'is_subscribed' => $isSubscribed,
            'pdfs' => $pdfs,
        ]);
    }

    /**
     * Get PDFs using POST request with body parameters
     */
    public function getPdfsPost(Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'subject_id' => 'required|exists:subjects,id',
            'language' => 'required|in:gujarati,english',
        ]);

        $query = PdfFile::where('subject_id', $validated['subject_id'])
            ->where('semester_id', $validated['semester_id'])
            ->where('language', $validated['language'])
            ->where('is_active', true)
            ->orderBy('display_order');

        $pdfs = $query->get([
            'id',
            'title',
            'type',
            'language',
            'is_free',
            'file_size',
            'display_order',
            'created_at'
        ])->map(function ($pdf) {
            return [
                'id' => (int) $pdf->id,
                'title' => $pdf->title,
                'type' => $pdf->type,
                'language' => $pdf->language,
                'is_free' => (bool) $pdf->is_free,
                'file_size' => (int) $pdf->file_size,
                'file_size_mb' => round($pdf->file_size / 1024 / 1024, 2),
                'display_order' => (int) $pdf->display_order,
                'created_at' => $pdf->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => count($pdfs) . ' PDF(s) found',
            'count' => count($pdfs),
            'semester_id' => $validated['semester_id'],
            'subject_id' => $validated['subject_id'],
            'language' => $validated['language'],
            'pdfs' => $pdfs,
        ]);
    }

    /**
     * Update PDF details
     */
    public function updatePdf(Request $request, $pdfId)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'language' => 'sometimes|in:gujarati,english',
            'is_free' => 'sometimes|boolean',
            'display_order' => 'sometimes|integer',
        ]);

        $pdf = PdfFile::findOrFail($pdfId);
        $pdf->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'PDF updated successfully',
            'pdf' => $pdf,
        ]);
    }

    /**
     * Delete PDF
     */
    public function deletePdf($pdfId)
    {
        $pdf = PdfFile::findOrFail($pdfId);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($pdf->file_path)) {
            Storage::disk('public')->delete($pdf->file_path);
        }

        $pdf->delete();

        return response()->json([
            'success' => true,
            'message' => 'PDF deleted successfully',
        ]);
    }

    /**
     * List all PDFs with filters (for admin dashboard)
     */
    public function listPdfs(Request $request)
    {
        $query = PdfFile::with(['subject.semester.course.university', 'semester']);

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->filled('course_id')) {
            $query->whereHas('semester', function (Builder $semesterQuery) use ($request) {
                $semesterQuery->where('course_id', $request->course_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_free')) {
            $query->where('is_free', $request->boolean('is_free'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function (Builder $searchQuery) use ($search) {
                $searchQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('subject', function (Builder $subjectQuery) use ($search) {
                        $subjectQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('semester', function (Builder $semesterQuery) use ($search) {
                        $semesterQuery->where('label', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('semester.course', function (Builder $courseQuery) use ($search) {
                        $courseQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('semester.course.university', function (Builder $universityQuery) use ($search) {
                        $universityQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('short_name', 'like', '%' . $search . '%');
                    });
            });
        }

        $pdfs = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'pdfs' => $pdfs,
        ]);
    }
}
