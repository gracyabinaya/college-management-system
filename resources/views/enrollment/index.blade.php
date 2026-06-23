@extends('layouts.app')

@section('title', 'Enrollment List - CollegeOS')

@section('content')
<div class="container-fluid">
    <!-- Top Bar -->
    <div class="topbar">
        <div>
            <h1>Enrollment List</h1>
            <div class="topbar-sub">Student Course Mapping</div>
        </div>
        <a href="{{ route('enrollments.create') }}" class="btn-amber">
            <i class="ti ti-user-plus"></i> New Enrollment
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            <i class="ti ti-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">👥 Total Enrollments</div>
            <div class="stat-value">{{ $enrollments->count() }}</div>
            <div class="stat-pill pill-amber"><i class="ti ti-user-check"></i> Active Enrollments</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">📚 Total Courses</div>
            <div class="stat-value">{{ $enrollments->pluck('course_id')->unique()->count() }}</div>
            <div class="stat-pill pill-green"><i class="ti ti-book"></i> Courses</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">📊 Last Updated</div>
            <div class="stat-value" style="font-size: 24px;">{{ now()->format('M d, Y') }}</div>
            <div class="stat-pill pill-blue"><i class="ti ti-calendar"></i> {{ now()->format('h:i A') }}</div>
        </div>
    </div>

    <!-- Enrollment Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="table-title">
                <i class="ti ti-list"></i> Enrollment List
            </div>
            <div class="table-actions">
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search enrollments..." onkeyup="searchTable()">
                </div>
                <button class="btn-ghost" onclick="window.print()">
                    <i class="ti ti-printer"></i> Print
                </button>
                <a href="{{ route('enrollments.create') }}" class="btn-amber" style="padding: 7px 14px; font-size: 12px;">
                    <i class="ti ti-user-plus"></i> Add
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Course</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $index => $enroll)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="student-name">{{ $enroll->student->student_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="age-badge">{{ $enroll->student->age ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="gender-badge {{ strtolower($enroll->student->gender ?? '') }}">
                                    {{ $enroll->student->gender ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($enroll->student->date_of_birth)
                                    {{ \Carbon\Carbon::parse($enroll->student->date_of_birth)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="course-badge">{{ $enroll->course->course_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="department-badge">{{ $enroll->course->department->department_name ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('enrollments.destroy', $enroll->id) }}"
                                          method="POST"
                                          style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="ti ti-user-plus"></i>
                                    <div class="empty-title">No Enrollments Found</div>
                                    <div class="empty-sub">Click "New Enrollment" to enroll a student in a course</div>
                                    <a href="{{ route('enrollments.create') }}" class="btn-amber" style="margin-top: 15px; display: inline-flex;">
                                        <i class="ti ti-user-plus"></i> Create New Enrollment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if you have pagination) -->
        @if(method_exists($enrollments, 'links'))
            <div class="pagination-wrapper">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Styles -->
<style>
    /* Alert Success */
    .alert-success {
        background: var(--green-bg, #eaf5ef);
        color: var(--green, #1a7a4a);
        border: 1px solid #b8d9c9;
        border-radius: var(--radius, 10px);
        padding: 14px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -14px;
        top: -14px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        opacity: .08;
    }

    .stat-card:nth-child(1)::after { background: var(--amber, #c8850a); }
    .stat-card:nth-child(2)::after { background: var(--green, #1a7a4a); }
    .stat-card:nth-child(3)::after { background: var(--blue, #2563eb); }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--ink3, #555570);
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        line-height: 1;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        margin-top: 8px;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .pill-amber {
        background: var(--amber-light, #fdf3df);
        color: #7a5200;
    }
    .pill-green {
        background: var(--green-bg, #eaf5ef);
        color: var(--green, #1a7a4a);
    }
    .pill-blue {
        background: var(--blue-bg, #eff6ff);
        color: var(--blue, #2563eb);
    }

    /* Table Container */
    .table-container {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        overflow: hidden;
        margin-top: 20px;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border, #ddd8d0);
        background: var(--surface, #f7f5f2);
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        min-width: 180px;
    }

    .search-wrap input {
        width: 100%;
        padding: 7px 12px 7px 34px;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: var(--radius, 10px);
        font-size: 13px;
        background: #fff;
        color: var(--ink, #1a1a2e);
        outline: none;
        transition: border-color .2s;
    }

    .search-wrap input:focus {
        border-color: var(--amber, #c8850a);
        box-shadow: 0 0 0 3px rgba(200,133,10,.12);
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink3, #555570);
        font-size: 14px;
        pointer-events: none;
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        background: transparent;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: 8px;
        color: var(--ink2, #2d2d4e);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .btn-ghost:hover {
        background: var(--surface2, #efecea);
        color: var(--ink, #1a1a2e);
        text-decoration: none;
    }

    .btn-amber {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        background: var(--amber, #c8850a);
        color: var(--ink, #1a1a2e);
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .btn-amber:hover {
        background: #d99410;
        transform: translateY(-1px);
        color: var(--ink, #1a1a2e);
        text-decoration: none;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        padding: 0;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed;
    }

    .data-table thead {
        background: var(--ink, #1a1a2e);
    }

    .data-table th {
        color: #fff;
        padding: 12px 14px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* Equal column widths */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
        width: 5%;
        text-align: center;
    }

    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        width: 18%;
    }

    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
        width: 7%;
        text-align: center;
    }

    .data-table th:nth-child(4),
    .data-table td:nth-child(4) {
        width: 9%;
        text-align: center;
    }

    .data-table th:nth-child(5),
    .data-table td:nth-child(5) {
        width: 13%;
    }

    .data-table th:nth-child(6),
    .data-table td:nth-child(6) {
        width: 20%;
    }

    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
        width: 18%;
    }

    .data-table th:nth-child(8),
    .data-table td:nth-child(8) {
        width: 10%;
        text-align: center;
    }

    .data-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border, #ddd8d0);
        color: var(--ink2, #2d2d4e);
        vertical-align: middle;
    }

    .data-table td:nth-child(1),
    .data-table td:nth-child(3),
    .data-table td:nth-child(4),
    .data-table td:nth-child(8) {
        text-align: center;
    }

    .data-table tbody tr:hover {
        background: var(--surface, #f7f5f2);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Student Name */
    .student-name {
        font-weight: 500;
        color: var(--ink, #1a1a2e);
        font-size: 13px;
    }

    /* Age Badge */
    .age-badge {
        background: var(--surface2, #efecea);
        color: var(--ink2, #2d2d4e);
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    /* Gender Badge */
    .gender-badge {
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-transform: capitalize;
    }

    .gender-badge.male {
        background: #dbeafe;
        color: #1e40af;
    }

    .gender-badge.female {
        background: #fce4ec;
        color: #c62828;
    }

    .gender-badge.other {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    /* Course Badge */
    .course-badge {
        background: var(--amber-light, #fdf3df);
        color: #7a5200;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    /* Department Badge */
    .department-badge {
        background: var(--blue-bg, #eff6ff);
        color: var(--blue, #2563eb);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 5px 10px;
        background: var(--red-bg, #fdecea);
        color: var(--red, #c0392b);
        border: 1px solid #f5c6c2;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }

    .btn-delete:hover {
        background: #fbd8d5;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: var(--ink3, #555570);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--border2, #ccc5b8);
        margin-bottom: 16px;
        display: block;
    }

    .empty-state .empty-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--ink2, #2d2d4e);
        margin-bottom: 8px;
    }

    .empty-state .empty-sub {
        font-size: 14px;
        color: var(--ink3, #555570);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 14px 20px;
        border-top: 1px solid var(--border, #ddd8d0);
        background: var(--surface, #f7f5f2);
    }

    /* Top Bar */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .topbar h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        margin: 0;
    }

    .topbar-sub {
        font-size: 13px;
        color: var(--ink3, #555570);
        margin-top: 2px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .data-table th:nth-child(1),
        .data-table td:nth-child(1),
        .data-table th:nth-child(2),
        .data-table td:nth-child(2),
        .data-table th:nth-child(3),
        .data-table td:nth-child(3),
        .data-table th:nth-child(4),
        .data-table td:nth-child(4),
        .data-table th:nth-child(5),
        .data-table td:nth-child(5),
        .data-table th:nth-child(6),
        .data-table td:nth-child(6),
        .data-table th:nth-child(7),
        .data-table td:nth-child(7),
        .data-table th:nth-child(8),
        .data-table td:nth-child(8) {
            width: auto;
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrap {
            min-width: unset;
        }

        .data-table th,
        .data-table td {
            padding: 8px 10px;
            font-size: 12px;
        }
    }

    @media (max-width: 640px) {
        .topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .data-table {
            font-size: 11px;
        }

        .data-table th,
        .data-table td {
            padding: 6px 8px;
        }

        .gender-badge,
        .age-badge,
        .course-badge,
        .department-badge {
            font-size: 10px;
            padding: 2px 8px;
        }

        .btn-delete {
            font-size: 10px;
            padding: 4px 8px;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Search Script -->
<script>
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('.data-table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
</script>

@endsection