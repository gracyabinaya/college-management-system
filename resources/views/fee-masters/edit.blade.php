@extends('layouts.app')

@section('title', 'Edit Fee')

@section('content')
<div class="container">

    <h2>Edit Fee Master</h2>

    <form action="{{ route('fee-masters.update', $feeMaster->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Department</label>
            <input type="text"
                   name="department"
                   class="form-control"
                   value="{{ $feeMaster->department }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text"
                   name="category"
                   class="form-control"
                   value="{{ $feeMaster->category }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Fee Name</label>
            <input type="text"
                   name="fee_name"
                   class="form-control"
                   value="{{ $feeMaster->fee_name }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Amount</label>
            <input type="number"
                   step="0.01"
                   name="amount"
                   class="form-control"
                   value="{{ $feeMaster->amount }}"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Update Fee
        </button>

        <a href="{{ route('fee-masters.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </form>

</div>
@endsection