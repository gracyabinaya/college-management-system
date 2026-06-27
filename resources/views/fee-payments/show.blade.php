@extends('layouts.app')

@section('title', 'View Payment')

@section('content')
<div class="container-fluid">

    <h2>Payment Details</h2>

    <div class="card mb-4">
        <div class="card-body">

            <p><strong>Student Name:</strong> {{ $summary['student_name'] }}</p>

            @if($summary['department'])
                <p><strong>Department:</strong> {{ $summary['department'] }}</p>
            @endif

            @if($summary['year'])
                <p><strong>Academic Year:</strong> {{ $summary['year'] }}</p>
            @endif

            <p><strong>Fee Name:</strong> {{ $summary['fee_name'] }}</p>

            <p><strong>Total Fee:</strong> ₹ {{ number_format($summary['total_amount'], 2) }}</p>

            <p><strong>Total Paid:</strong> ₹ {{ number_format($summary['paid_amount'], 2) }}</p>

            <p><strong>Balance:</strong> ₹ {{ number_format($summary['balance_amount'], 2) }}</p>

            <p>
                <strong>Status:</strong>
                @if($summary['status'] == 'Paid')
                    <span class="status-badge status-paid">Paid</span>
                @elseif($summary['status'] == 'Partial')
                    <span class="status-badge status-partial">Partial</span>
                @else
                    <span class="status-badge status-pending">Pending</span>
                @endif
            </p>

            @if($summary['last_payment_mode'])
                <p><strong>Last Payment Mode:</strong> {{ $summary['last_payment_mode'] }}</p>
            @endif

            <a href="{{ route('fee-payments.index') }}" class="btn btn-secondary">
                Back
            </a>

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h5 class="mb-3">Installment History</h5>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount Paid</th>
                            <th>Mode</th>
                            
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($installments as $index => $installment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $installment->payment_date
                                        ? \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y')
                                        : '—' }}
                                </td>
                                <td>₹ {{ number_format($installment->amount_paid, 2) }}</td>
                                <td>{{ $installment->payment_mode }}</td>
                                <td>{{ $installment->remarks ?: '—' }}</td>
                                <td>
                                    @if($installment->feeReceipt)
                                        <a href="{{ route('fee-receipts.show', $installment->feeReceipt->id) }}"
                                           class="btn-view">
                                            <i class="ti ti-receipt"></i> View Receipt
                                        </a>
                                    @else
                                        <form action="{{ route('fee-payments.generate-receipt', $installment->id) }}"
                                              method="POST"
                                              style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn-edit">
                                                <i class="ti ti-receipt"></i> Generate Receipt
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center">
                                    No installments recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection