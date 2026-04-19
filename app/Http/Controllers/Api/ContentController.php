<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\InteractsWithApiFilters;
use App\Http\Controllers\Controller;
use App\Models\{University, Course, Semester, PdfFile, Subscription, Subject};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class ContentController extends Controller
{
    use InteractsWithApiFilters;

    public function universities(Request $request)
    {
        $query = University::query();

        $this->applySearch($query, $request->query('search'), ['name', 'short_name', 'city']);
        $this->applyBooleanFilter($query, $request, 'is_active');

        $query->when($request->filled('city'), function (Builder $query) use ($request) {
            $query->where('city', $request->query('city'));
        });

        $universities = $query->orderBy('display_order')->get()->map(function ($university) {
            return [
                'id'            => $university->id,
                'name'          => $university->name ?? '',
                'short_name'    => $university->short_name ?? '',
                'city'          => $university->city ?? '',
                'is_active'     => (bool) $university->is_active,
                'display_order' => $university->display_order ?? 0,
            ];
        });

        return response()->json(['success' => true, 'universities' => $universities]);
    }

    public function courses(Request $request, $universityId)
    {
        $query = Course::where('university_id', $universityId)
            ->when($request->filled('type'), function (Builder $query) use ($request) {
                $query->where('type', $request->query('type'));
            });

        $this->applySearch($query, $request->query('search'), ['name', 'type']);
        $this->applyBooleanFilter($query, $request, 'is_active');

        $courses = $query->orderBy('display_order')->get()->map(function ($course) {
                return [
                    'id'            => $course->id,
                    'university_id' => $course->university_id ?? 0,
                    'name'          => $course->name ?? '',
                    'type'          => $course->type ?? '',
                    'is_active'     => (bool) $course->is_active,
                    'display_order' => $course->display_order ?? 0,
                ];
            });

        return response()->json(['success' => true, 'courses' => $courses]);
    }

    public function myCourses(Request $request)
    {
        $subscriptions = Subscription::with([
                'semester:id,course_id,number,label,is_active,end_date',
                'semester.course:id,university_id,name,type,language,is_active,display_order',
                'semester.course.university:id,name,short_name,city',
            ])
            ->where('user_id', $request->user()->id)
            ->where('status', 'paid')
            ->where(function (Builder $query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
            })
            ->get()
            ->filter(fn ($subscription) => $subscription->semester?->course);

        $courses = $subscriptions
            ->groupBy(fn ($subscription) => $subscription->semester->course->id)
            ->map(function ($courseSubscriptions) {
                $course = $courseSubscriptions->first()->semester->course;

                $semesters = $courseSubscriptions
                    ->sortBy(fn ($subscription) => $subscription->semester->number ?? 0)
                    ->map(function ($subscription) {
                        $semester = $subscription->semester;

                        return [
                            'id'         => (int) $semester->id,
                            'course_id'  => (int) ($semester->course_id ?? 0),
                            'number'     => (int) ($semester->number ?? 0),
                            'label'      => $semester->label ?? '',
                            'name'       => $semester->label ?? '',
                            'title'      => $semester->label ?? '',
                            'is_active'  => (bool) $semester->is_active,
                            'end_date'   => $semester->end_date?->toDateString() ?? '',
                            'endDate'    => $semester->end_date?->toDateString() ?? '',
                            'expires_at' => $subscription->expires_at?->toDateString() ?? '',
                            'is_subscribed' => true,
                            'isSubscribed'  => true,
                        ];
                    })
                    ->values();

                return [
                    'id'                 => (int) $course->id,
                    'university_id'      => (int) ($course->university_id ?? 0),
                    'university'         => $course->university?->name ?? '',
                    'university_short'   => $course->university?->short_name ?? '',
                    'name'               => $course->name ?? '',
                    'type'               => $course->type ?? '',
                    'language'           => $course->language ?? '',
                    'is_active'          => (bool) $course->is_active,
                    'display_order'      => (int) ($course->display_order ?? 0),
                    'subscription_count' => $semesters->count(),
                    'semesters'          => $semesters,
                ];
            })
            ->sortBy('display_order')
            ->values();

        return response()->json([
            'success' => true,
            'courses' => $courses,
            'data'    => $courses,
        ]);
    }

    public function semesters(Request $request, $courseId)
    {
        $query = Semester::where('course_id', $courseId)
            ->when($request->filled('number'), function (Builder $query) use ($request) {
                $query->where('number', $request->integer('number'));
            });

        $this->applyBooleanFilter($query, $request, 'is_active');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function (Builder $searchQuery) use ($search) {
                $searchQuery->where('label', 'like', '%' . $search . '%');

                if (is_numeric($search)) {
                    $searchQuery->orWhere('number', (int) $search);
                }
            });
        }

        $semesters = $query->orderBy('number')->get();
        $user = $request->user() ?? $this->userFromBearerToken($request);

        $subscriptions = collect();
        if ($user && $semesters->isNotEmpty()) {
            $subscriptions = Subscription::where('user_id', $user->id)
                ->whereIn('semester_id', $semesters->pluck('id'))
                ->where('status', 'paid')
                ->where(function (Builder $subscriptionQuery) {
                    $subscriptionQuery->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })
                ->get()
                ->keyBy('semester_id');
        }

        $semesters = $semesters->map(function ($semester) use ($subscriptions) {
                $subscription = $subscriptions->get($semester->id);
                $semesterHasNotEnded = is_null($semester->end_date)
                    || $semester->end_date->copy()->endOfDay()->greaterThan(Carbon::now());
                $isSubscribed = (bool) ($subscription && $semesterHasNotEnded);

                return [
                    'id'         => $semester->id,
                    'course_id'  => $semester->course_id ?? 0,
                    'number'     => $semester->number ?? 0,
                    'label'      => $semester->label ?? '',
                    'name'       => $semester->label ?? '',
                    'title'      => $semester->label ?? '',
                    'is_active'  => (bool) $semester->is_active,
                    'end_date'   => $semester->end_date?->toDateString() ?? '',
                    'endDate'    => $semester->end_date?->toDateString() ?? '',
                    'is_subscribed' => $isSubscribed,
                    'isSubscribed'  => $isSubscribed,
                ];
            });

        return response()->json([
            'success'   => true,
            'semesters' => $semesters,
            'data'      => $semesters,
        ]);
    }

    public function subjects(Request $request, $semesterId)
    {
        $query = Subject::where('semester_id', $semesterId);

        $this->applySearch($query, $request->query('search'), ['name']);
        $this->applyBooleanFilter($query, $request, 'is_active');

        $subjects = $query
            ->orderBy('display_order')->get()->map(function ($subject) {
                return [
                    'id'            => $subject->id,
                    'semester_id'   => $subject->semester_id ?? 0,
                    'name'          => $subject->name ?? '',
                    'is_active'     => (bool) $subject->is_active,
                    'display_order' => $subject->display_order ?? 0,
                ];
            });

        return response()->json([
            'success'  => true,
            'subjects' => $subjects,
            'data'     => $subjects,
        ]);
    }

    private function userFromBearerToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        return PersonalAccessToken::findToken($token)?->tokenable;
    }

    public function pdfList(Request $request, $semesterId, $subjectId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'pdfs'    => [],
            ], 401);
        }

        $subject = Subject::where('id', $subjectId)
            ->where('semester_id', $semesterId)
            ->where('is_active', true)
            ->first();

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found for this semester.',
                'pdfs'    => [],
            ], 404);
        }

        $hasSubscription = Subscription::where('user_id', $user->id)
            ->where('semester_id', $semesterId)
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
            })->exists();

        $pdfs = PdfFile::where('subject_id', $subjectId)
            ->where('semester_id', $semesterId)
            ->where('is_active', true)
            ->when($request->filled('language'), function (Builder $query) use ($request) {
                $query->where('language', $request->query('language'));
            })
            ->when($request->filled('type'), function (Builder $query) use ($request) {
                $query->where('type', $request->query('type'));
            })
            ->when($request->filled('is_free'), function (Builder $query) use ($request) {
                $query->where('is_free', $request->boolean('is_free'));
            })
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $query->where('title', 'like', '%' . trim((string) $request->query('search')) . '%');
            })
            ->with('subject:id,name,display_order')
            ->orderBy('display_order')
            ->get();

        $pdfs = $pdfs->map(function ($pdf) use ($hasSubscription) {
            return [
                'id'        => (int) $pdf->id,
                'title'     => $pdf->title ?? '',
                'type'      => $pdf->type ?? '',
                'language'  => $pdf->language ?? 'english',
                'subject_id'=> (int) ($pdf->subject_id ?? 0),
                'subject'   => $pdf->subject->name ?? '',
                'is_free'   => (bool) $pdf->is_free,
                'is_locked' => !$pdf->is_free && !$hasSubscription,
            ];
        });

        return response()->json([
            'success'          => true,
            'has_subscription' => $hasSubscription,
            'pdfs'             => $pdfs,
        ]);
    }

    public function getPdfUrl(Request $request, $pdfId)
    {
        $user = $request->user();
        $pdf  = PdfFile::with('subject.semester')->findOrFail($pdfId);

        if (!$pdf->is_free) {
            $semesterId = $pdf->subject->semester->id;
            $hasSubscription = Subscription::where('user_id', $user->id)
                ->where('semester_id', $semesterId)
                ->where('status', 'paid')
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
                })->exists();

            if (!$hasSubscription) {
                return response()->json([
                    'success'     => false,
                    'message'     => 'Please purchase subscription to access this content.',
                    'error'       => 'subscription_required',
                    'semester_id' => $semesterId,
                ], 403);
            }
        }

        $signedPath = url()->temporarySignedRoute(
            'pdf.serve',
            Carbon::now()->addHour(),
            ['pdf' => $pdfId],
            false
        );
        $url = $request->getSchemeAndHttpHost() . $signedPath;

        return response()->json([
            'success'    => true,
            'url'        => $url,
            'pdf_url'    => $url,
            'file_url'   => $url,
            'download_url' => $url,
            'title'      => $pdf->title,
            'expires_in' => 3600,
        ]);
    }

    public function servePdf(Request $request, $pdfId)
    {
        if (!$request->hasValidSignature(false)) {
            abort(403, 'Link expired or invalid.');
        }

        $pdf = PdfFile::findOrFail($pdfId);

        return response()->file(storage_path('app/public/' . $pdf->file_path), [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'no-store, no-cache',
        ]);
    }
}
