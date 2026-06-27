<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CollegeOS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        :root {
            --ink: #1a1a2e;
            --ink2: #2d2d4e;
            --ink3: #555570;
            --amber: #c8850a;
            --amber-light: #fdf3df;
            --amber-mid: #f5c842;
            --surface: #f7f5f2;
            --surface2: #efecea;
            --surface3: #e5e1db;
            --green: #1a7a4a;
            --green-bg: #eaf5ef;
            --red: #c0392b;
            --red-bg: #fdecea;
            --blue: #2563eb;
            --blue-bg: #eff6ff;
            --border: #ddd8d0;
            --border2: #ccc5b8;
            --radius: 10px;
            --radius-lg: 16px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--surface);
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: var(--ink);
            margin: 0;
            padding: 0;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 235px;
            height: 100vh;
            background: var(--ink);
            padding: 24px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
            z-index: 50;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            margin-bottom: 22px;
        }

        .logo-icon {
            width: 38px; height: 38px;
            background: var(--amber);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--ink);
        }

        .logo-text {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .3px;
        }

        .logo-sub {
            font-size: 11px;
            color: rgba(255,255,255,.38);
            margin-top: 1px;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,.28);
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 16px 10px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,.55);
            font-size: 13.5px;
            text-decoration: none;
            transition: all .18s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .nav-link i {
            font-size: 17px;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .nav-link.active {
            background: var(--amber);
            color: var(--ink);
            font-weight: 600;
        }

        .nav-link.active i {
            color: var(--ink);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .user-name {
            font-size: 12.5px;
            color: rgba(255,255,255,.8);
            font-weight: 500;
        }

        .user-role {
            font-size: 11px;
            color: rgba(255,255,255,.35);
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: 235px;
            padding: 28px 32px;
            min-height: 100vh;
            background: var(--surface);
        }

        /* ── Topbar ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .topbar h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--ink);
            margin: 0;
        }

        .topbar-sub {
            font-size: 13px;
            color: var(--ink3);
            margin-top: 2px;
        }

        /* ── Buttons ── */
        .btn-amber {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--amber);
            color: var(--ink);
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-amber:hover {
            background: #d99410;
            transform: translateY(-1px);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: 8px;
            color: var(--ink2);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-ghost:hover {
            background: var(--surface2);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .sidebar {
                width: 60px;
                padding: 16px 8px;
            }
            .logo-text, .logo-sub, .nav-section, .user-name, .user-role {
                display: none;
            }
            .main-content {
                margin-left: 60px;
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ═══ Sidebar ═══ -->
 <!--sidebar updated-->
<div class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="ti ti-school"></i></div>
        <div>
            <div class="logo-text">CollegeOS</div>
            <div class="logo-sub">Management System</div>
        </div>
    </div>

    <div class="nav-section">Core</div>
    
    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
        <i class="ti ti-layout-dashboard"></i> Dashboard
    </a>
    <a href="{{ url('/students') }}" class="nav-link {{ Request::is('students*') ? 'active' : '' }}">
        <i class="ti ti-users"></i> Students
    </a>
    <a href="{{ url('/courses') }}" class="nav-link {{ Request::is('courses*') ? 'active' : '' }}">
        <i class="ti ti-book"></i> Courses
    </a>
    <a href="{{ url('/enrollments') }}" class="nav-link {{ Request::is('enrollments*') ? 'active' : '' }}">
    <i class="ti ti-user-plus"></i> Enrollments
</a>
    
    <a href="{{ url('/faculty') }}" class="nav-link {{ Request::is('faculty*') ? 'active' : '' }}">
        <i class="ti ti-user-check"></i> Faculty
    </a>
    <a href="{{ url('/timetable') }}" class="nav-link {{ Request::is('timetable*') ? 'active' : '' }}">
        <i class="ti ti-calendar-event"></i> Timetable
    </a>

    <div class="nav-section">Administration</div>
    
    <a href="{{ url('/admissions') }}" class="nav-link {{ Request::is('admissions*') ? 'active' : '' }}">
        <i class="ti ti-clipboard-list"></i> Admissions
    </a>
    <a href="{{ url('/departments') }}" class="nav-link {{ Request::is('departments*') ? 'active' : '' }}">
        <i class="ti ti-building"></i> Departments
    </a>
    <a href="{{ url('/fee-masters') }}" class="nav-link {{ Request::is('fee-masters*') ? 'active' : '' }}">
    <i class="ti ti-cash"></i> Fee Master
</a>

<a href="{{ url('/fee-payments') }}" class="nav-link {{ Request::is('fee-payments*') ? 'active' : '' }}">
    <i class="ti ti-credit-card"></i> Fee Payments
</a>

<a href="{{ url('/fee-receipts') }}" class="nav-link {{ Request::is('fee-receipts*') ? 'active' : '' }}">
    <i class="ti ti-receipt"></i> Fee Receipts
</a>

<a href="{{ url('/fees/pending') }}" class="nav-link {{ Request::is('fees/pending*') ? 'active' : '' }}">
    <i class="ti ti-alert-circle"></i> Pending Fees
</a>

<a href="{{ url('/fees/paid') }}" class="nav-link {{ Request::is('fees/paid*') ? 'active' : '' }}">
    <i class="ti ti-check"></i> Paid Fees
</a>


    <a href="{{ url('/reports') }}" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
        <i class="ti ti-report"></i> Reports
    </a>
    <a href="{{ url('/settings') }}" class="nav-link {{ Request::is('settings*') ? 'active' : '' }}">
        <i class="ti ti-settings"></i> Settings
    </a>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-avatar">A</div>
            <div>
                <div class="user-name">Admin User</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="#" style="margin:0">
            @csrf
            <button type="submit" class="nav-link">
                <i class="ti ti-logout"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- ═══ Main Content ═══ -->
<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>