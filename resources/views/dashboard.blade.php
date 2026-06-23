@extends('layouts.app')

@section('title', 'Fee Dashboard')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2>Fee Dashboard</h2>
        <p class="text-muted">Overview of fee collections and payments</p>
    </div>

    <div class="row g-4 mb-4">

        <!-- Total Collection -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Collection</h6>
                    <h3 class="fw-bold">
                        ₹ {{ number_format($totalCollection, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Pending Amount</h6>
                    <h3 class="fw-bold text-danger">
                        ₹ {{ number_format($pendingAmount, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Paid Students -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Paid Students</h6>
                    <h3 class="fw-bold text-success">
                        {{ $paidStudents }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Recent Payments</h6>
                    <h3 class="fw-bold">
                        {{ count($recentPayments) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Recent Payments</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Fee</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentPayments as $payment)
                    <tr>
                        <td>{{ $payment->student_name }}</td>
                        <td>{{ $payment->fee_name }}</td>
                        <td>₹ {{ number_format($payment->amount, 2) }}</td>

                        <td>
                            @if($payment->status == 'Paid')
                                <span class="badge bg-success">
                                    Paid
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            No recent payments found
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection