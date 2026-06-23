@extends('layouts.app')

@section('title', 'Create Fee')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Create Fee Master</h2>
        <p class="text-muted">Add a new fee structure</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('fee-masters.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input
                        type="text"
                        name="department"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input
                        type="text"
                        name="category"
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

                <button type="submit" class="btn btn-warning">
                    <i class="ti ti-device-floppy"></i>
                    Save Fee
                </button>

                <a href="{{ route('fee-masters.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection