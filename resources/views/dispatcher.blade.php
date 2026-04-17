<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatcher Dashboard</title>

    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#0f172a; color:#e2e8f0; display:flex; }

        .sidebar { width:240px; background:#1e293b; height:100vh; padding:18px; box-sizing:border-box; position:relative; }
        .sidebar h2 { color:#38bdf8; margin:0 0 18px; }
        .sidebar ul { list-style:none; padding:0; margin:0; }
        .sidebar li { padding:12px; margin-bottom:10px; cursor:pointer; border-radius:8px; color:#cbd5e1; }
        .sidebar li.active { background: rgba(56,189,248,0.15); border-left:4px solid #38bdf8; }
        .badgeDot { display:inline-block; min-width: 24px; padding:2px 8px; border-radius:999px; background:#334155; color:#e2e8f0; font-size:12px; margin-left:8px; text-align:center; }
        .logout-item { position:absolute; bottom:24px; left:18px; right:18px; text-align:center; background: rgba(255,255,255,0.08); }

        .main { flex:1; padding:20px; }
        header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
        .dispatcher-id { background:#334155; padding:8px 14px; border-radius:10px; font-size:14px; }

        .tab { display:none; }
        .tab.active { display:block; }

        .grid { display:flex; flex-wrap:wrap; gap:16px; }
        .card { background:#1e293b; border-radius:12px; padding:16px; width: 360px; box-shadow:0 8px 18px rgba(0,0,0,0.25); border-left:4px solid #38bdf8; }
        .card h3 { margin:0 0 8px; }
        .muted { color:#94a3b8; font-size:13px; margin-bottom:10px; }
        .desc { background:rgba(255,255,255,0.04); padding:10px; border-radius:10px; margin-bottom:10px; }
        .thumb { width:100%; height:180px; object-fit:cover; border-radius:10px; margin:10px 0; border:1px solid rgba(255,255,255,0.08); }

        .actions { display:flex; gap:10px; margin-top:10px; }
        button { padding:10px 12px; border:0; border-radius:10px; cursor:pointer; font-weight:700; }
        .btn-claim { background:#38bdf8; color:#0f172a; }
        .btn-open { background:#22c55e; color:#0f172a; }
        .btn-view { background:#e2e8f0; color:#0f172a; }

        .rowItem { width:100%; background:#1e293b; border-radius:12px; padding:12px 14px; display:flex; justify-content:space-between; align-items:center; border-left:4px solid #64748b; }
        .rowLeft { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .statusPill { padding:4px 10px; border-radius:999px; font-size:12px; font-weight:800; }
        .status-accepted { background:rgba(34,197,94,0.18); color:#22c55e; }
        .status-denied { background:rgba(239,68,68,0.18); color:#ef4444; }
    </style>
</head>
<body>

<form id="logoutForm" action="/logout" method="POST" style="display:none;">
    @csrf
</form>

<div class="sidebar">
    <h2>DISPATCH</h2>
    <ul>
        <li class="tabLink active" data-tab="pendingTab">
            Pending <span class="badgeDot" id="pendingCount">{{ count($pending) }}</span>
        </li>
        <li class="tabLink" data-tab="claimedTab">
            Claimed <span class="badgeDot" id="claimedCount">{{ count($claimed) }}</span>
        </li>
        <li class="tabLink" data-tab="acceptedTab">Accepted</li>
        <li class="tabLink" data-tab="deniedTab">Denied</li>
        <li class="logout-item" onclick="document.getElementById('logoutForm').submit()">Logout</li>
    </ul>
</div>

<div class="main">
    <header>
        <h1>Dispatcher Panel</h1>
        <span class="dispatcher-id">ID: {{ Auth::user()->id_number }}</span>
    </header>

    @if($errors->any())
        <div style="background: rgba(239,68,68,0.15); padding:12px; border-radius:12px; margin-bottom:12px;">
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    <!-- Pending -->
    <div id="pendingTab" class="tab active">
        <h2>Pending Reports</h2>
        <div class="grid">
            @forelse($pending as $r)
                <div class="card">
                    <h3>Emergency Report #{{ $r->id }}</h3>
                    <div class="muted">
                        <div><b>Location:</b> {{ $r->location ?? '—' }}</div>
                        <div><b>Created:</b> {{ $r->created_at }}</div>
                    </div>
                    <div class="desc">{{ $r->description }}</div>

                    @if($r->file_location)
                        <img class="thumb" src="{{ asset('storage/' . $r->file_location) }}" alt="Evidence">
                    @endif

                    <div class="actions">
                        <!-- target=_blank opens the claimed chat in a new tab -->
                        <form action="/dispatcher/reports/{{ $r->id }}/claim" method="POST" target="_blank">
                            @csrf
                            <button class="btn-claim" type="submit">Claim</button>
                        </form>
                    </div>
                </div>
            @empty
                <div>No pending reports.</div>
            @endforelse
        </div>
    </div>

    <!-- Claimed -->
    <div id="claimedTab" class="tab">
        <h2>Claimed (Only You)</h2>
        <div class="grid">
            @forelse($claimed as $r)
                <div class="card" style="border-left-color:#22c55e;">
                    <h3>Report #{{ $r->id }}</h3>
                    <div class="muted">
                        <div><b>Location:</b> {{ $r->location ?? '—' }}</div>
                        <div><b>Claimed:</b> {{ $r->claimed_at ?? '—' }}</div>
                    </div>
                    <div class="desc">{{ $r->description }}</div>

                    <div class="actions">
                        <a href="/dispatcher/reports/{{ $r->id }}" target="_blank" style="text-decoration:none;">
                            <button class="btn-open" type="button">Open</button>
                        </a>
                    </div>
                </div>
            @empty
                <div>No claimed reports.</div>
            @endforelse
        </div>
    </div>

    <!-- Accepted -->
    <div id="acceptedTab" class="tab">
        <h2>Accepted</h2>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse($accepted as $r)
                <div class="rowItem">
                    <div class="rowLeft">
                        <span class="statusPill status-accepted">ACCEPTED</span>
                        <b>#{{ $r->id }}</b>
                        <span class="muted">{{ $r->created_at }}</span>
                        <span class="muted">{{ $r->dispatcher?->name ?? '—' }} ({{ $r->id_number ?? '—' }})</span>

                    </div>
                    <a href="/dispatcher/reports/{{ $r->id }}" target="_blank" style="text-decoration:none;">
                        <button class="btn-view" type="button">View</button>
                    </a>
                </div>
            @empty
                <div>No accepted reports.</div>
            @endforelse
        </div>
    </div>

    <!-- Denied -->
    <div id="deniedTab" class="tab">
        <h2>Denied</h2>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse($denied as $r)
                <div class="rowItem">
                    <div class="rowLeft">
                        <span class="statusPill status-denied">DENIED</span>
                        <b>#{{ $r->id }}</b>
                        <span class="muted">{{ $r->created_at }}</span>
                        <span class="muted">{{ $r->dispatcher?->name ?? '—' }} ({{ $r->id_number ?? '—' }})</span>

                    </div>
                    <a href="/dispatcher/reports/{{ $r->id }}" target="_blank" style="text-decoration:none;">
                        <button class="btn-view" type="button">View</button>
                    </a>
                </div>
            @empty
                <div>No denied reports.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // tab switching
    document.querySelectorAll('.tabLink').forEach(li => {
        li.addEventListener('click', () => {
            document.querySelectorAll('.tabLink').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));

            li.classList.add('active');
            document.getElementById(li.dataset.tab).classList.add('active');
        });
    });

    // polling for badge counts
    async function pollCounts() {
        const res = await fetch('/dispatcher/poll');
        if (!res.ok) return;
        const data = await res.json();

        document.getElementById('pendingCount').textContent = data.pendingCount;
        document.getElementById('claimedCount').textContent = data.claimedCount;
    }

    pollCounts();
    setInterval(pollCounts, 2000);
</script>

</body>
</html>
