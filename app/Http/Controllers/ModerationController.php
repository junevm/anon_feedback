<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    /**
     * Display the moderation queue.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $feedback = Feedback::with('category')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $counts = [
            'pending' => Feedback::where('status', 'pending')->count(),
            'approved' => Feedback::where('status', 'approved')->count(),
            'flagged' => Feedback::where('status', 'flagged')->count(),
        ];

        return view('moderation.index', compact('feedback', 'counts', 'status'));
    }

    /**
     * Approve a feedback submission.
     */
    public function approve(Request $request, Feedback $feedback)
    {
        $feedback->update([
            'status' => 'approved',
            'moderation_note' => $request->input('note'),
            'moderated_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Feedback approved successfully.');
    }

    /**
     * Flag a feedback submission.
     */
    public function flag(Request $request, Feedback $feedback)
    {
        $feedback->update([
            'status' => 'flagged',
            'moderation_note' => $request->input('note', 'Flagged for review'),
            'moderated_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Feedback flagged successfully.');
    }

    /**
     * Unflag/reset a feedback submission back to pending.
     */
    public function reset(Feedback $feedback)
    {
        $feedback->update([
            'status' => 'pending',
            'moderation_note' => null,
            'moderated_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Feedback reset to pending.');
    }
}
