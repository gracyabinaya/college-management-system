@extends('layouts.app')

@section('title', 'View Payment')

@section('content')
<div class="container-fluid">

    <h2>Payment Details</h2>

    <div class="card">
        <div class="card-body">

            <p><strong>Student Name:</strong> {{ $feePayment->student_name }}</p>

            <p><strong>Fee Name:</strong> {{ $feePayment->fee_name }}</p>

            <p><strong>Amount:</strong> ₹ {{ number_format($feePayment->amount, 2) }}</p>

            <p><strong>Status:</strong> {{ $feePayment->status }}</p>

            <p><strong>Payment Mode:</strong> {{ $feePayment->payment_mode }}</p>

            <a href="{{ route('fee-payments.index') }}" class="btn btn-primary">
                Back
            </a>

        </div>
    </div>

</div>
@endsection