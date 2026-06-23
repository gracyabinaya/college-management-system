@extends('layouts.app')

@section('title', 'Fee Payments - CollegeOS')

@section('content')
<div class="container-fluid">
    <!-- Top Bar -->
    <div class="topbar">
        <div>
            <h1>Fee Payments</h1>
            <div class="topbar-sub">Manage student fee payments</div>
        </div>
        <a href="{{ route('fee-payments.create') }}" class="btn-amber">
            <i class="ti ti-plus"></i> Add Payment
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            <i class="ti ti-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">💰 Total Payments</div>
            <div class="stat-value">{{ $payments->count() }}</div>
            <div class="stat-pill pill-amber"><i class="ti ti-receipt"></i> Transactions</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">💵 Total Amount Collected</div>
            <div class="stat-value" style="font-size: 24px;">₹ {{ number_format($payments->sum('amount'), 2) }}</div>
            <div class="stat-pill pill-green"><i class="ti ti-currency-rupee"></i> Received</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">✅ Payment Status</div>
            <div class="stat-value" style="font-size: 24px;">
                {{ $payments->where('status', 'Paid')->count() }} / {{ $payments->count() }}
            </div>
            <div class="stat-pill pill-blue"><i class="ti ti-check-circle"></i> Paid / Total</div>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="table-title">
                <i class="ti ti-list"></i> Payment Records
            </div>
            <div class="table-actions">
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search payments..." onkeyup="searchTable()">
                </div>
                <button class="btn-ghost" onclick="window.print()">
                    <i class="ti ti-printer"></i> Print
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>SI No</th>
                        <th>Student Name</th>
                        <th>Fee Name</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="student-name">{{ $payment->student_name }}</span>
                            </td>
                            <td>
                                <span class="fee-name">{{ $payment->fee_name }}</span>
                            </td>
                            <td>
                                <span class="amount-badge">₹ {{ number_format($payment->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="payment-mode-badge">
                                    <i class="ti ti-{{ $payment->payment_mode == 'Cash' ? 'cash' : ($payment->payment_mode == 'Card' ? 'credit-card' : 'building-bank') }}"></i>
                                    {{ $payment->payment_mode }}
                                </span>
                            </td>
                            <td>
                                @if($payment->status == 'Paid')
                                    <span class="status-badge status-paid">
                                        <i class="ti ti-check-circle"></i> Paid
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="ti ti-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('fee-payments.edit', $payment->id) }}" class="btn-edit">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('fee-payments.destroy', $payment->id) }}"
                                          method="POST"
                                          style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this payment record?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="ti ti-credit-card"></i>
                                    <div class="empty-title">No Payment Records Found</div>
                                    <div class="empty-sub">Click "Add Payment" to record your first payment</div>
                                    <a href="{{ route('fee-payments.create') }}" class="btn-amber" style="margin-top: 15px; display: inline-flex;">
                                        <i class="ti ti-plus"></i> Add Your First Payment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($payments, 'links'))
            <div class="pagination-wrapper">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Styles -->
