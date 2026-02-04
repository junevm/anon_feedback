<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Simple pagination
        $feedbacks = Feedback::latest()->paginate(10);
        return view('admin.index', compact('feedbacks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,approved,flagged'
        ]);

        $feedback->status = $request->input('status');
        $feedback->save();

        return redirect()->back()->with('success', 'Status updated.');
    }
}
