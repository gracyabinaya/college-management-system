@extends('layouts.app')

@section('title', 'Student Details - CollegeOS')

@section('content')
    <style>
        .card { border: 1px solid var(--border); border-radius: var(--radius-lg); background: white; padding: 24px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }
        .field-row { display: grid; grid-template-columns: 140px 1fr; gap: 18px; padding: 14px 0; border-bottom: 1px solid var(--border2); }
        .field-label { font-size: 13px; font-weight: 700; color: var(--ink3); text-transform: uppercase; letter-spacing: .4px; }
        .field-value { font-size: 15px; color: var(--ink); }
        .profile-card { border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; text-align: center; }
        .profile-photo { width: 150px; height: 180px; border-radius: 14px; object-fit: cover; border: 1px solid var(--border); }
        .profile-name { margin-top: 18px; font-size: 18px; font-weight: 700; color: var(--ink); }
        .profile-meta { color: var(--ink3); font-size: 13px; margin-top: 6px; }
        .action-buttons { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 18px; justify-content: center; }
        .btn-sm { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; text-decoration: none; }
        .btn-edit { background: var(--amber-light); color: var(--amber); }
        .btn-print { background: var(--green-bg); color: var(--green); }
        .btn-back { background: transparent; border: 1px solid var(--border); color: var(--ink2); }
        .btn-sm:hover { transform: translateY(-1px); }
    </style>

    <div class="topbar">
        <div>
            <h1>Student Details</h1>
            <p class="topbar-sub">Quick access to complete student information</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap: wrap;">
            <a href="{{ route('students.index') }}" class="btn-ghost btn-back">
                <i class="ti ti-arrow-left"></i> Back
            </a>
            <a href="{{ route('students.edit', $student) }}" class="btn-amber btn-edit">
                <i class="ti ti-pencil"></i> Edit
            </a>
            <a href="{{ route('students.print', $student) }}" target="_blank" class="btn-amber btn-print">
                <i class="ti ti-printer"></i> Print
            </a>
        </div>
    </div>

    <div class="card">
        <div class="detail-grid">
            <div>
                <div class="field-row">
                    <div class="field-label">Student ID</div>
                    <div class="field-value">{{ $student->student_id }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Name</div>
                    <div class="field-value">{{ $student->student_name }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Father's Name</div>
                    <div class="field-value">{{ $student->father_name }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Age</div>
                    <div class="field-value">{{ $student->age }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Gender</div>
                    <div class="field-value">{{ $student->gender }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Date of Birth</div>
                    <div class="field-value">{{ $student->date_of_birth->format('M d, Y') }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Contact</div>
                    <div class="field-value">{{ $student->contact_number }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Alternate Contact</div>
                    <div class="field-value">{{ $student->alternate_contact_number ?? '—' }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Aadhaar</div>
                    <div class="field-value">{{ $student->aadhaar_number }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Last Class</div>
                    <div class="field-value">{{ $student->last_class_studied }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Previous Grade</div>
                    <div class="field-value">{{ $student->previous_year_grade }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Last School</div>
                    <div class="field-value">{{ $student->last_school_studied }}</div>
                </div>
            </div>

            <div class="profile-card">
                <img src="{{ $student->photo ? asset('uploads/students/' . $student->photo) : 'https://via.placeholder.com/150x180?text=Photo' }}" alt="Student Photo" class="profile-photo">
                <div class="profile-name">{{ $student->student_name }}</div>
                <div class="profile-meta">{{ $student->student_id }}</div>
                <div class="action-buttons">
                    <a href="{{ route('students.edit', $student) }}" class="btn-sm btn-edit"><i class="ti ti-pencil"></i> Edit</a>
                    <a href="{{ route('students.print', $student) }}" target="_blank" class="btn-sm btn-print"><i class="ti ti-printer"></i> Print</a>
                </div>
            </div>
        </div>
    </div>
@endsection
