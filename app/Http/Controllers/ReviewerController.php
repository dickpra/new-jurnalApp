<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\ReviewRecommendation;

class ReviewerController extends Controller
{
    public function index()
    {
        $assignments = Review::with('submission.journalTheme')
            ->where('reviewer_id', Auth::id())
            ->latest()
            ->get();

        return view('reviewer.dashboard', compact('assignments'));
    }

    public function show($id)
    {
        $review = Review::with('submission.journalTheme')->findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('reviewer.evaluate', compact('review'));
    }

    public function store(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'recommendation' => 'required|string',
            'notes_for_author' => 'required|string',
            'notes_for_manager' => 'nullable|string',
        ]);

        $review->update([
            'recommendation' => ReviewRecommendation::from($request->recommendation),
            'notes_for_author' => $request->notes_for_author,
            'notes_for_manager' => $request->notes_for_manager,
            'is_completed' => true,
        ]);

        return redirect()->route('reviewer.dashboard')->with('success', 'Evaluation submitted successfully to the Journal Manager.');
    }

    public function download($id)
    {
        $review = Review::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return Storage::disk('local')->download($review->submission->manuscript_file);
    }
}