<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ResellController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\OrganizerTicketController;
use App\Http\Controllers\OrganizerResellController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventReviewController;
use App\Http\Controllers\UserFavoriteController;

Route::get('/test-error', function() {
    abort(500, 'Test error');
});


Route::get('/welcome', function (Request $request) {
    $query = Event::query();

    // If search keyword is entered
    if ($request->filled('search')) {
        $query->where('event_name', 'like', '%' . $request->search . '%');
    }

    // Only get events from today up to 2 months ahead
    $start = Carbon::today();
    $end = Carbon::today()->addMonths(2);
    $events = $query->whereBetween('event_date', [$start, $end])
                    ->orderBy('event_date', 'asc')
                    ->take(6) // Optional: show only 6 latest upcoming events
                    ->get();

    return view('welcome', compact('events'));
})->middleware(['auth', 'verified'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [ProfileController::class, 'userProfile'])->name('user.profile');
});
// User profile picture upload
Route::post('/user/profile/upload', [ProfileController::class, 'uploadPicture'])->name('user.profile.upload');

// User password update
Route::put('/user/password/update', [ProfileController::class, 'updatePassword'])->name('user.password.update');

Route::put('/user/profile/update', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
Route::delete('/user/profile/delete-picture', [ProfileController::class, 'deletePicture'])->name('user.profile.deletePicture');

// Allow guests to view all events
Route::get('/events/all', [App\Http\Controllers\EventController::class, 'all'])->name('events.all');

// Event reviews (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/reviews', [EventReviewController::class, 'show'])->name('events.reviews.show');
    Route::post('/events/{event}/reviews', [EventReviewController::class, 'store'])->name('events.reviews.store');
    Route::put('/events/{event}/reviews', [EventReviewController::class, 'update'])->name('events.reviews.update');
    Route::delete('/events/{event}/reviews', [EventReviewController::class, 'destroy'])->name('events.reviews.destroy');
});

// User favorites (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/favorites/toggle', [UserFavoriteController::class, 'toggle'])->name('events.favorites.toggle');
    Route::get('/events/{event}/favorites/check', [UserFavoriteController::class, 'check'])->name('events.favorites.check');
    Route::get('/favorites', [UserFavoriteController::class, 'index'])->name('events.favorites');
});

// Recently viewed events (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::get('/recently-viewed', [EventController::class, 'recentlyViewed'])->name('events.recently-viewed');
});

// Quick view modal (available to all users)
Route::get('/events/{event}/quick-view', [EventController::class, 'quickView'])->name('events.quick-view');

Route::middleware(['auth'])->group(function () {
    Route::resource('events', EventController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/book/confirmation', [BookingController::class, 'confirmation'])->name('book.confirmation');
    Route::get('/book/{event}', [BookingController::class, 'create'])->name('book.ticket');
    Route::post('/book/{event}/prepare', [BookingController::class, 'preparePayment'])->name('book.prepare');
    Route::post('/payments/process', [BookingController::class, 'processPayment'])->name('payments.process');
    Route::get('/my-bookings', [BookingController::class, 'history']) ->middleware('auth')->name('book.history');
    Route::get('/payments/confirm', [BookingController::class, 'showPaymentConfirmation'])->name('payments.confirm');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/tickets', [OrganizerTicketController::class, 'index'])->name('organizer.tickets');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/resell/{transactionId}', [ResellController::class, 'show'])->name('resell.show'); // resell form
    Route::post('/resell/{transactionId}', [ResellController::class, 'post'])->name('resell.post'); // handle submission
});



Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/resell', [OrganizerResellController::class, 'index'])->name('organizer.resell');
    
});
Route::get('/organizer/resell-requests', [OrganizerResellController::class, 'index'])->name('organizer.resell.requests');
Route::put('/organizer/resell/{ticket}/approve', [OrganizerResellController::class, 'approve'])->name('organizer.resell.approve');
Route::put('/organizer/resell/{ticket}/reject', [OrganizerResellController::class, 'reject'])->name('organizer.resell.reject');

Route::get('/my-resell-tickets', [ResellController::class, 'myResellTickets'])->name('resell.my');
Route::post('/notifications/mark-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');

Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/dashboard', function () {
        return view('organizer.dashboard');
    })->name('organizer.dashboard');
});


Route::get('/', function (Request $request) {
    $query = Event::query();

    // If search keyword is entered
    if ($request->filled('search')) {
        $query->where('event_name', 'like', '%' . $request->search . '%');
    }

    // Only get events from today up to 2 months ahead
    $start = Carbon::today();
    $end = Carbon::today()->addMonths(2);
    $events = $query->whereBetween('event_date', [$start, $end])
                    ->orderBy('event_date', 'asc')
                    ->take(6) // Optional: show only 6 latest upcoming events
                    ->get();

    return view('welcome', compact('events'));
});

Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');


Route::middleware(['auth', 'adminmiddleware'])->group(function () {
    Route::get('/admin/organizer', [AdminController::class, 'organizer'])->name('admin.organizer');
    Route::get('/admin/resell-tickets', [AdminController::class, 'resellTickets'])->name('admin.resell.tickets');
});

Route::get('/admin/organizer/{id}/events', [App\Http\Controllers\AdminController::class, 'viewOrganizerEvents'])->name('admin.organizer.events');
Route::post('/admin/resell/{id}/note', [App\Http\Controllers\AdminController::class, 'saveResellNote'])->name('admin.resell.note');
Route::get('/admin/resell/{id}', [App\Http\Controllers\AdminController::class, 'viewResellTicket'])->name('admin.resell.view');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::get('/admin/export-pdf', [AdminController::class, 'exportPDF'])->name('admin.export.pdf');
Route::get('/admin/export/users', [App\Http\Controllers\AdminController::class, 'exportUsersCSV'])->name('admin.export.users');
Route::get('/admin/export/events', [App\Http\Controllers\AdminController::class, 'exportEventsCSV'])->name('admin.export.events');
Route::get('/admin/export/transactions', [App\Http\Controllers\AdminController::class, 'exportTransactionsCSV'])->name('admin.export.transactions');
Route::get('/admin/export-users-pdf', [App\Http\Controllers\AdminController::class, 'exportUsersPDF'])->name('admin.export.users.pdf');
Route::get('/admin/export-events-pdf', [App\Http\Controllers\AdminController::class, 'exportEventsPDF'])->name('admin.export.events.pdf');

Route::get('/download-qr/{transaction}', [BookingController::class, 'downloadQr'])->name('download.qr');

// TEMPORARILY REMOVE MIDDLEWARE FOR TESTING
Route::resource('admin/events', App\Http\Controllers\EventController::class)->names('admin.events');
// Route::middleware(['auth', 'adminmiddleware'])->group(function () {
//     Route::resource('admin/events', App\Http\Controllers\EventController::class)->names('admin.events');
//     Route::post('/admin/events/{id}/approve', [App\Http\Controllers\EventController::class, 'approve'])->name('admin.events.approve');
//     Route::post('/admin/events/{id}/publish', [App\Http\Controllers\EventController::class, 'publish'])->name('admin.events.publish');
//     Route::post('/admin/events/{id}/unpublish', [App\Http\Controllers\EventController::class, 'unpublish'])->name('admin.events.unpublish');
// });

// Admin user management
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::get('admin/report/export/pdf', [AdminController::class, 'exportPDF'])->name('admin.report.export.pdf');

require __DIR__.'/auth.php';
