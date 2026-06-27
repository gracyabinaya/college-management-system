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
                    <label class="form-label">Student</label>

                    <select name="student_id" id="student_id" class="form-control" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ $feePayment->student_id == $student->id ? 'selected' : '' }}>
                                {{ $student->student_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fee Name</label>

                    <select name="fee_master_id" id="fee_master" class="form-select" required>
                        <option value="">Select Fee</option>

                        @foreach($feeMasters as $fee)
                            <option
                                value="{{ $fee->id }}"
                                {{ $feePayment->fee_master_id == $fee->id ? 'selected' : '' }}>
                                {{ $fee->fee_name }} ({{ $fee->department }} - {{ $fee->year }} Year) &mdash; ₹{{ number_format($fee->amount, 2) }}
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">Amounts below are calculated live from existing installments for this student + fee.</small>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Total Fee</label>
                        <input type="text" id="total_fee" class="form-control" value="{{ number_format($summary['total_amount'], 2) }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Already Paid <small class="text-muted">(excluding this installment)</small></label>
                        <input type="text" id="already_paid" class="form-control" value="{{ number_format($summary['paid_amount'], 2) }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Remaining Balance <small class="text-muted">(before this installment)</small></label>
                        <input type="text" id="remaining_balance" class="form-control" value="{{ number_format($summary['balance_amount'], 2) }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Amount Paying Now</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            name="amount_paid"
                            id="amount_paid"
                            value="{{ old('amount_paid', $feePayment->amount_paid) }}"
                            max="{{ $summary['balance_amount'] }}"
                            class="form-control"
                            required>
                        @error('amount_paid')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Mode</label>
                    <select name="payment_mode" class="form-control" required>
                        <option value="">Select Payment Mode</option>

                        <option value="Cash" {{ $feePayment->payment_mode == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="UPI" {{ $feePayment->payment_mode == 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Card" {{ $feePayment->payment_mode == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Net Banking" {{ $feePayment->payment_mode == 'Net Banking' ? 'selected' : '' }}>Net Banking</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>

                    <input
                        type="date"
                        name="payment_date"
                        id="payment_date"
                        class="form-control"
                        value="{{ old('payment_date', optional($feePayment->payment_date)->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $feePayment->remarks) }}">
                </div>

                <button type="submit" class="btn btn-success">
                    Update Payment
                </button>

                <a href="{{ route('fee-payments.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const studentSelect = document.getElementById('student_id');
    const feeSelect      = document.getElementById('fee_master');
    const totalFee        = document.getElementById('total_fee');
    const alreadyPaid      = document.getElementById('already_paid');
    const remaining         = document.getElementById('remaining_balance');
    const amount              = document.getElementById('amount_paid');

    const excludePaymentId = {{ $feePayment->id }};

    function loadSummary() {
        const studentId    = studentSelect.value;
        const feeMasterId  = feeSelect.value;

        if (!studentId || !feeMasterId) return;

        const url = `{{ route('fee-payments.summary') }}?student_id=${studentId}&fee_master_id=${feeMasterId}&exclude_payment_id=${excludePaymentId}`;

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Request failed: ' + res.status);
                return res.json();
            })
            .then(data => {
                totalFee.value    = parseFloat(data.total_amount).toFixed(2);
                alreadyPaid.value  = parseFloat(data.paid_amount).toFixed(2);
                remaining.value      = parseFloat(data.balance_amount).toFixed(2);

                amount.max = data.balance_amount;
            })
            .catch(err => {
                console.error('Error loading fee summary:', err);
            });
    }

    studentSelect.addEventListener('change', loadSummary);
    feeSelect.addEventListener('change', loadSummary);

    amount.addEventListener('input', function () {
        const max = parseFloat(this.max);

        if (!isNaN(max) && parseFloat(this.value) > max) {
            alert('Amount cannot exceed Remaining Balance (₹' + max.toFixed(2) + ')');
            this.value = max;
        }
    });

});
</script>

@endsection