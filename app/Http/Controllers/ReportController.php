<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feedback;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function analytics()
    {
        // Overall statistics
        $totalFeedback = Feedback::count();
        $pendingFeedback = Feedback::where('status', 'pending')->count();
        $approvedFeedback = Feedback::where('status', 'approved')->count();
        $flaggedFeedback = Feedback::where('status', 'flagged')->count();

        // Category breakdown
        $categoryStats = Category::withCount(['feedback as total_feedback'])
            ->withCount(['feedback as approved_feedback' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['feedback as flagged_feedback' => function ($query) {
                $query->where('status', 'flagged');
            }])
            ->get();

        // Recent trends (last 7 days)
        $trendData = Feedback::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.analytics', compact(
            'totalFeedback',
            'pendingFeedback',
            'approvedFeedback',
            'flaggedFeedback',
            'categoryStats',
            'trendData'
        ));
    }

    /**
     * Display trends over time.
     */
    public function trends(Request $request)
    {
        $days = $request->input('days', 30);
        
        // Feedback trends
        $feedbackTrends = Feedback::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "flagged" THEN 1 ELSE 0 END) as flagged')
            )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Category trends
        $categoryTrends = Feedback::select(
                'categories.name',
                DB::raw('COUNT(*) as count')
            )
            ->join('categories', 'feedback.category_id', '=', 'categories.id')
            ->where('feedback.created_at', '>=', now()->subDays($days))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        return view('reports.trends', compact('feedbackTrends', 'categoryTrends', 'days'));
    }

    /**
     * Generate and store a new report.
     */
    public function generate(Request $request)
    {
        $type = $request->input('type', 'analytics');
        
        $data = [
            'total_feedback' => Feedback::count(),
            'pending' => Feedback::where('status', 'pending')->count(),
            'approved' => Feedback::where('status', 'approved')->count(),
            'flagged' => Feedback::where('status', 'flagged')->count(),
            'by_category' => Category::withCount('feedback')->get()->toArray(),
        ];

        $report = Report::create([
            'title' => ucfirst($type) . ' Report - ' . now()->format('Y-m-d'),
            'type' => $type,
            'data' => $data,
            'report_date' => now(),
            'generated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('reports.analytics')
            ->with('success', 'Report generated successfully.');
    }
}
