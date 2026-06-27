<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Student - {{ $student->student_name }}</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; color: #1d1d2e; background: #fff; margin: 0; padding: 32px; }
        .report { max-width: 820px; margin: 0 auto; border: 1px solid #e6e2db; padding: 32px; }
        .brand { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .brand-title { font-size: 22px; font-weight: 700; letter-spacing: 0.6px; color: #1a1a2e; }
        .brand-sub { color: #7f7f92; font-size: 13px; margin-top: 4px; }
        .badge { border-radius: 10px; padding: 8px 14px; background: #f9f4e7; color: #ab7b1f; font-size: 12px; font-weight: 700; }
        .section-title { font-size: 14px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px; }
        .field { padding: 14px 16px; background: #f8f5f2; border-radius: 14px; border: 1px solid #ede8df; }
        .field-label { font-size: 12px; color: #6b6b84; text-transform: uppercase; letter-spacing: .35px; margin-bottom: 8px; }
        .field-value { font-size: 15px; font-weight: 600; color: #24243a; }
        .photo-card { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px; border: 1px solid #ede8df; border-radius: 16px; }
        .photo-card img { width: 160px; height: 200px; object-fit: cover; border-radius: 14px; border: 1px solid #d4cfc4; }
        .photo-card small { display: block; margin-top: 12px; color: #7c7c90; font-size: 12px; }
        .footer { display: flex; justify-content: space-between; margin-top: 40px; gap: 24px; }
        .signature { text-align: center; width: 100%; }
        .signature-line { border-top: 1px solid #cfc9c1; margin-top: 46px; padding-top: 8px; color: #656575; font-size: 12px; }
        .print-note { margin-top: 30px; color: #6b6b84; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .report { border: none; padding: 0; }
            .brand { page-break-inside: avoid; }
            .footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="report">
        <div class="brand">
            <div>
                <div class="brand-title">CollegeOS Student Printout</div>
                <div class="brand-sub">Student record generated on {{ now()->format('F j, Y') }}</div>
            </div>
            <div class="badge">Student ID: {{ $student->student_id }}</div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Student Name</div>
                <div class="field-value">{{ $student->student_name }}</div>
            </div>
            <div class="field">
                <div class="field-label">Father's Name</div>
                <div class="field-value">{{ $student->father_name }}</div>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Age</div>
                <div class="field-value">{{ $student->age }}</div>
            </div>
            <div class="field">
                <div class="field-label">Gender</div>
                <div class="field-value">{{ $student->gender }}</div>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Date of Birth</div>
                <div class="field-value">{{ $student->date_of_birth->format('F d, Y') }}</div>
            </div>
            <div class="field">
                <div class="field-label">Contact Number</div>
                <div class="field-value">{{ $student->contact_number }}</div>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Alternate Contact</div>
                <div class="field-value">{{ $student->alternate_contact_number ?? 'N/A' }}</div>
            </div>
            <div class="field">
                <div class="field-label">Aadhaar Number</div>
                <div class="field-value">{{ $student->aadhaar_number }}</div>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Last Class Studied</div>
                <div class="field-value">{{ $student->last_class_studied }}</div>
            </div>
            <div class="field">
                <div class="field-label">Previous Year Grade</div>
                <div class="field-value">{{ $student->previous_year_grade }}</div>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <div class="field-label">Last School Studied</div>
                <div class="field-value">{{ $student->last_school_studied }}</div>
            </div>
            <div class="photo-card">
                <img src="{{ $student->photo ? asset('uploads/students/' . $student->photo) : 'https://via.placeholder.com/160x200?text=Photo' }}" alt="Student Photo">
                <small>Passport photo</small>
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                <div class="signature-line">Registrar Signature</div>
            </div>
            <div class="signature">
                <div class="signature-line">Principal Signature</div>
            </div>
        </div>

        <div class="print-note">
            This student report is generated for administrative use and should be handled with confidentiality.
        </div>
    </div>
</body>
</html>
