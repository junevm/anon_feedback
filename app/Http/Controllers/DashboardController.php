<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Quick stats
        $stats = [
            'total_feedback' => Feedback::count(),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
            'approved_feedback' => Feedback::where('status', 'approved')->count(),
            'flagged_feedback' => Feedback::where('status', 'flagged')->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
        ];

        // Recent feedback
        $recentFeedback = Feedback::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Category distribution
        $categoryDistribution = Category::withCount('feedback')
            ->orderBy('feedback_count', 'desc')
            ->limit(5)
            ->get();

        // Weekly trend
        $weeklyTrend = Feedback::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact('stats', 'recentFeedback', 'categoryDistribution', 'weeklyTrend'));
    }
}
