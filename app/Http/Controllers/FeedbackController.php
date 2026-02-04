<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:50',
            'message' => 'required|string|min:10',
        ]);

        $message = $request->input('message');
        $isToxic = $this->checkToxicity($message);
        
        $status = $isToxic ? Feedback::STATUS_FLAGGED : Feedback::STATUS_PENDING;

        Feedback::create([
            'category' => $request->input('category'),
            'message' => $message,
            'is_toxic' => $isToxic,
            'status' => $status,
        ]);

        return redirect()->route('feedback.create')->with('success', 'Feedback submitted successfully!');
    }

    private function checkToxicity($message)
    {
        $badWords = ['bad', 'worst', 'hate', 'stupid', 'idiot'];
        
        foreach ($badWords as $word) {
            if (stripos($message, $word) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
