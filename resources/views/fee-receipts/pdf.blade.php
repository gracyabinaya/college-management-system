<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            margin: 22mm 20mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1a1a2e;
            font-size: 12px;
        }

        /* ---------- Header ---------- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 70px;
        }

        .logo-box {
            width: 56px;
            height: 56px;
            border: 1.5px solid #1a1a2e;
            border-radius: 6px;
            text-align: center;
            line-height: 56px;
            font-size: 10px;
            font-weight: 700;
            color: #555570;
        }

        /* If a real logo image is supplied later, replace .logo-box
           with: <img src="{{ public_path('images/logo.png') }}" style="width:56px;height:56px;"> */

        .college-info-cell {
            text-align: center;
        }

        .college-name {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1a1a2e;
            margin: 0;
        }

        .college-sub {
            font-size: 10px;
            color: #555570;
            margin-top: 2px;
            letter-spacing: .4px;
        }

        .report-title {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #c8850a;
            margin-top: 6px;
        }

        .header-rule {
            border: none;
            border-top: 2px solid #1a1a2e;
            margin: 10px 0 16px;
        }

        /* ---------- Receipt meta strip ---------- */
        .meta-strip {
            width: 100%;
            background: #f7f5f2;
            border: 1px solid #ddd8d0;
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

        .meta-strip table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-strip td {
            font-size: 11.5px;
            padding: 2px 0;
        }

        .meta-strip .meta-label {
            font-weight: 700;
            color: #555570;
            width: 90px;
        }

        .meta-strip .meta-value {
            font-weight: 700;
            color: #1a1a2e;
        }

        /* ---------- Details ---------- */
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            border: 1px solid #ddd8d0;
            border-radius: 6px;
        }

        table.details td {
            padding: 9px 14px;
            font-size: 12px;
            vertical-align: top;
            border-bottom: 1px solid #ecebe8;
        }

        table.details tr:last-child td {
            border-bottom: none;
        }

        table.details tr:nth-child(even) {
            background: #fbfaf8;
        }

        .label {
            font-weight: 700;
            color: #555570;
            width: 42%;
        }

        .sep {
            width: 4%;
            color: #999;
        }

        .value {
            color: #1a1a2e;
            font-weight: 600;
        }

        /* ---------- Amount summary box ---------- */
        .amount-box {
            width: 100%;
            border: 1.5px solid #1a1a2e;
            border-radius: 6px;
            margin-top: 16px;
            border-collapse: collapse;
        }

        .amount-box td {
            padding: 10px 16px;
            font-size: 12.5px;
        }

        .amount-box .amt-label {
            color: #555570;
            font-weight: 600;
        }

        .amount-box .amt-value {
            text-align: right;
            font-weight: 700;
            color: #1a1a2e;
        }

        .amount-box .row-divider td {
            border-top: 1px dashed #ccc5b8;
        }

        .paid-this-time-row td {
            background: #eaf5ef;
            font-size: 14px;
            font-weight: 900;
            color: #1a7a4a;
        }

        .balance-row td {
            font-weight: 800;
            color: #b45309;
        }

       

        /* ---------- Footer ---------- */
        .footer-note {
            margin-top: 22px;
            font-size: 10px;
            text-align: center;
            color: #555570;
        }

        .sig-row {
            margin-top: 50px;
            width: 100%;
        }

        .sig-row td {
            width: 50%;
            text-align: center;
            font-size: 10.5px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .sig-line {
            border-top: 1px solid #1a1a2e;
            width: 150px;
            margin: 0 auto 6px;
        }

        .generated-on {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
            color: #999;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo-box">LOGO</div>
            </td>
            <td class="college-info-cell">
                <div class="college-name">College Management System</div>
                <div class="college-sub">Affiliated Institution &middot; Estd. ----</div>
                <div class="report-title">Fee Payment Receipt</div>
            </td>
            <td class="logo-cell"></td>
        </tr>
    </table>
    <hr class="header-rule">

    {{-- Receipt meta --}}
    <div class="meta-strip">
        <table>
            <tr>
                <td class="meta-label">Receipt No</td>
                <td class="meta-value">{{ $receipt->receipt_number }}</td>
                <td class="meta-label" style="text-align:right;">Date</td>
                <td class="meta-value" style="text-align:right;">
                    {{ \Carbon\Carbon::parse($receipt->payment_date)->format('d-m-Y') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Student / fee details --}}
    <table class="details">
        <tr>
            <td class="label">Student Name</td>
            <td class="sep">:</td>
            <td class="value">{{ $receipt->student_name }}</td>
        </tr>
        @if($summary['student_college_id'])
        <tr>
            <td class="label">Student ID</td>
            <td class="sep">:</td>
            <td class="value">{{ $summary['student_college_id'] }}</td>
        </tr>
        @endif
        @if($summary['department'])
        <tr>
            <td class="label">Department</td>
            <td class="sep">:</td>
            <td class="value">{{ $summary['department'] }}</td>
        </tr>
        @endif
        @if($summary['year'])
        <tr>
            <td class="label">Academic Year</td>
            <td class="sep">:</td>
            <td class="value">{{ $summary['year'] }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Fee Name</td>
            <td class="sep">:</td>
            <td class="value">{{ $receipt->fee_name }}</td>
        </tr>
        @if($summary['payment_mode'])
        <tr>
            <td class="label">Payment Mode</td>
            <td class="sep">:</td>
            <td class="value">{{ $summary['payment_mode'] }}</td>
        </tr>
        @endif
    </table>

    {{-- Amount summary --}}
    <table class="amount-box">
        <tr>
            <td class="amt-label">Total Fee</td>
            <td class="amt-value">₹ {{ number_format($summary['total_fee'], 2) }}</td>
        </tr>
        <tr class="paid-this-time-row">
            <td class="amt-label">Paid This Time</td>
            <td class="amt-value">₹ {{ number_format($summary['paid_this_time'], 2) }}</td>
        </tr>
        <tr class="row-divider">
            <td class="amt-label">Total Paid To Date</td>
            <td class="amt-value">₹ {{ number_format($summary['total_paid'], 2) }}</td>
        </tr>
        <tr class="balance-row">
            <td class="amt-label">Balance Remaining</td>
            <td class="amt-value">₹ {{ number_format($summary['balance'], 2) }}</td>
        </tr>
    </table>

    

    <div class="footer-note">
        This is a computer-generated receipt and is valid proof of payment.
    </div>

    <table class="sig-row">
        <tr>
            <td>
                <div class="sig-line"></div>
                Prepared By
            </td>
            <td>
                <div class="sig-line"></div>
                Authorized Signature
            </td>
        </tr>
    </table>

    <div class="generated-on">
        Generated on {{ now()->format('d-m-Y h:i A') }}
    </div>

</body>
</html>