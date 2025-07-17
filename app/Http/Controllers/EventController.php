<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RecentlyViewedEvent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Models\User;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role === 'admin';
        $query = $isAdmin ? Event::query() : Event::where('organizer_id', $user->id);

        // Search by event name/location/description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                ;
            });
        }

        // Filter by organizer (admin only)
        if ($isAdmin && $request->filled('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }

        // Date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('event_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date')) {
            $today = Carbon::today();
            if ($request->date == 'today') {
                $query->whereDate('event_date', $today);
            } elseif ($request->date == 'this-week') {
                $query->whereBetween('event_date', [$today, $today->copy()->endOfWeek()]);
            } elseif ($request->date == 'this-month') {
                $query->whereMonth('event_date', $today->month);
            }
        }

        // Export to CSV (admin only)
        if ($isAdmin && $request->has('export') && $request->export === 'csv') {
            $events = $query->with('organizer')->orderBy('event_date')->get();
            $filename = 'events_export_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$filename",
            ];
            $columns = ['ID', 'Event Name', 'Date', 'Time', 'Location', 'Organizer', 'Description'];
            $callback = function() use ($events, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($events as $event) {
                    fputcsv($file, [
                        $event->id,
                        $event->event_name,
                        $event->event_date,
                        $event->event_time,
                        $event->location,
                        $event->organizer ? $event->organizer->name : '',
                        $event->description,
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        $events = $query->with('organizer')->orderBy('event_date')->get();
        $organizers = $isAdmin ? User::where('role', 'organizer')->get() : collect();
        return view('events.index', compact('events', 'organizers', 'isAdmin'));
    }

    public function show($id)
    {
        $event = Event::with('seats')->findOrFail($id);
        
        // Track recently viewed event
        if (auth()->check()) {
            $this->trackRecentlyViewed($event);
        }
        
        return view('events.show', compact('event'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'organizer') {
            abort(403, 'Only organizers can access this page.');
        }

        return view('events.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'organizer') {
            abort(403, 'Only organizers can create events.');
        }

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required', // ✅ added
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $filename = time() . '_' . $image->getClientOriginalName();

            $manager = new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
            $resizedImage = $manager->read($image)->cover(1920, 600)->toJpeg();

            Storage::disk('public')->put("event_banners/$filename", $resizedImage);
            $imagePath = "event_banners/$filename";
        }

        $event = Event::create([
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time, // ✅ added
            'location' => $request->location,
            'description' => $request->description,
            'banner_image' => $imagePath,
            'organizer_id' => auth()->id(),
        ]);

        // Auto-create seats
        foreach (['A', 'B', 'C', 'D', 'E'] as $row) {
            for ($i = 1; $i <= 10; $i++) {
                $label = $row . $i;

                if ($row === 'A') {
                    $type = 'VIP';
                    $price = 150;
                } elseif (in_array($row, ['B', 'C'])) {
                    $type = 'GENERAL';
                    $price = 80;
                } else {
                    $type = 'ECONOMY';
                    $price = 50;
                }

                \App\Models\Seat::create([
                    'event_id' => $event->id,
                    'label' => $label,
                    'type' => $type,
                    'price' => $price,
                ]);
            }
        }

        return redirect()->route('events.index')->with('success', 'Event created with seats!');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required', // ✅ added
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $filename = time() . '_' . $image->getClientOriginalName();

            $manager = new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
            $resizedImage = $manager->read($image)->cover(1920, 600)->toJpeg();

            Storage::disk('public')->put("event_banners/$filename", $resizedImage);
            $event->banner_image = "event_banners/$filename";
        }

        $event->event_time = $request->event_time; // ✅ update time
        $event->update($request->only(['event_name', 'event_date', 'location', 'description']));
        $event->save(); // ✅ ensure everything is saved

        return redirect()->route('events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted!');
    }

    public function all(Request $request)
    {
        $locations = Event::select('location')->distinct()->pluck('location');
        $query = Event::query();
        $query->whereDate('event_date', '>=', Carbon::today());

        // Search by event name, location, or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Date filter
        if ($request->filled('date')) {
            $today = Carbon::today();
            if ($request->date == 'today') {
                $query->whereDate('event_date', $today);
            } elseif ($request->date == 'this-week') {
                $query->whereBetween('event_date', [$today, $today->copy()->endOfWeek()]);
            } elseif ($request->date == 'this-month') {
                $query->whereMonth('event_date', $today->month);
            }
        }

        // Capacity filter
        if ($request->filled('capacity')) {
            if ($request->capacity === 'available') {
                $query->whereHas('seats', function($q) {
                    $q->whereDoesntHave('transactions');
                });
            } elseif ($request->capacity === 'sold-out') {
                $query->whereDoesntHave('seats', function($q) {
                    $q->whereDoesntHave('transactions');
                });
            } elseif ($request->capacity === 'hot') {
                // Get all events first, then filter for hot events
                $allEvents = $query->get();
                $hotEventIds = $allEvents->filter(function($event) {
                    return $event->isHotEvent();
                })->pluck('id');
                
                $query->whereIn('id', $hotEventIds);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'soonest');
        if ($sort == 'latest') {
            $query->orderBy('event_date', 'desc');
        } else {
            $query->orderBy('event_date', 'asc');
        }

        $events = $query->with(['organizer', 'reviews'])->get();
        
        // Get recently viewed events for authenticated users
        $recentlyViewed = collect();
        if (auth()->check()) {
            $recentlyViewed = auth()->user()->recentlyViewedEvents()
                ->with('event.organizer')
                ->orderBy('viewed_at', 'desc')
                ->limit(5)
                ->get()
                ->pluck('event');
        }

        return view('events.all', compact('events', 'locations', 'recentlyViewed'));
    }

    public function recentlyViewed()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $recentlyViewed = auth()->user()->recentlyViewedEvents()
            ->with('event.organizer')
            ->orderBy('viewed_at', 'desc')
            ->get()
            ->pluck('event');

        return view('events.recently-viewed', compact('recentlyViewed'));
    }

    public function quickView($id)
    {
        $event = Event::with(['organizer', 'reviews.user', 'seats'])->findOrFail($id);
        
        // Track recently viewed event
        if (auth()->check()) {
            $this->trackRecentlyViewed($event);
        }

        $isFavorited = auth()->check() ? auth()->user()->hasFavoritedEvent($event->id) : false;
        $userReview = auth()->check() ? $event->reviews()->where('user_id', auth()->id())->first() : null;

        $html = view('events.partials.quick-view', compact('event', 'isFavorited', 'userReview'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function approve($id)
    {
        $event = Event::findOrFail($id);
        $event->approved = true;
        $event->save();
        return redirect()->back()->with('success', 'Event approved successfully.');
    }

    public function publish($id)
    {
        $event = Event::findOrFail($id);
        $event->published = true;
        $event->save();
        return redirect()->back()->with('success', 'Event published successfully.');
    }

    public function unpublish($id)
    {
        $event = Event::findOrFail($id);
        $event->published = false;
        $event->save();
        return redirect()->back()->with('success', 'Event unpublished successfully.');
    }

    private function trackRecentlyViewed($event)
    {
        // Remove existing entry if exists
        RecentlyViewedEvent::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->delete();

        // Add new entry
        RecentlyViewedEvent::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'viewed_at' => now(),
        ]);

        // Keep only last 20 recently viewed events
        $recentEvents = RecentlyViewedEvent::where('user_id', auth()->id())
            ->orderBy('viewed_at', 'desc')
            ->get();

        if ($recentEvents->count() > 20) {
            $recentEvents->slice(20)->each(function($event) {
                $event->delete();
            });
        }
    }
}