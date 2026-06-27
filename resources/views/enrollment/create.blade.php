@extends('layouts.app')

@section('title', 'Enroll Student - CollegeOS')

@section('content')
<div class="container-fluid">
    <!-- Top Bar -->
    <div class="topbar">
        <div>
            <h1>Enroll Student</h1>
            <div class="topbar-sub">Enroll a student in a course</div>
        </div>
        <a href="{{ route('enrollments.index') }}" class="btn-ghost">
            <i class="ti ti-arrow-left"></i> Back to Enrollments
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf

            <div class="form-grid">
                <!-- Student Selection -->
                <div class="field">
                    <label for="studentSelect">Student <span class="required">*</span></label>
                    <select id="studentSelect" name="student_id" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option 
    value="{{ $student->id }}"
    data-age="{{ $student->age }}"
    data-gender="{{ $student->gender }}"
    data-dob="{{ \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') }}"
    {{ old('student_id') == $student->id ? 'selected' : '' }}>
    {{ $student->student_name }}
</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Course Selection -->
                <div class="field">
                    <label for="course_id">Course <span class="required">*</span></label>
                    <select name="course_id" id="course_id" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }} ({{ $course->course_code }}) - 
                                {{ $course->department->department_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Auto-filled fields section -->
                <div class="field full-width">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; background: var(--surface, #f7f5f2); padding: 16px 20px; border-radius: 8px; border: 1px solid var(--border2, #ccc5b8);">
                        <div>
                            <label style="font-size: 11px; font-weight: 600; color: var(--ink3, #555570); text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 4px;">Age</label>
                            <input type="text" id="age" readonly style="background: #fff; border: none; padding: 0; font-size: 14px; font-weight: 600; color: var(--ink, #1a1a2e); width: 100%; outline: none;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600; color: var(--ink3, #555570); text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 4px;">Gender</label>
                            <input type="text" id="gender" readonly style="background: #fff; border: none; padding: 0; font-size: 14px; font-weight: 600; color: var(--ink, #1a1a2e); width: 100%; outline: none;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600; color: var(--ink3, #555570); text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 4px;">Date of Birth</label>
                            <input type="text" id="dob" readonly style="background: #fff; border: none; padding: 0; font-size: 14px; font-weight: 600; color: var(--ink, #1a1a2e); width: 100%; outline: none;">
                        </div>
                    </div>
                    <div style="font-size: 11px; color: var(--ink3, #555570); margin-top: 6px;">
                        <i class="ti ti-info-circle"></i> Student details will auto-fill when you select a student
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-amber">
                    <i class="ti ti-user-plus"></i> Enroll Student
                </button>
                <a href="{{ route('enrollments.index') }}" class="btn-ghost">
                    <i class="ti ti-x"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
    /* Form Card */
    .form-card {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        padding: 32px 36px;
        margin-top: 20px;
        max-width: 800px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 30px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field.full-width {
        grid-column: span 2;
    }

    .field label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink2, #2d2d4e);
        letter-spacing: .3px;
    }

    .field label .required {
        color: var(--red, #c0392b);
        font-weight: 700;
    }

    .field select {
        padding: 10px 14px;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: 8px;
        font-size: 14px;
        background: var(--surface, #f7f5f2);
        color: var(--ink, #1a1a2e);
        outline: none;
        transition: all .2s;
        font-family: inherit;
        width: 100%;
        appearance: auto;
    }

    .field select:focus {
        border-color: var(--amber, #c8850a);
        box-shadow: 0 0 0 3px rgba(200,133,10,.12);
        background: #fff;
    }

    .field select.error {
        border-color: var(--red, #c0392b);
    }

    .field .error {
        font-size: 12px;
        color: var(--red, #c0392b);
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid var(--border, #ddd8d0);
    }

    .btn-amber {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 24px;
        background: var(--amber, #c8850a);
        color: var(--ink, #1a1a2e);
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .btn-amber:hover {
        background: #d99410;
        transform: translateY(-1px);
        color: var(--ink, #1a1a2e);
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        background: transparent;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: 8px;
        color: var(--ink2, #2d2d4e);
        font-size: 14px;
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
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
            max-width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .field.full-width {
            grid-column: span 1;
        }

        .field.full-width [style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn-amber,
        .form-actions .btn-ghost {
            justify-content: center;
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .form-card {
            padding: 16px;
        }

        .topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .topbar h1 {
            font-size: 18px;
        }

        .field select {
            font-size: 13px;
            padding: 8px 12px;
        }
    }
</style>

<!-- JavaScript for auto-fill functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSelect = document.getElementById('studentSelect');
        const ageInput = document.getElementById('age');
        const genderInput = document.getElementById('gender');
        const dobInput = document.getElementById('dob');

        // Initial check - if a student is already selected (e.g., after form validation error)
        if (studentSelect.value) {
            const selected = studentSelect.options[studentSelect.selectedIndex];
            ageInput.value = selected.getAttribute('data-age') || '';
            genderInput.value = selected.getAttribute('data-gender') || '';
            dobInput.value = selected.getAttribute('data-dob') || '';
        }

        // Event listener for change
        studentSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            
            if (selected.value) {
                ageInput.value = selected.getAttribute('data-age') || '';
                genderInput.value = selected.getAttribute('data-gender') || '';
                dobInput.value = selected.getAttribute('data-dob') || '';
            } else {
                ageInput.value = '';
                genderInput.value = '';
                dobInput.value = '';
            }
        });
    });
</script>

@endsection