@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-body">

            <h2 class="mb-4">Edit Payment</h2>

            <form action="{{ route('fee-payments.update', $feePayment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Student Name</label>
                    <input
                        type="text"
                        name="student_name"
                        value="{{ old('student_name', $feePayment->student_name) }}"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fee Name</label>
                    <input
                        type="text"
                        name="fee_name"
                        value="{{ old('fee_name', $feePayment->fee_name) }}"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        value="{{ old('amount', $feePayment->amount) }}"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Mode</label>
                    <select name="payment_mode" class="form-control" required>
                        <option value="">Select Payment Mode</option>

                        <option value="Cash"
                            {{ $feePayment->payment_mode == 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="UPI"
                            {{ $feePayment->payment_mode == 'UPI' ? 'selected' : '' }}>
                            UPI
                        </option>

                        <option value="Card"
                            {{ $feePayment->payment_mode == 'Card' ? 'selected' : '' }}>
                            Card
                        </option>

                        <option value="Net Banking"
                            {{ $feePayment->payment_mode == 'Net Banking' ? 'selected' : '' }}>
                            Net Banking
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Pending"
                            {{ $feePayment->status == 'Pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="Paid"
                            {{ $feePayment->status == 'Paid' ? 'selected' : '' }}>
                            Paid
                        </option>
                    </select>
                </div>

                <div class="mb-3" id="paymentDateSection">
                    <label class="form-label">Payment Date</label>

                    <input
                        type="date"
                        name="payment_date"
                        id="payment_date"
                        class="form-control"
                        value="{{ $feePayment->payment_date }}"
                        max="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="btn btn-success">
                    Update Payment
                </button>

                <a href="{{ route('fee-payments.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const status = document.getElementById('status');
    const paymentDateSection = document.getElementById('paymentDateSection');
    const paymentDate = document.getElementById('payment_date');

    function togglePaymentDate() {

        if (status.value === 'Paid') {

            paymentDateSection.style.display = 'block';

            if (!paymentDate.value) {
                paymentDate.value = new Date().toISOString().split('T')[0];
            }

        } else {

            paymentDateSection.style.display = 'none';
            paymentDate.value = '';

        }
    }

    togglePaymentDate();

    status.addEventListener('change', togglePaymentDate);

});
</script>

@endsection