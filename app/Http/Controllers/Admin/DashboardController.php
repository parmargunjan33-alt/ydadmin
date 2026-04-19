<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\University;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\PdfFile;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'total_universities' => University::count(),
            'total_courses' => Course::count(),
            'total_semesters' => Semester::count(),
            'total_subjects' => Subject::count(),
            'total_pdfs' => PdfFile::count(),
        ];

        // Recent subscriptions
        $recent_subscriptions = Subscription::with(['user', 'semester'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent users
        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get user growth data (last 7 days)
        $user_growth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top universities by user count
        $top_universities = University::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        // Subscription status breakdown
        $subscription_status = Subscription::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'stats',
            'recent_subscriptions',
            'recent_users',
            'user_growth',
            'top_universities',
            'subscription_status'
        ));
    }
}
