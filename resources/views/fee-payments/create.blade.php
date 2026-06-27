@extends('layouts.app')

@section('title','Fee Payment')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>New Fee Payment</h2>
        <p class="text-muted">
            Record a student's fee payment.
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

            <form action="{{ route('fee-payments.store') }}" method="POST">

                @csrf

                <div class="row">

                    {{-- Student --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Student
                        </label>

                        <select
                            id="student_id"
                            name="student_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Student
                            </option>

                            @foreach($students as $student)

                                <option
                                    value="{{ $student->id }}"
                                    {{ (string) old('student_id', request('student_id')) === (string) $student->id ? 'selected' : '' }}>

                                    {{ $student->student_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Fee Master --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Fee
                        </label>

                        <select
                            id="fee_master"
                            name="fee_master_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Fee
                            </option>

                            @foreach($feeMasters as $fee)

                                <option
                                    value="{{ $fee->id }}"
                                    data-name="{{ $fee->fee_name }}"
                                    data-department="{{ $fee->department }}"
                                    data-year="{{ $fee->year }}"
                                    data-amount="{{ $fee->amount }}"
                                    {{ (string) old('fee_master_id', request('fee_master_id')) === (string) $fee->id ? 'selected' : '' }}>

                                    {{ $fee->fee_name }}
                                    -
                                    {{ $fee->department }}
                                    -
                                    {{ $fee->year }} Year

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Department --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Department

                        </label>

                        <input
                            type="text"
                            id="department"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Year --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Academic Year

                        </label>

                        <input
                            type="text"
                            id="year"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Fee Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Fee Name

                        </label>

                        <input
                            type="text"
                            id="fee_name"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Total Fee --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Total Fee Amount
                        </label>

                        <input
                            type="text"
                            id="total_fee"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Already Paid --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Already Paid
                        </label>

                        <input
                            type="text"
                            id="already_paid"
                            class="form-control"
                            value="0"
                            readonly>

                    </div>

                    {{-- Remaining Balance --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Remaining Balance
                        </label>

                        <input
                            type="text"
                            id="remaining_balance"
                            class="form-control"
                            readonly>

                    </div>

                    {{-- Amount Paying Now --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Amount Paying Now
                        </label>

                        <input
                            type="number"
                            name="amount_paid"
                            id="amount_paid"
                            class="form-control"
                            min="1"
                            step="0.01"
                            required>

                        <div class="form-text" id="balanceHint"></div>

                    </div>

                    {{-- Payment Mode --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Payment Mode

                        </label>

                        <select
                            name="payment_mode"
                            class="form-select">

                            <option>Cash</option>
                            <option>UPI</option>
                            <option>Card</option>
                            <option>Net Banking</option>

                        </select>

                    </div>

                    {{-- Payment Date --}}
                    <div
                        class="col-md-6 mb-3"
                        id="paymentDateSection"
                        style="display:none;">

                        <label class="form-label">

                            Payment Date

                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            class="form-control"
                            value="{{ date('Y-m-d') }}">

                    </div>

                   
                </div>

                <button class="btn btn-warning">

                    <i class="ti ti-device-floppy"></i>

                    Save Payment

                </button>

                <a
                    href="{{ route('fee-payments.index') }}"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

<script>

const studentSelect = document.getElementById('student_id');
const feeSelect = document.getElementById('fee_master');

const totalFee = document.getElementById('total_fee');
const alreadyPaid = document.getElementById('already_paid');
const remainingBalance = document.getElementById('remaining_balance');
const amountInput = document.getElementById('amount_paid');
const balanceHint = document.getElementById('balanceHint');
const paymentDateSection = document.getElementById('paymentDateSection');

function fillFeeMasterFields() {

    let selected = feeSelect.options[feeSelect.selectedIndex];

    document.getElementById('department').value =
        selected?.dataset.department ?? '';

    document.getElementById('year').value =
        selected?.dataset.year ?? '';

    document.getElementById('fee_name').value =
        selected?.dataset.name ?? '';
}

// Shows/hides the Payment Date field based on whether this payment
// will fully settle the balance. This is purely a UI convenience —
// it has no effect on what gets saved; the server always computes
// the real status independently from amount_paid vs balance.
function updatePaymentDateVisibility() {

    const balance = parseFloat(remainingBalance.value);
    const amount = parseFloat(amountInput.value);

    const willBeFullyPaid = !isNaN(balance) && !isNaN(amount) && amount > 0
        && (balance - amount) <= 0;

    paymentDateSection.style.display = willBeFullyPaid ? 'block' : 'none';
}

// Calls the real backend summary endpoint, which computes
// paid/balance from actual stored installments (FeePaymentController::buildSummary).
async function loadSummary() {

    fillFeeMasterFields();

    const studentId = studentSelect.value;
    const feeMasterId = feeSelect.value;

    // Reset fields until both are chosen
    if (!studentId || !feeMasterId) {
        totalFee.value = '';
        alreadyPaid.value = '0.00';
        remainingBalance.value = '';
        amountInput.value = '';
        amountInput.removeAttribute('max');
        balanceHint.textContent = '';
        paymentDateSection.style.display = 'none';
        return;
    }

    const selectedFee = feeSelect.options[feeSelect.selectedIndex];
    const fallbackAmount = parseFloat(selectedFee?.dataset.amount || 0);

    try {

        const params = new URLSearchParams({
            student_id: studentId,
            fee_master_id: feeMasterId,
        });

        const response = await fetch(`{{ route('fee-payments.summary') }}?${params}`, {
            headers: { 'Accept': 'application/json' },
        });

        if (!response.ok) {
            throw new Error('Summary request failed');
        }

        const data = await response.json();

        totalFee.value = Number(data.total_amount).toFixed(2);
        alreadyPaid.value = Number(data.paid_amount).toFixed(2);
        remainingBalance.value = Number(data.balance_amount).toFixed(2);

        amountInput.value = '';
        amountInput.max = data.balance_amount;

        balanceHint.textContent = data.balance_amount > 0
            ? `Up to ₹${Number(data.balance_amount).toFixed(2)} can be paid.`
            : 'This fee is fully paid.';

    } catch (err) {

        console.error(err);

        // Fall back to fee master's full amount if the summary call fails,
        // so the form is still usable rather than silently broken.
        totalFee.value = fallbackAmount.toFixed(2);
        alreadyPaid.value = '0.00';
        remainingBalance.value = fallbackAmount.toFixed(2);
        amountInput.value = '';
        amountInput.max = fallbackAmount;

        balanceHint.textContent = 'Could not verify balance with server — please double check before saving.';
    }

    updatePaymentDateVisibility();
}

studentSelect.addEventListener('change', loadSummary);
feeSelect.addEventListener('change', loadSummary);

amountInput.addEventListener('input', function () {

    let max = parseFloat(this.max);

    let value = parseFloat(this.value);

    if (!isNaN(max) && value > max) {

        alert('Amount cannot exceed Remaining Balance.');

        this.value = max;

    }

    updatePaymentDateVisibility();

});

// If the page loaded with student_id/fee_master_id pre-selected
// (e.g. via the "Collect" link from the Pending Fees page), load
// the real summary immediately instead of waiting for a change event.
if (studentSelect.value && feeSelect.value) {
    loadSummary();
}

</script>

@endsection