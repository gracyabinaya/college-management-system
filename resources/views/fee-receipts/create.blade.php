@extends('layouts.app')

@section('title', 'Generate Receipt')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Generate Receipt</h2>
        <p class="text-muted">Create a new fee receipt</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('fee-receipts.store') }}" method="POST">
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
                        name="amount"
                        step="0.01"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input
                        type="date"
                        name="payment_date"
                        class="form-control"
                        required>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="ti ti-receipt"></i>
                    Generate Receipt
                </button>

                <a href="{{ route('fee-receipts.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection