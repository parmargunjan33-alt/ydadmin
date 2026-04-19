<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{University, Course, Semester, Subject, PdfFile, AppConfig, User, Subscription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string',
            'type'          => 'required|in:summary,past_papers,imp_questions',
            'is_free'       => 'boolean',
            'display_order' => 'integer',
            'pdf'           => 'required|file|mimes:pdf|max:20480',
        ]);

        $path = $request->file('pdf')->store('pdfs', 'public');

        $pdf = PdfFile::create([
            'subject_id'    => $request->subject_id,
            'title'         => $request->title,
            'type'          => $request->type,
            'file_path'     => $path,
            'file_size'     => $request->file('pdf')->getSize(),
            'is_free'       => $request->boolean('is_free', false),
            'display_order' => $request->integer('display_order', 0),
            'is_active'     => true,
        ]);

        return response()->json(['success' => true, 'pdf' => $pdf]);
    }

    public function deletePdf($id)
    {
        $pdf = PdfFile::findOrFail($id);
        Storage::disk('public')->delete($pdf->file_path);
        $pdf->delete();
        return response()->json(['success' => true]);
    }

    public function toggleSemester($id)
    {
        $sem = Semester::findOrFail($id);
        $sem->update(['is_active' => !$sem->is_active]);
        return response()->json(['success' => true, 'is_active' => $sem->is_active]);
    }

    public function updatePrice(Request $request)
    {
        $request->validate(['price' => 'required|integer|min:100']);
        AppConfig::where('key', 'subscription_price')->update(['value' => $request->price]);
        return response()->json(['success' => true]);
    }

    public function setSemesterEndDate(Request $request, $id)
    {
        $request->validate(['end_date' => 'required|date']);
        Semester::findOrFail($id)->update(['end_date' => $request->end_date]);
        return response()->json(['success' => true]);
    }

    public function lookupUser(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);
        $user = User::with(['university', 'course', 'semester'])
            ->where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $subscriptions = Subscription::where('user_id', $user->id)->with('semester')->get();

        return response()->json(['success' => true, 'user' => $user, 'subscriptions' => $subscriptions]);
    }

    public function stats()
    {
        return response()->json([
            'success' => true,
            'stats'   => [
                'total_users'         => User::count(),
                'verified_users'      => User::where('mobile_verified', true)->count(),
                'total_subscriptions' => Subscription::where('status', 'paid')->count(),
                'total_revenue'       => Subscription::where('status', 'paid')->sum('amount') / 100,
                'sem2_subscribers'    => Subscription::where('status', 'paid')
                                            ->whereHas('semester', fn($q) => $q->where('number', 2))->count(),
                'sem4_subscribers'    => Subscription::where('status', 'paid')
                                            ->whereHas('semester', fn($q) => $q->where('number', 4))->count(),
            ],
        ]);
    }

    public function listPdfs(Request $request)
    {
        $pdfs = PdfFile::with('subject.semester')
            ->when($request->semester_id, function ($q) use ($request) {
                $q->whereHas('subject', fn($s) => $s->where('semester_id', $request->semester_id));
            })
            ->orderBy('subject_id')->orderBy('display_order')->get();

        return response()->json(['success' => true, 'pdfs' => $pdfs]);
    }
}