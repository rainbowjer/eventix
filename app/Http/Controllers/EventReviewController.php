<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventReview;
use Illuminate\Http\Request;

class EventReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this event
        $existingReview = EventReview::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this event'
            ], 400);
        }

        $review = EventReview::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'review' => $review->load('user')
        ]);
    }

    public function show(Event $event)
    {
        $review = EventReview::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        return response()->json([
            'review' => $review
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $review = EventReview::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'review' => $review->load('user')
        ]);
    }

    public function destroy(Event $event)
    {
        $review = EventReview::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
}
