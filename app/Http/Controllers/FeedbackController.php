<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Category;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FeedbackController extends Controller
{
    /**
     * Display the feedback submission form.
     */
    public function submit()
    {
        $categories = Category::where('is_active', true)->get();
        return view('feedback.submit', compact('categories'));
    }

    /**
     * Store a newly submitted feedback anonymously.
     */
    public function store(StoreFeedbackRequest $request)
    {
        // Generate anonymous token (hashed to ensure privacy)
        $anonymousToken = Hash::make(
            auth()->id() . time() . rand(1000, 9999)
        );

        // Create feedback without user_id for anonymity
        $feedback = Feedback::create([
            'category_id' => $request->category_id,
            'content' => $request->content,
            'anonymous_token' => $anonymousToken,
            'status' => 'pending', // Default status
        ]);

        // Check for toxic content and auto-flag if necessary
        if ($feedback->containsToxicContent()) {
            $feedback->update([
                'status' => 'flagged',
                'moderation_note' => 'Auto-flagged: Potential toxic content detected',
            ]);
        }

        return redirect()
            ->route('feedback.submit')
            ->with('success', 'Your feedback has been submitted anonymously. Thank you!');
    }

    /**
     * Display a listing of all feedback (admin only).
     */
    public function index()
    {
        $feedback = Feedback::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('feedback.index', compact('feedback'));
    }
}
