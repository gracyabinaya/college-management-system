@extends('layouts.app')

@section('title', 'Edit Fee Master')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Edit Fee Master</h2>
        <p class="text-muted">Update the fee structure.</p>
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

            <form action="{{ route('fee-masters.update', $feeMaster->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department</label>
                        <input type="text"
                               name="department"
                               class="form-control"
                               value="{{ old('department', $feeMaster->department) }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year</label>

                        <select name="year" class="form-select" required>

                            <option value="I" {{ old('year', $feeMaster->year)=='I'?'selected':'' }}>I Year</option>
                            <option value="II" {{ old('year', $feeMaster->year)=='II'?'selected':'' }}>II Year</option>
                            <option value="III" {{ old('year', $feeMaster->year)=='III'?'selected':'' }}>III Year</option>
                            <option value="IV" {{ old('year', $feeMaster->year)=='IV'?'selected':'' }}>IV Year</option>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text"
                               name="category"
                               class="form-control"
                               value="{{ old('category', $feeMaster->category) }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fee Name</label>
                        <input type="text"
                               name="fee_name"
                               class="form-control"
                               value="{{ old('fee_name', $feeMaster->fee_name) }}"
                               required>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Total Amount (₹)</label>
                        <input type="number"
                               step="0.01"
                               min="1"
                               name="amount"
                               class="form-control"
                               value="{{ old('amount', $feeMaster->amount) }}"
                               required>
                    </div>

                </div>

                <button class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i>
                    Update Fee Master
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