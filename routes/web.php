<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use App\Models\Report;
use Carbon\Carbon;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Anonymous report form page
Route::get('/report', function () {
    return view('report');
});


// Login page
Route::get('/login', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->is_admin) return redirect('/admin');
        if ($user->is_dispatcher) return redirect('/dispatcher');
        return redirect('/');
    }
    return view('login');
})->name('login');

// Auth actions
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Anonymous report creation + chat
Route::post('/report', [ReportController::class, 'store']);
Route::get('/report/{id}', [ReportController::class, 'showAnonymous']);
Route::get('/report/{id}/poll', [ReportController::class, 'pollAnonymous']);
Route::post('/report/{id}/message', [ReportController::class, 'sendAnonymousMessage']);

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {

        $dispatchers = User::where('is_dispatcher', 1)->get();
        $admins = User::where('is_admin', 1)->get();

        // Admin "finished reports" list + resolution time
        $finalReports = Report::whereIn('status', ['accepted', 'denied'])
            ->with('dispatcher')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($r) {
                $end = $r->closed_at ?? $r->updated_at;
                $seconds = $r->created_at->diffInSeconds($end);

                $r->duration_human = Carbon::createFromTimestamp(0)
                    ->addSeconds($seconds)
                    ->diffForHumans(null, true);

                return $r;
            });

        // Dashboard counts
        $totalReports    = Report::count();
        $pendingReports  = Report::where('status', 'pending')->count();
        $claimedReports  = Report::where('status', 'claimed')->count();
        $acceptedReports = Report::where('status', 'accepted')->count();
        $deniedReports   = Report::where('status', 'denied')->count();

        return view('admin', compact(
            'dispatchers',
            'admins',
            'finalReports',
            'totalReports',
            'pendingReports',
            'claimedReports',
            'acceptedReports',
            'deniedReports'
        ));
    });

    Route::post('/admin', [UserController::class, 'registerFromAdmin']);
    Route::get('/admin/download/{id}', [UserController::class, 'downloadStaffFile']);

    // Admin view report (reuses dispatcher_report view in review mode)
    Route::get('/admin/reports/{id}', [ReportController::class, 'showAdminReview']);
});

// Dispatcher routes
Route::middleware(['auth', 'dispatcher'])->group(function () {

    Route::get('/dispatcher', [ReportController::class, 'dispatcherDashboard']);

    Route::get('/dispatcher/poll', [ReportController::class, 'dispatcherPoll']);

    Route::post('/dispatcher/reports/{id}/claim', [ReportController::class, 'claim']);

    Route::get('/dispatcher/reports/{id}', [ReportController::class, 'showDispatcher']);
    Route::get('/dispatcher/reports/{id}/poll', [ReportController::class, 'pollDispatcher']);
    Route::post('/dispatcher/reports/{id}/message', [ReportController::class, 'sendDispatcherMessage']);

    Route::post('/dispatcher/reports/{id}/accept', [ReportController::class, 'accept']);
    Route::post('/dispatcher/reports/{id}/deny', [ReportController::class, 'deny']);
});
