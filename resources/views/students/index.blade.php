@extends('layouts.app')

@section('title', 'Students - CollegeOS')

@section('content')
    <style>
        .card { border: 1px solid var(--border); border-radius: var(--radius-lg); background: white; padding: 20px; margin-bottom: 20px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--ink); margin: 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: var(--surface2); padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: var(--ink3); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .table td { padding: 14px 12px; border-bottom: 1px solid var(--border2); font-size: 13.5px; }
        .table tr:hover { background: var(--surface2); }
        .table img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; }
        .btn-group { display: flex; gap: 6px; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; border: none; cursor: pointer; transition: all .15s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .btn-view { background: var(--blue-bg); color: var(--blue); }
        .btn-view:hover { background: #dbeafe; }
        .btn-edit { background: var(--amber-light); color: var(--amber); }
        .btn-edit:hover { background: #fce4ad; }
        .btn-delete { background: var(--red-bg); color: var(--red); }
        .btn-delete:hover { background: #fadbd6; }
        .btn-print { background: var(--green-bg); color: var(--green); }
        .btn-print:hover { background: #dfeae5; }
        .search-box { display: flex; gap: 8px; }
        .search-box input { padding: 9px 14px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; flex: 1; max-width: 300px; }
        .search-box button { padding: 9px 18px; background: var(--amber); color: var(--ink); border: none; border-radius: var(--radius); font-weight: 600; cursor: pointer; transition: all .15s; }
        .search-box button:hover { background: #d99410; }
        .empty-state { text-align: center; padding: 40px 20px; color: var(--ink3); }
        .empty-state i { font-size: 48px; opacity: .5; margin-bottom: 10px; }
        .stat-card { background: white; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; margin-bottom: 20px; }
        .stat-label { font-size: 11px; font-weight: 600; color: var(--ink3); text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--ink); margin-top: 10px; }
    </style>

    <div class="topbar">
        <div>
            <h1>Students</h1>
            <p class="topbar-sub">Manage and organize student records</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn-amber">
            <i class="ti ti-plus"></i> Add Student
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $students->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Current Page</div>
            <div class="stat-value">{{ $students->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Last Updated</div>
            <div class="stat-value">{{ now()->format('M d') }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Registered Students</h2>
            <form action="{{ route('students.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search by name, ID, or contact..." value="{{ request('search') }}">
                <button type="submit">Search</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Last Class</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->student_id }}</strong></td>
                            <td>
                                <img src="{{ $student->photo ? asset('uploads/students/' . $student->photo) : 'https://via.placeholder.com/40?text=Photo' }}" alt="Photo">
                            </td>
                            <td>{{ $student->student_name }}</td>
                            <td>{{ $student->father_name }}</td>
                            <td>{{ $student->contact_number }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->last_class_studied }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('students.show', $student) }}" class="btn-sm btn-view">
                                        <i class="ti ti-eye"></i> View
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn-sm btn-edit">
                                        <i class="ti ti-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this student record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete" style="border:none;">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>
                                    <a href="{{ route('students.print', $student) }}" target="_blank" class="btn-sm btn-print">
                                        <i class="ti ti-printer"></i> Print
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="ti ti-inbox"></i>
                                    <p><strong>No students found</strong></p>
                                    <p style="font-size:12px;">Add a new student record to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $students->withQueryString()->links() }}
        </div>
    </div>
@endsection
