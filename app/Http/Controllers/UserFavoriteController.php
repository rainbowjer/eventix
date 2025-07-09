<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\UserFavorite;
use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{
    public function toggle(Event $event)
    {
        \Log::info('Favorite toggle called for event: ' . $event->id . ' by user: ' . auth()->id());
        
        $existingFavorite = UserFavorite::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            $isFavorited = false;
            $message = 'Event removed from favorites';
            \Log::info('Favorite removed for event: ' . $event->id);
        } else {
            UserFavorite::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
            ]);
            $isFavorited = true;
            $message = 'Event added to favorites';
            \Log::info('Favorite added for event: ' . $event->id);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'isFavorited' => $isFavorited
        ]);
    }

    public function index()
    {
        $favorites = auth()->user()->favoriteEvents()
            ->with(['organizer', 'reviews'])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('events.favorites', compact('favorites'));
    }

    public function check(Event $event)
    {
        $isFavorited = auth()->user()->hasFavoritedEvent($event->id);

        return response()->json([
            'isFavorited' => $isFavorited
        ]);
    }
}
