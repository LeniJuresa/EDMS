<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /* =========================
       ANONYMOUS USER SIDE
       ========================= */

    public function store(Request $request)
    {
        $data = $request->validate([
            'location' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:5'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public'); // returns "reports/xxx.jpg"
        }

        $report = Report::create([
            'session_id' => $request->session()->getId(),
            'location' => $data['location'],
            'description' => $data['description'],
            'file_location' => $path,
            'status' => 'pending',
            'messages' => [
                ['sender' => 'system', 'text' => 'Report submitted. Waiting for a dispatcher.', 'ts' => now()->toISOString()],
            ],
        ]);

        return redirect("/report/{$report->id}");
    }

    public function showAdminReview($id)
    {
        $report = \App\Models\Report::with('dispatcher')->findOrFail($id);

        // Only review finished reports
        if (!in_array($report->status, ['accepted', 'denied'])) {
            abort(403);
        }

        $mode = 'review'; // disables chat + accept/deny in the shared view

        // Reuse the dispatcher finished-report view
        return view('dispatcher_report', compact('report', 'mode'));
    }
    public function showAdmin($id)
    {
        $report = \App\Models\Report::with('dispatcher')->findOrFail($id);

        if (!in_array($report->status, ['accepted', 'denied'])) {
            abort(403);
        }

        return view('admin_report', compact('report'));
    }
    public function showAnonymous(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if ($report->session_id !== $request->session()->getId()) {
            abort(403);
        }

        return view('report_chat', compact('report'));
    }

    public function pollAnonymous(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if ($report->session_id !== $request->session()->getId()) {
            abort(403);
        }

        return response()->json([
            'status' => $report->status,
            'messages' => $report->messages ?? [],
            'closed_at' => $report->closed_at,
        ]);
    }

    public function sendAnonymousMessage(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if ($report->session_id !== $request->session()->getId()) abort(403);
        if (in_array($report->status, ['accepted', 'denied'])) abort(403, 'Chat closed');

        $data = $request->validate([
            'text' => ['required', 'string', 'max:1000'],
        ]);

        $messages = $report->messages ?? [];
        $messages[] = ['sender' => 'citizen', 'text' => $data['text'], 'ts' => now()->toISOString()];

        $report->messages = $messages;
        $report->save();

        return response()->json(['ok' => true]);
    }

    /* =========================
       DISPATCHER SIDE
       ========================= */

    public function dispatcherDashboard()
    {
        $user = auth()->user();

        // Pending: visible to all dispatchers
        $pending = Report::where('status', 'pending')
            ->latest()
            ->get();

        // Claimed: only visible to the dispatcher who claimed it
        $claimed = Report::where('status', 'claimed')
            ->where('id_number', $user->id_number)
            ->latest()
            ->get();

        // Accepted: visible to ALL dispatchers (NO id_number filter)
        $accepted = Report::where('status', 'accepted')
            ->with('dispatcher')
            ->latest()
            ->get();

        // Denied: visible to ALL dispatchers (NO id_number filter)
        $denied = Report::where('status', 'denied')
            ->with('dispatcher')
            ->latest()
            ->get();

        return view('dispatcher', compact('pending', 'claimed', 'accepted', 'denied'));
    }

    // lightweight polling endpoint for dashboard
    public function dispatcherPoll()
    {
        $user = auth()->user();

        $pendingCount = Report::where('status', 'pending')->count();
        $claimedCount = Report::where('status', 'claimed')->where('id_number', $user->id_number)->count();

        return response()->json([
            'pendingCount' => $pendingCount,
            'claimedCount' => $claimedCount,
        ]);
    }

    // Claim: pending -> claimed, set dispatcher id_number, set claimed_at, notify citizen via system message
    public function claim($id)
    {
        $user = auth()->user();
        $report = Report::findOrFail($id);

        if ($report->status !== 'pending') {
            return redirect('/dispatcher')->withErrors(['claim' => 'This report is no longer pending.']);
        }

        $report->status = 'claimed';
        $report->id_number = $user->id_number;
        $report->claimed_at = now();

        $messages = $report->messages ?? [];
        $messages[] = [
            'sender' => 'system',
            'text' => 'Your report is under review.',
            'ts' => now()->toISOString()
        ];
        $report->messages = $messages;
        $report->save();

        // opens new tab (from the dashboard form target="_blank") and lands here:
        return redirect("/dispatcher/reports/{$report->id}");
    }

    public function showDispatcher($id)
    {
        $user = auth()->user();
        $report = Report::findOrFail($id);

        // only the claiming dispatcher can view
        if ($report->id_number !== $user->id_number) abort(403);

        return view('dispatcher_report', compact('report'));
    }

    public function pollDispatcher($id)
    {
        $user = auth()->user();
        $report = Report::findOrFail($id);

        if ($report->id_number !== $user->id_number) abort(403);

        return response()->json([
            'status' => $report->status,
            'messages' => $report->messages ?? [],
            'closed_at' => $report->closed_at,
        ]);
    }

    public function sendDispatcherMessage(Request $request, $id)
    {
        $user = auth()->user();
        $report = Report::findOrFail($id);

        if ($report->id_number !== $user->id_number) abort(403);
        if (in_array($report->status, ['accepted', 'denied'])) abort(403, 'Chat closed');

        $data = $request->validate([
            'text' => ['required', 'string', 'max:1000'],
        ]);

        $messages = $report->messages ?? [];
        $messages[] = ['sender' => 'dispatcher', 'text' => $data['text'], 'ts' => now()->toISOString()];

        $report->messages = $messages;
        $report->save();

        return response()->json(['ok' => true]);
    }

    public function accept($id) { return $this->closeReport($id, 'accepted'); }
    public function deny($id) { return $this->closeReport($id, 'denied'); }

    private function closeReport($id, string $finalStatus)
    {
        $user = auth()->user();
        $report = Report::findOrFail($id);

        if ($report->id_number !== $user->id_number) abort(403);
        if ($report->status !== 'claimed') {
            return redirect("/dispatcher/reports/{$id}")->withErrors(['close' => 'Report is not in claimed state.']);
        }

        $report->status = $finalStatus;
        $report->closed_at = now();

        $messages = $report->messages ?? [];
        $messages[] = [
            'sender' => 'system',
            'text' => "Your report has been {$finalStatus}. Thank you.",
            'ts' => now()->toISOString()
        ];
        $report->messages = $messages;

        $report->save();

        // return to dashboard (dispatcher report page will become read-only on next poll)
        return redirect('/dispatcher');
    }
}
