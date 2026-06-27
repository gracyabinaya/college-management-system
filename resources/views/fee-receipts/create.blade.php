@extends('layouts.app')

@section('title', 'Create Fee Receipt')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h2>Create Fee Receipt</h2>
        <p class="text-muted">
            Select an existing fee payment to generate its receipt. Every receipt is
            always linked to a real payment record.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('fee-receipts.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Fee Payment
                    </label>

                    <select
                        name="fee_payment_id"
                        class="form-select"
                        required>

                        <option value="">
                            Select a payment to generate a receipt for
                        </option>

                        @forelse($unreceiptedPayments as $payment)

                            <option value="{{ $payment->id }}">
                                {{ $payment->student->student_name ?? 'N/A' }}
                                &mdash;
                                {{ $payment->feeMaster->fee_name ?? 'N/A' }}
                                &mdash;
                                ₹{{ number_format($payment->amount_paid, 2) }}
                                ({{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }})
                            </option>

                        @empty

                            <option value="" disabled>
                                No unreceipted payments available
                            </option>

                        @endforelse

                    </select>

                    @if($unreceiptedPayments->isEmpty())
                        <div class="form-text text-muted">
                            Every existing payment already has a receipt. New payments will
                            appear here once recorded.
                        </div>
                    @endif

                </div>

                <button class="btn btn-warning" {{ $unreceiptedPayments->isEmpty() ? 'disabled' : '' }}>
                    <i class="ti ti-receipt"></i>
                    Generate Receipt
                </button>

                <a href="{{ route('fee-receipts.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>
@endsection