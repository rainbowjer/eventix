<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\AdminActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalUsers = $this->getTotalUsers($request);
        $totalEvents = $this->getTotalEvents($request);
        $totalResells = $this->getTotalResells($request);
        $totalRevenue = $this->getTotalRevenue($request);
        $users = $this->getUsers($request);
        $events = $this->getEvents($request);
        $resellTickets = $this->getResellTickets($request);
        $recentAdminLogs = $this->getRecentAdminLogs($request);
        [$salesDays, $salesCounts] = $this->getSalesTrend();
        $frequentUsers = $this->getFrequentUsers();
        $frequentOrganizers = $this->getFrequentOrganizers();
        $transactions = $this->getTransactions();
        $eventPieData = $this->getEventPieData();
        $paymentMethodData = $this->getPaymentMethodData();
        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'totalResells', 'totalRevenue',
            'users', 'events', 'resellTickets', 'recentAdminLogs',
            'salesDays', 'salesCounts', 'frequentUsers', 'frequentOrganizers',
            'transactions', 'eventPieData', 'paymentMethodData'
        ));
    }
    
    /**
     * Helper method to get date query based on filter
     */
    private function getDateQuery($dateFilter)
    {
        $now = now();
        
        switch ($dateFilter) {
            case 'today':
                return ['created_at', '>=', $now->startOfDay()];
            case 'week':
                return ['created_at', '>=', $now->startOfWeek()];
            case 'month':
                return ['created_at', '>=', $now->startOfMonth()];
            case 'year':
                return ['created_at', '>=', $now->startOfYear()];
            default:
                return [];
        }
    }

    private function getTotalUsers(Request $request) {
        $dateFilter = $request->get('date_filter');
        if ($dateFilter) {
            $dateQuery = $this->getDateQuery($dateFilter);
            return User::where($dateQuery)->count();
        }
        return User::count();
    }
    private function getTotalEvents(Request $request) {
        $dateFilter = $request->get('date_filter');
        if ($dateFilter) {
            $dateQuery = $this->getDateQuery($dateFilter);
            return Event::where($dateQuery)->count();
        }
        return Event::count();
    }
    private function getTotalResells(Request $request) {
        $dateFilter = $request->get('date_filter');
        if ($dateFilter) {
            $dateQuery = $this->getDateQuery($dateFilter);
            return Ticket::whereNotNull('resell_price')->where($dateQuery)->count();
        }
        return Ticket::whereNotNull('resell_price')->count();
    }
    private function getTotalRevenue(Request $request) {
        $dateFilter = $request->get('date_filter');
        if ($dateFilter) {
            $dateQuery = $this->getDateQuery($dateFilter);
            return Transaction::where($dateQuery)->sum('amount');
        }
        return Transaction::sum('amount');
    }
    private function getUsers(Request $request) {
        $globalSearch = $request->get('global_search');
        $searchType = $request->get('search_type', 'all');
        $userQuery = User::orderBy('created_at', 'desc');
        if ($request->filled('user_search')) {
            $search = $request->user_search;
            $userQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        if ($globalSearch && ($searchType == 'all' || $searchType == 'users')) {
            $userQuery->where(function($q) use ($globalSearch) {
                $q->where('name', 'like', "%$globalSearch%")
                  ->orWhere('email', 'like', "%$globalSearch%");
            });
        }
        return $userQuery->paginate(5);
    }
    private function getEvents(Request $request) {
        $globalSearch = $request->get('global_search');
        $searchType = $request->get('search_type', 'all');
        $eventQuery = Event::orderBy('created_at', 'desc');
        if ($request->filled('event_search')) {
            $search = $request->event_search;
            $eventQuery->where('event_name', 'like', "%$search%");
        }
        if ($globalSearch && ($searchType == 'all' || $searchType == 'events')) {
            $eventQuery->where('event_name', 'like', "%$globalSearch%");
        }
        return $eventQuery->paginate(5);
    }
    private function getResellTickets(Request $request) {
        $globalSearch = $request->get('global_search');
        $searchType = $request->get('search_type', 'all');
        $resellQuery = Ticket::select('id', 'user_id', 'event_id', 'seat_id', 'price', 'resell_price', 'resell_status')
            ->where('is_resell', true);
        if ($request->filled('resell_search')) {
            $search = $request->resell_search;
            $resellQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                        $uq->where('name', 'like', "%$search%");
                    })
                  ->orWhereHas('event', function($eq) use ($search) {
                        $eq->where('event_name', 'like', "%$search%");
                    })
                  ->orWhere('resell_status', 'like', "%$search%");
            });
        }
        if ($globalSearch && ($searchType == 'all' || $searchType == 'resells')) {
            $resellQuery->where(function($q) use ($globalSearch) {
                $q->whereHas('user', function($uq) use ($globalSearch) {
                        $uq->where('name', 'like', "%$globalSearch%");
                    })
                  ->orWhereHas('event', function($eq) use ($globalSearch) {
                        $eq->where('event_name', 'like', "%$globalSearch%");
                    })
                  ->orWhere('resell_status', 'like', "%$globalSearch%");
            });
        }
        if ($request->filled('start_date')) {
            $resellQuery->whereDate('updated_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $resellQuery->whereDate('updated_at', '<=', $request->end_date);
        }
        return $resellQuery->orderByDesc('updated_at')->paginate(5);
    }
    private function getRecentAdminLogs(Request $request) {
        $globalSearch = $request->get('global_search');
        $searchType = $request->get('search_type', 'all');
        $adminLogQuery = AdminActivityLog::select('id', 'admin_id', 'action', 'description', 'created_at');
        if ($request->filled('adminlog_search')) {
            $search = $request->adminlog_search;
            $adminLogQuery->where(function($q) use ($search) {
                $q->whereHas('admin', function($aq) use ($search) {
                        $aq->where('name', 'like', "%$search%");
                    })
                  ->orWhere('action', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }
        if ($globalSearch && ($searchType == 'all')) {
            $adminLogQuery->where(function($q) use ($globalSearch) {
                $q->whereHas('admin', function($aq) use ($globalSearch) {
                        $aq->where('name', 'like', "%$globalSearch%");
                    })
                  ->orWhere('action', 'like', "%$globalSearch%")
                  ->orWhere('description', 'like', "%$globalSearch%");
            });
        }
        if ($request->filled('start_date_adminlog')) {
            $adminLogQuery->whereDate('created_at', '>=', $request->start_date_adminlog);
        }
        if ($request->filled('end_date_adminlog')) {
            $adminLogQuery->whereDate('created_at', '<=', $request->end_date_adminlog);
        }
        return $adminLogQuery->orderByDesc('created_at')->take(5)->get();
    }
    private function getSalesTrend() {
        $salesDays = [];
        $salesCounts = [];
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $daysInMonth = $now->day;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $startOfMonth->copy()->addDays($i - 1);
            $label = $date->format('j M');
            $count = Transaction::whereDate('created_at', $date->toDateString())->count();
            $salesDays[] = $label;
            $salesCounts[] = $count;
        }
        return [$salesDays, $salesCounts];
    }
    private function getFrequentUsers() {
        return User::where('role', 'user')->orderByDesc('login_count')->take(3)->get();
    }
    private function getFrequentOrganizers() {
        return User::where('role', 'organizer')->orderByDesc('login_count')->take(3)->get();
    }
    private function getTransactions() {
        return Transaction::with('user', 'seat.event')->orderByDesc('created_at')->paginate(5);
    }
    private function getEventPieData() {
        $topEvents = Event::with(['tickets.transaction'])->withCount(['tickets as sold_count' => function($q) {
            $q->whereHas('transaction');
        }, 'tickets'])->orderByDesc('sold_count')->take(3)->get();
        $eventPieData = [];
        foreach ($topEvents as $event) {
            $sold = $event->sold_count;
            $total = $event->tickets_count;
            $unsold = max(0, $total - $sold);
            $sold_value = 0;
            $potential_value = 0;
            foreach ($event->tickets as $ticket) {
                $potential_value += $ticket->price;
                if ($ticket->transaction) {
                    $sold_value += $ticket->price;
                }
            }
            $unsold_value = max(0, $potential_value - $sold_value);
            $eventPieData[] = [
                'event_name' => $event->event_name,
                'sold' => $sold,
                'unsold' => $unsold,
                'sold_value' => $sold_value,
                'unsold_value' => $unsold_value
            ];
        }
        return $eventPieData;
    }
    private function getPaymentMethodData() {
        $paymentMethodData = Transaction::whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get()
            ->map(function($item) {
                return [
                    'method' => ucfirst(str_replace('_', ' ', $item->payment_method)),
                    'count' => $item->count,
                    'total_amount' => $item->total_amount,
                    'percentage' => 0
                ];
            });
        $totalTransactions = $paymentMethodData->sum('count');
        if ($totalTransactions > 0) {
            $paymentMethodData = $paymentMethodData->map(function($item) use ($totalTransactions) {
                $item['percentage'] = round(($item['count'] / $totalTransactions) * 100, 1);
                return $item;
            });
        }
        return $paymentMethodData;
    }

public function report(Request $request)
{
    $totalSales = Transaction::sum('amount');
    $totalTickets = Transaction::count();
    
    // Build query with search functionality
    $query = Transaction::with('user', 'seat.event');
    
    // Search by user name
    if ($request->filled('user_search')) {
        $search = $request->user_search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }
    
    // Search by event name
    if ($request->filled('event_search')) {
        $search = $request->event_search;
        $query->whereHas('seat.event', function($q) use ($search) {
            $q->where('event_name', 'like', "%$search%");
        });
    }
    
    // Search by seat label
    if ($request->filled('seat_search')) {
        $search = $request->seat_search;
        $query->whereHas('seat', function($q) use ($search) {
            $q->where('label', 'like', "%$search%");
        });
    }
    
    // Date range filter
    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    
    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }
    
    $transactions = $query->latest()->get();

    return view('admin.report', compact('totalSales', 'totalTickets', 'transactions'));
}

public function deleteUser(User $user)
{
    // Optional: Prevent admin from deleting themselves or other admins
    if ($user->role === 'admin') {
        return redirect()->back()->with('error', 'Cannot delete another admin.');
    }

    $userName = $user->name;
    $userId = $user->id;
    $user->delete();

    AdminActivityLog::create([
        'admin_id' => auth()->id(),
        'action' => 'delete_user',
        'description' => "Deleted user: $userName (ID: $userId)",
    ]);

    return redirect()->back()->with('success', 'User deleted successfully.');
}
public function organizers()
{
    $organizers = User::where('role', 'organizer')
                      ->orderBy('name')
                      ->get(); // no pagination

    return view('admin.organizer', compact('organizers'));
}
public function organizer()
{
    $organizers = User::where('role', 'organizer')
        ->withCount('events')
        ->orderBy('name')
        ->paginate(15);
    return view('admin.organizer', compact('organizers'));
}
public function exportPDF(Request $request)
{
    // Build query with search functionality (same as report method)
    $query = Transaction::with('user', 'seat.event');
    
    // Search by user name
    if ($request->filled('user_search')) {
        $search = $request->user_search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }
    
    // Search by event name
    if ($request->filled('event_search')) {
        $search = $request->event_search;
        $query->whereHas('seat.event', function($q) use ($search) {
            $q->where('event_name', 'like', "%$search%");
        });
    }
    
    // Search by seat label
    if ($request->filled('seat_search')) {
        $search = $request->seat_search;
        $query->whereHas('seat', function($q) use ($search) {
            $q->where('label', 'like', "%$search%");
        });
    }
    
    // Date range filter
    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    
    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }
    
    $transactions = $query->latest()->get();

    // Generate filename based on search criteria
    $filename = 'transactions-report';
    if ($request->filled('user_search')) {
        $filename .= '-user-' . $request->user_search;
    }
    if ($request->filled('event_search')) {
        $filename .= '-event-' . $request->event_search;
    }
    if ($request->filled('start_date')) {
        $filename .= '-from-' . $request->start_date;
    }
    $filename .= '.pdf';

    $pdf = PDF::loadView('admin.exports.transactions', compact('transactions'));
    return $pdf->download($filename);
}

    public function exportUsersPDF(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');
        
        // Apply global search if provided
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        // Apply date filter if provided
        if ($request->filled('date_filter')) {
            $dateQuery = $this->getDateQuery($request->date_filter);
            if (!empty($dateQuery)) {
                $query->where($dateQuery[0], $dateQuery[1], $dateQuery[2]);
            }
        }
        
        $users = $query->get();
        
        // Generate filename based on search criteria
        $filename = 'users-report';
        if ($request->filled('global_search')) {
            $filename .= '-search-' . $request->global_search;
        }
        if ($request->filled('date_filter')) {
            $filename .= '-' . $request->date_filter;
        }
        $filename .= '.pdf';
        
        $pdf = Pdf::loadView('admin.exports.users', compact('users'));
        return $pdf->download($filename);
    }

    public function exportEventsPDF(Request $request)
    {
        $query = Event::orderBy('created_at', 'desc');
        
        // Apply global search if provided
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where('event_name', 'like', "%$search%");
        }
        
        // Apply date filter if provided
        if ($request->filled('date_filter')) {
            $dateQuery = $this->getDateQuery($request->date_filter);
            if (!empty($dateQuery)) {
                $query->where($dateQuery[0], $dateQuery[1], $dateQuery[2]);
            }
        }
        
        $events = $query->get();
        
        // Generate filename based on search criteria
        $filename = 'events-report';
        if ($request->filled('global_search')) {
            $filename .= '-search-' . $request->global_search;
        }
        if ($request->filled('date_filter')) {
            $filename .= '-' . $request->date_filter;
        }
        $filename .= '.pdf';
        
        $pdf = Pdf::loadView('admin.exports.events', compact('events'));
        return $pdf->download($filename);
    }

    public function exportUsersCSV(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');
        
        // Apply global search if provided
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        // Apply date filter if provided
        if ($request->filled('date_filter')) {
            $dateQuery = $this->getDateQuery($request->date_filter);
            if (!empty($dateQuery)) {
                $query->where($dateQuery[0], $dateQuery[1], $dateQuery[2]);
            }
        }
        
        $users = $query->get();
        
        AdminActivityLog::create([
            'admin_id' => auth()->id(),
            'action' => 'export_users_csv',
            'description' => 'Exported users as CSV' . ($request->filled('global_search') ? ' (filtered)' : ''),
        ]);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];
        $callback = function() use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Created At']);
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at,
                ]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'users.csv', $headers);
    }

    public function exportEventsCSV(Request $request)
    {
        $query = Event::orderBy('created_at', 'desc');
        
        // Apply global search if provided
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where('event_name', 'like', "%$search%");
        }
        
        // Apply date filter if provided
        if ($request->filled('date_filter')) {
            $dateQuery = $this->getDateQuery($request->date_filter);
            if (!empty($dateQuery)) {
                $query->where($dateQuery[0], $dateQuery[1], $dateQuery[2]);
            }
        }
        
        $events = $query->get();
        
        AdminActivityLog::create([
            'admin_id' => auth()->id(),
            'action' => 'export_events_csv',
            'description' => 'Exported events as CSV' . ($request->filled('global_search') ? ' (filtered)' : ''),
        ]);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="events.csv"',
        ];
        $callback = function() use ($events) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Event Name', 'Date', 'Venue', 'Created At']);
            foreach ($events as $event) {
                fputcsv($handle, [
                    $event->id,
                    $event->event_name,
                    $event->event_date,
                    $event->venue,
                    $event->created_at,
                ]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'events.csv', $headers);
    }

    public function exportTransactionsCSV(Request $request)
    {
        // Build query with search functionality (same as report method)
        $query = Transaction::with('user', 'seat.event');
        
        // Search by user name
        if ($request->filled('user_search')) {
            $search = $request->user_search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        
        // Search by event name
        if ($request->filled('event_search')) {
            $search = $request->event_search;
            $query->whereHas('seat.event', function($q) use ($search) {
                $q->where('event_name', 'like', "%$search%");
            });
        }
        
        // Search by seat label
        if ($request->filled('seat_search')) {
            $search = $request->seat_search;
            $query->whereHas('seat', function($q) use ($search) {
                $q->where('label', 'like', "%$search%");
            });
        }
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->latest()->get();
        
        AdminActivityLog::create([
            'admin_id' => auth()->id(),
            'action' => 'export_transactions_csv',
            'description' => 'Exported transactions as CSV' . ($request->filled('user_search') || $request->filled('event_search') ? ' (filtered)' : ''),
        ]);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ];
        $callback = function() use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Event', 'Seat', 'Amount', 'Status', 'Created At']);
            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->user?->name,
                    $t->seat?->event?->event_name,
                    $t->seat?->label,
                    $t->amount,
                    $t->status,
                    $t->created_at,
                ]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'transactions.csv', $headers);
    }

    public function resellTickets(Request $request)
    {
        $query = Ticket::with(['user', 'event', 'seat'])
            ->where('is_resell', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('event', function($q2) use ($search) {
                    $q2->where('event_name', 'like', "%$search%");
                })
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })
                ->orWhereHas('seat', function($q2) use ($search) {
                    $q2->where('label', 'like', "%$search%");
                })
                ->orWhere('resell_status', 'like', "%$search%");
            });
        }

        $resellTickets = $query->orderByDesc('updated_at')->paginate(10);
        return view('admin.resell', compact('resellTickets'));
    }

    public function saveResellNote(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->resell_admin_note = $request->resell_admin_note;
        $ticket->save();
        return back()->with('success', 'Admin note saved.');
    }

    public function viewResellTicket($id)
    {
        $ticket = Ticket::with(['event', 'seat', 'user'])->findOrFail($id);
        return view('admin.resell_view', compact('ticket'));
    }

    public function viewOrganizerEvents($id)
    {
        $organizer = User::where('id', $id)->where('role', 'organizer')->firstOrFail();
        $events = Event::where('organizer_id', $organizer->id)->get();
        return view('admin.organizer_events', compact('organizer', 'events'));
    }
}