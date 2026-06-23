@extends('layouts.app')

@section('title', 'Fee Payment')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Fee Payment</h2>
        <p class="text-muted">Record a student fee payment</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('fee-payments.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Student Name</label>
                    <input
                        type="text"
                        name="student_name"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fee Name</label>
                    <input
                        type="text"
                        name="fee_name"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Mode</label>
                    <select name="payment_mode" class="form-select">
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
                        <option value="Card">Card</option>
                        <option value="Net Banking">Net Banking</option>
                    </select>
                </div>

                <div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="Pending">Pending</option>
        <option value="Paid">Paid</option>
    </select>
</div>

<div class="mb-3" id="paymentDateSection" style="display:none;">
    <label class="form-label">Payment Date</label>
    <input type="date"
           name="payment_date"
           id="payment_date"
           class="form-control"
           max="{{ date('Y-m-d') }}">
</div>

                <button type="submit" class="btn btn-warning">
                    <i class="ti ti-device-floppy"></i>
                    Save Payment
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
            paymentDate.required = true;

            if (!paymentDate.value) {
                paymentDate.value = new Date().toISOString().split('T')[0];
            }

        } else {
            paymentDateSection.style.display = 'none';
            paymentDate.required = false;
            paymentDate.value = '';
        }
    }

    togglePaymentDate();

    status.addEventListener('change', togglePaymentDate);

});
</script>

@endsection