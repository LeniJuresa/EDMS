<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-container">

    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <p><em>{{ Auth::user()->name }}</em> (<strong>{{ Auth::user()->id_number }}</strong>)</p>
        <ul>
            <li class="tab-link active" data-tab="dashboard">Dashboard</li>
            <li class="tab-link" data-tab="reports">Reports</li>
            <li class="tab-link" data-tab="staff">Staff</li>
            <li onclick="logout()" class="logout-item">Logout</li>
        </ul>
    </aside>

    <main class="main-content">

        <!-- Dashboard Tab -->
        <div class="tab-content active" id="dashboard">
            <h1>Dashboard</h1>
            <p>You are logged in as <strong>{{ Auth::user()->name }}</strong> (ID: <strong>{{ Auth::user()->id_number }}</strong>)</p>
            <div class="summary-cards">
                <div class="card">Total reports: {{ $totalReports }}</div>
                <div class="card">Pending reports: {{ $pendingReports }}</div>
                <div class="card">Claimed reports: {{ $claimedReports }}</div>
                <div class="card">Accepted reports: {{ $acceptedReports }}</div>
                <div class="card">Denied reports: {{ $deniedReports }}</div>
            </div>
        </div>

        <!-- Reports Tab -->
        <div class="tab-content" id="reports">
            <h1>Reports</h1>
            <p>Only accepted/denied reports will appear here. </p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Dispatcher</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody>
                @forelse($finalReports as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>Report </td>
                        <td>{{ ucfirst($r->status) }}</td>
                        <td>{{ $r->dispatcher?->name ?? '—' }} </td>
                        <td>{{ $r->created_at }}</td>
                        <td>{{ $r->closed_at ?? $r->updated_at }}</td>
                        <td>
                            <a href="{{ url('/admin/reports/' . $r->id) }}">
                                <button>View</button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No accepted/denied reports yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Staff Tab -->
        <div class="tab-content" id="staff">
            <h1>Staff Management</h1>

            <h2>Dispatcher List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Accepted</th>
                        <th>Total Denied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispatchers as $dispatcher)
                        <tr>
                            <td>{{ $dispatcher->id_number }}</td>
                            <td>{{ $dispatcher->name }}</td>
                            <td>{{ $dispatcher->email }}</td>
                            <td>{{ $dispatcher->accepted_reports_count ?? 0 }}</td>
                            <td>{{ $dispatcher->denied_reports_count ?? 0 }}</td>
                            <td>
                                @if(!$dispatcher->is_admin)
                                    <button onclick="editDispatcher({{ $dispatcher->id }})">-</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h2 style="margin-top:40px;">Admin List</h2>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->id_number }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>Admin</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2>Create New Staff Account</h2>
            <form action="/admin" method="POST">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input name="name" type="text" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="dispatcher" selected>Dispatcher</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <button type="submit">Create Account</button>
            </form>

            <!-- Validation errors -->
            @if($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success message and download button -->
            @if(session('success') && session('new_user_id'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
                <a href="{{ url('/admin/download/' . session('new_user_id')) }}" class="download-button">
                    Download Staff Account File
                </a>
            @endif
        </div>

    </main>

</div>

<script>
    // Tab switching
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });

    function logout() {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "/logout";
        const token = document.createElement("input");
        token.type = "hidden";
        token.name = "_token";
        token.value = "{{ csrf_token() }}";
        form.appendChild(token);
        document.body.appendChild(form);
        form.submit();
    }


</script>

</body>
</html>
