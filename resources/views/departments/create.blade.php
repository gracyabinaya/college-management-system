@extends('layouts.app')

@section('title', 'Add Department - CollegeOS')

@section('content')
<div class="container-fluid">
    <!-- Top Bar -->
    <div class="topbar">
        <div>
            <h1>Add Department</h1>
            <div class="topbar-sub">Create a new academic department</div>
        </div>
        <a href="{{ route('departments.index') }}" class="btn-ghost">
            <i class="ti ti-arrow-left"></i> Back to Departments
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div class="field">
                <label for="department_name">Department Name <span class="required">*</span></label>
                <input type="text" 
                       id="department_name" 
                       name="department_name" 
                       placeholder="e.g. Computer Science" 
                       value="{{ old('department_name') }}"
                       required>
                @error('department_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-amber">
                    <i class="ti ti-check"></i> Create Department
                </button>
                <a href="{{ route('departments.index') }}" class="btn-ghost">
                    <i class="ti ti-x"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
    .form-card {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        padding: 32px 36px;
        margin-top: 20px;
        max-width: 500px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
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

    .field input {
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
    }

    .field input:focus {
        border-color: var(--amber, #c8850a);
        box-shadow: 0 0 0 3px rgba(200,133,10,.12);
        background: #fff;
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

    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
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

    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
            max-width: 100%;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn-amber,
        .form-actions .btn-ghost {
            justify-content: center;
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
    }
</style>
@endsection