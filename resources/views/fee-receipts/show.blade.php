@extends('layouts.app')

@section('title', 'Receipt Details')

@section('content')
<div class="container-fluid">

    <h2>Receipt Details</h2>

    <div class="card">
        <div class="card-body">

            <p>
                <strong>Receipt Number:</strong>
                {{ $feeReceipt->receipt_number }}
            </p>

            <p>
                <strong>Student Name:</strong>
                {{ $feeReceipt->student_name }}
            </p>

            <p>
                <strong>Fee Name:</strong>
                {{ $feeReceipt->fee_name }}
            </p>

            <p>
                <strong>Amount:</strong>
                ₹ {{ number_format($feeReceipt->amount, 2) }}
            </p>

            @if($feeReceipt->feePayment)
                <p>
                    <strong>Generated From:</strong>
                    <a href="{{ route('fee-payments.show', $feeReceipt->feePayment->id) }}">
                        Payment #{{ $feeReceipt->feePayment->id }}
                    </a>
                </p>
            @endif

            <a href="{{ route('fee-receipts.index') }}"
               class="btn btn-primary">
                Back
            </a>

        </div>
    </div>

</div>
@endsection