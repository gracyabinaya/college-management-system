@extends('layouts.app')

@section('title', 'Add Student - CollegeOS')

@section('content')
    <style>
        .card { border: 1px solid var(--border); border-radius: var(--radius-lg); background: white; padding: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 8px; text-transform: uppercase; letter-spacing: .3px; }
        .form-input, .form-select { width: 100%; padding: 11px 14px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; color: var(--ink); }
        .form-input:focus, .form-select:focus { outline: none; border-color: var(--amber); box-shadow: 0 0 0 3px rgba(200,133,10,.08); }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .photo-upload { border: 2px dashed var(--border2); border-radius: var(--radius-lg); padding: 24px; text-align: center; }
        .photo-preview { width: 120px; height: 150px; border-radius: var(--radius); object-fit: cover; margin: 0 auto 12px; border: 1px solid var(--border); }
        .btn-submit { background: var(--amber); color: var(--ink); padding: 12px 24px; border: none; border-radius: var(--radius); font-weight: 600; font-size: 13.5px; cursor: pointer; transition: all .15s; }
        .btn-submit:hover { background: #d99410; }
        .btn-back { background: transparent; color: var(--ink3); padding: 12px 24px; border: 1px solid var(--border); border-radius: var(--radius); font-weight: 600; font-size: 13.5px; cursor: pointer; transition: all .15s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-back:hover { background: var(--surface2); }
        .form-row { display: flex; gap: 20px; }
        .error-message { color: var(--red); font-size: 12px; margin-top: 4px; }
        .alert { padding: 14px 16px; border-radius: var(--radius); margin-bottom: 20px; }
        .alert-error { background: var(--red-bg); color: var(--red); }
        .alert-error strong { font-weight: 600; }
    </style>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="margin: 0;">Register Student</h1>
            <p class="topbar-sub">Add a new student to the system</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn-back">
            <i class="ti ti-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div>
                    <div class="form-group">
                        <label class="form-label">Student Name *</label>
                        <input type="text" name="student_name" class="form-input" value="{{ old('student_name') }}" required>
                        @error('student_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age *</label>
                        <input type="number" name="age" class="form-input" min="1" max="100" value="{{ old('age') }}" required>
                        @error('age') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date of Birth *</label>
                        <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gender *</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Father's Name *</label>
                        <input type="text" name="father_name" class="form-input" value="{{ old('father_name') }}" required>
                        @error('father_name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number (10 digits) *</label>
                        <input type="tel" name="contact_number" class="form-input" value="{{ old('contact_number') }}" required>
                        @error('contact_number') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alternate Contact</label>
                        <input type="tel" name="alternate_contact_number" class="form-input" value="{{ old('alternate_contact_number') }}">
                        @error('alternate_contact_number') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Aadhaar Number (12 digits) *</label>
                        <input type="text" name="aadhaar_number" class="form-input" maxlength="12" value="{{ old('aadhaar_number') }}" required>
                        @error('aadhaar_number') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Class Studied *</label>
                        <input type="text" name="last_class_studied" class="form-input" value="{{ old('last_class_studied') }}" required>
                        @error('last_class_studied') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Previous Year Grade *</label>
                        <input type="text" name="previous_year_grade" class="form-input" value="{{ old('previous_year_grade') }}" required>
                        @error('previous_year_grade') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last School Studied *</label>
                        <input type="text" name="last_school_studied" class="form-input" value="{{ old('last_school_studied') }}" required>
                        @error('last_school_studied') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label class="form-label">Passport Size Photo *</label>
                        <div class="photo-upload">
                            <img id="photoPreview" src="https://via.placeholder.com/120x150?text=Passport+Photo" class="photo-preview">
                            <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png" id="photoInput" onchange="previewPhoto(event)" required>
                            <p style="font-size: 12px; color: var(--ink3); margin-top: 10px;">JPG, JPEG, or PNG<br>120×150px</p>
                            @error('photo') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                <a href="{{ route('students.index') }}" class="btn-back">Cancel</a>
                <button type="submit" class="btn-submit"><i class="ti ti-check"></i> Save Student</button>
            </div>
        </form>
    </div>

    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
