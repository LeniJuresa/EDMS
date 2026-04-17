<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Chat</title>

    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; }
        header { background:#003366; color:#fff; padding:16px; }

        .wrap { max-width: 1000px; margin: 18px auto; padding: 0 16px; }

        .card {
            background:#fff;
            border-radius:12px;
            padding:18px;
            box-shadow:0 6px 18px rgba(0,0,0,0.08);
            margin-bottom:16px;
        }

        .report-grid{
            display:flex;
            gap: 18px;
            align-items:flex-start;
        }

        .report-left{
            flex: 1;
            min-width: 0;
        }

        .report-right{
            width: 360px;
            flex-shrink: 0;
        }

        .meta-badges{
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-bottom:12px;
        }

        .badge{
            background:#f3f4f6;
            color:#111827;
            padding:8px 10px;
            border-radius:10px;
            font-size: 13px;
        }

        .badge.status{
            background:#e0f2fe;
            color:#075985;
            font-weight:700;
        }

        .section-title{
            font-size: 14px;
            color:#374151;
            margin: 8px 0 6px;
            font-weight:700;
        }

        .desc{
            background:#fafafa;
            border:1px solid #eee;
            border-radius:10px;
            padding:12px;
            line-height:1.45;
            color:#111827;
        }

        .evidence-img{
            width:100%;
            height: 260px;
            object-fit: cover;
            border-radius:12px;
            margin:0;
            border: 1px solid #eee;
        }

        .no-evidence{
            width:100%;
            height:260px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f3f4f6;
            border-radius:12px;
            color:#6b7280;
            border: 1px dashed #d1d5db;
        }

        /* Chat box */
        .chat{
            display:flex;
            flex-direction:column;
            gap:10px;
            height: 520px;
            overflow-y:auto;
            overflow-x:hidden;
            padding:12px;
            background:#fafafa;
            border:1px solid #ddd;
            border-radius:12px;
        }

        .msg { padding:10px 12px; border-radius:10px; max-width: 75%; }
        .msg.citizen { background:#dbeafe; align-self:flex-end; }
        .msg.system { background:#eee; align-self:center; }
        .msg.dispatcher { background:#dcfce7; align-self:flex-start; }

        .row{
            display:flex;
            gap:10px;
            margin-top:12px;
            position: sticky;
            bottom: 0;
            background: #fff;
            padding-top: 10px;
        }

        input{
            flex:1;
            padding:12px;
            border-radius:10px;
            border:1px solid #d1d5db;
        }

        button {
            padding:10px 16px;
            border:0;
            border-radius:10px;
            background:#ff6600;
            color:#fff;
            cursor:pointer;
        }

        button:disabled { background:#999; cursor:not-allowed; }

        /* Responsive */
        @media (max-width: 900px){
            .report-grid{ flex-direction: column; }
            .report-right{ width: 100%; }
            .evidence-img, .no-evidence{ height: 220px; }
            .chat{ height: 420px; }
        }
    </style>
</head>

<body>
<header>
    <div><b>Anonymous Report</b></div>
</header>

<div class="wrap">

    <!-- Report info card -->
    <div class="card">
        <div class="report-grid">
            <div class="report-left">
                <div class="meta-badges">
                    <div class="badge"><b>Location:</b> {{ $report->location ?? '—' }}</div>
                    <div class="badge"><b>Created:</b> {{ $report->created_at }}</div>
                    <div class="badge status"><b>Status:</b> <span id="statusText">{{ $report->status }}</span></div>
                </div>

                <div class="section-title">Description</div>
                <div class="desc">{{ $report->description }}</div>
            </div>

            <div class="report-right">
                <div class="section-title">Evidence</div>
                @if($report->file_location)
                    <img class="evidence-img" src="{{ asset('storage/' . $report->file_location) }}" alt="Evidence">
                @else
                    <div class="no-evidence">No image uploaded</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chat card -->
    <div class="card">
        <h3 style="margin-top:0;">Chat</h3>

        <div id="chatBox" class="chat"></div>

        <div class="row">
            <input id="chatInput" type="text" placeholder="Type a message..." />
            <button id="sendBtn" onclick="sendMessage()">Send</button>
        </div>

        <div id="closedHint" style="margin-top:10px; color:#a00; display:none;">
            This report has been closed. Chat is disabled.
        </div>
    </div>
</div>

<script>
    const reportId = {{ $report->id }};
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function render(messages) {
        const box = document.getElementById('chatBox');
        const nearBottom = (box.scrollHeight - box.scrollTop - box.clientHeight) < 80;

        box.innerHTML = '';
        messages.forEach(m => {
            const div = document.createElement('div');
            div.className = 'msg ' + (m.sender || 'system');
            div.textContent = m.text || '';
            box.appendChild(div);
        });

        if (nearBottom) box.scrollTop = box.scrollHeight;
    }

    async function poll() {
        const res = await fetch(`/report/${reportId}/poll`);
        if (!res.ok) return;
        const data = await res.json();

        document.getElementById('statusText').textContent = data.status;
        render(data.messages || []);

        const closed = (data.status === 'accepted' || data.status === 'denied');
        document.getElementById('sendBtn').disabled = closed;
        document.getElementById('chatInput').disabled = closed;
        document.getElementById('closedHint').style.display = closed ? 'block' : 'none';
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        if (!text) return;

        const res = await fetch(`/report/${reportId}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ text })
        });

        if (res.ok) {
            input.value = '';
            await poll();
            const box = document.getElementById('chatBox');
            box.scrollTop = box.scrollHeight;
        }
    }

    poll();
    setInterval(poll, 2000);
        document.getElementById('chatInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { // provjerava Enter
            e.preventDefault();   // sprječava novi red u inputu
            sendMessage();        // šalje poruku
        }
    });
</script>
</body>
</html>
