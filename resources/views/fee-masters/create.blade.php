@extends('layouts.app')

@section('title', 'Create Fee Master')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Create Fee Master</h2>
        <p class="text-muted">Create a fee structure for a department and academic year.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('fee-masters.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department</label>
                        <input type="text"
                               name="department"
                               class="form-control"
                               value="{{ old('department') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year</label>

                        <select name="year" class="form-select" required>
                            <option value="">Select Year</option>
                            <option value="I" {{ old('year')=='I'?'selected':'' }}>I Year</option>
                            <option value="II" {{ old('year')=='II'?'selected':'' }}>II Year</option>
                            <option value="III" {{ old('year')=='III'?'selected':'' }}>III Year</option>
                            <option value="IV" {{ old('year')=='IV'?'selected':'' }}>IV Year</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text"
                               name="category"
                               class="form-control"
                               value="{{ old('category') }}"
                               placeholder="Tuition / Exam / Hostel"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fee Name</label>
                        <input type="text"
                               name="fee_name"
                               class="form-control"
                               value="{{ old('fee_name') }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Total Amount (₹)</label>
                        <input type="number"
                               step="0.01"
                               min="1"
                               name="amount"
                               class="form-control"
                               value="{{ old('amount') }}"
                               required>
                    </div>

                </div>

                <button class="btn btn-warning">
                    <i class="ti ti-device-floppy"></i>
                    Save Fee Master
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