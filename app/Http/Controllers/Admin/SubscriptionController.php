<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');
        $courseId = $request->query('course_id');
        $semesterId = $request->query('semester_id');

        $query = Subscription::with(['user', 'semester.course']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($courseId) {
            $query->whereHas('semester', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $subscriptions = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $semesters = Semester::orderBy('label')->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'users', 'courses', 'semesters', 'userId', 'courseId', 'semesterId'));
    }

    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully!');
    }
}