<style>
    /* Alert Success */
    .alert-success {
        background: var(--green-bg, #eaf5ef);
        color: var(--green, #1a7a4a);
        border: 1px solid #b8d9c9;
        border-radius: var(--radius, 10px);
        padding: 14px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        padding: 20px 22px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -14px;
        top: -14px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        opacity: .08;
    }

    .stat-card:nth-child(1)::after { background: var(--amber, #c8850a); }
    .stat-card:nth-child(2)::after { background: var(--green, #1a7a4a); }
    .stat-card:nth-child(3)::after { background: var(--blue, #2563eb); }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--ink3, #555570);
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        line-height: 1;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        margin-top: 8px;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .pill-amber {
        background: var(--amber-light, #fdf3df);
        color: #7a5200;
    }
    .pill-green {
        background: var(--green-bg, #eaf5ef);
        color: var(--green, #1a7a4a);
    }
    .pill-blue {
        background: var(--blue-bg, #eff6ff);
        color: var(--blue, #2563eb);
    }

    /* Table Container */
    .table-container {
        background: #fff;
        border: 1px solid var(--border, #ddd8d0);
        border-radius: var(--radius-lg, 16px);
        overflow: hidden;
        margin-top: 20px;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border, #ddd8d0);
        background: var(--surface, #f7f5f2);
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        min-width: 180px;
    }

    .search-wrap input {
        width: 100%;
        padding: 7px 12px 7px 34px;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: var(--radius, 10px);
        font-size: 13px;
        background: #fff;
        color: var(--ink, #1a1a2e);
        outline: none;
        transition: border-color .2s;
    }

    .search-wrap input:focus {
        border-color: var(--amber, #c8850a);
        box-shadow: 0 0 0 3px rgba(200,133,10,.12);
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink3, #555570);
        font-size: 14px;
        pointer-events: none;
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        background: transparent;
        border: 1px solid var(--border2, #ccc5b8);
        border-radius: 8px;
        color: var(--ink2, #2d2d4e);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .btn-ghost:hover {
        background: var(--surface2, #efecea);
        color: var(--ink, #1a1a2e);
        text-decoration: none;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        padding: 0;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed;
    }

    .data-table thead {
        background: var(--ink, #1a1a2e);
    }

    .data-table th {
        color: #fff;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* Equal column widths */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
        width: 7%;
        text-align: center;
    }

    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        width: 18%;
    }

    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
        width: 18%;
    }

    .data-table th:nth-child(4),
    .data-table td:nth-child(4) {
        width: 14%;
    }

    .data-table th:nth-child(5),
    .data-table td:nth-child(5) {
        width: 15%;
    }

    .data-table th:nth-child(6),
    .data-table td:nth-child(6) {
        width: 12%;
    }

    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
        width: 16%;
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border, #ddd8d0);
        color: var(--ink2, #2d2d4e);
        vertical-align: middle;
    }

    .data-table td:nth-child(1) {
        text-align: center;
    }

    .data-table tbody tr:hover {
        background: var(--surface, #f7f5f2);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Student Name */
    .student-name {
        font-weight: 500;
        color: var(--ink, #1a1a2e);
        display: block;
        word-wrap: break-word;
        font-size: 13px;
    }

    /* Fee Name */
    .fee-name {
        font-weight: 500;
        color: var(--ink, #1a1a2e);
        display: block;
        word-wrap: break-word;
        font-size: 13px;
    }

    /* Amount Badge */
    .amount-badge {
        font-weight: 700;
        color: #1a7a4a;
        background: var(--green-bg, #eaf5ef);
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        display: inline-block;
        white-space: nowrap;
    }

    /* Payment Mode Badge */
    .payment-mode-badge {
        background: var(--gray-bg, #f0f0f0);
        color: var(--ink2, #2d2d4e);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
        white-space: nowrap;
    }

    .payment-mode-badge i {
        font-size: 14px;
        color: var(--ink3, #555570);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-paid {
        background: var(--green-bg, #eaf5ef);
        color: var(--green, #1a7a4a);
    }

    .status-paid i {
        color: var(--green, #1a7a4a);
    }

    .status-pending {
        background: #fef3e8;
        color: #b45309;
    }

    .status-pending i {
        color: #b45309;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        background: var(--amber-light, #fdf3df);
        color: #7a5200;
        border: 1px solid #f5d990;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-edit:hover {
        background: #fae9a0;
        color: #7a5200;
        text-decoration: none;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        background: var(--red-bg, #fdecea);
        color: var(--red, #c0392b);
        border: 1px solid #f5c6c2;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }

    .btn-delete:hover {
        background: #fbd8d5;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: var(--ink3, #555570);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--border2, #ccc5b8);
        margin-bottom: 16px;
        display: block;
    }

    .empty-state .empty-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--ink2, #2d2d4e);
        margin-bottom: 8px;
    }

    .empty-state .empty-sub {
        font-size: 14px;
        color: var(--ink3, #555570);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 14px 20px;
        border-top: 1px solid var(--border, #ddd8d0);
        background: var(--surface, #f7f5f2);
    }

    /* Top Bar */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .topbar h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--ink, #1a1a2e);
        margin: 0;
    }

    .topbar-sub {
        font-size: 13px;
        color: var(--ink3, #555570);
        margin-top: 2px;
    }

    .btn-amber {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        background: var(--amber, #c8850a);
        color: var(--ink, #1a1a2e);
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .btn-amber:hover {
        background: #d99410;
        transform: translateY(-1px);
        color: var(--ink, #1a1a2e);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .data-table th:nth-child(1),
        .data-table td:nth-child(1) {
            width: 8%;
        }
        .data-table th:nth-child(2),
        .data-table td:nth-child(2) {
            width: 17%;
        }
        .data-table th:nth-child(3),
        .data-table td:nth-child(3) {
            width: 17%;
        }
        .data-table th:nth-child(4),
        .data-table td:nth-child(4) {
            width: 14%;
        }
        .data-table th:nth-child(5),
        .data-table td:nth-child(5) {
            width: 14%;
        }
        .data-table th:nth-child(6),
        .data-table td:nth-child(6) {
            width: 12%;
        }
        .data-table th:nth-child(7),
        .data-table td:nth-child(7) {
            width: 18%;
        }
    }

    @media (max-width: 900px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrap {
            min-width: unset;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-edit,
        .btn-delete {
            justify-content: center;
        }

        .data-table th,
        .data-table td {
            padding: 8px 10px;
            font-size: 12px;
        }

        /* Remove fixed widths on mobile */
        .data-table th:nth-child(1),
        .data-table td:nth-child(1),
        .data-table th:nth-child(2),
        .data-table td:nth-child(2),
        .data-table th:nth-child(3),
        .data-table td:nth-child(3),
        .data-table th:nth-child(4),
        .data-table td:nth-child(4),
        .data-table th:nth-child(5),
        .data-table td:nth-child(5),
        .data-table th:nth-child(6),
        .data-table td:nth-child(6),
        .data-table th:nth-child(7),
        .data-table td:nth-child(7) {
            width: auto;
        }
    }

    @media (max-width: 640px) {
        .topbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .data-table {
            font-size: 11px;
        }

        .data-table th,
        .data-table td {
            padding: 6px 8px;
        }

        .amount-badge,
        .payment-mode-badge,
        .status-badge {
            font-size: 10px;
            padding: 2px 8px;
        }

        .btn-edit,
        .btn-delete {
            font-size: 10px;
            padding: 4px 8px;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Search Script -->
<script>
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('.data-table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
</script>

@endsection