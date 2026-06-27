<?php

namespace App\Http\Controllers;

use App\Models\FeeMaster;
use App\Models\FeePayment;
use App\Models\Student;
use Illuminate\Http\Request;

class FeePaymentController extends Controller
{
    /* =================================================================
     | LIST PAGE
     | One row per (student, fee_master) combo that has at least one
     | installment, with total/paid/balance/status always computed
     | fresh — never read from a stored column.
     |================================================================*/
    public function index()
    {
        $combos = FeePayment::select('student_id', 'fee_master_id')
            ->distinct()
            ->get();

        $payments = $combos
            ->map(fn($c) => $this->buildSummary($c->student_id, $c->fee_master_id))
            ->filter() // drop combos whose student/fee was deleted since
            ->values();

        return view('fee-payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::orderBy('student_name')->get();

        $feeMasters = FeeMaster::orderBy('department')
            ->orderBy('year')
            ->orderBy('fee_name')
            ->get();

        return view('fee-payments.create', compact('students', 'feeMasters'));
    }

    public function edit(FeePayment $feePayment)
    {
        $students = Student::orderBy('student_name')->get();

        $feeMasters = FeeMaster::orderBy('department')
            ->orderBy('year')
            ->orderBy('fee_name')
            ->get();

        // Balance available to this installment = total - (everything
        // paid EXCLUDING this row), so editing the amount re-validates
        // correctly against the original balance before this payment.
        $summary = $this->buildSummary(
            $feePayment->student_id,
            $feePayment->fee_master_id,
            $feePayment->id
        );

        return view('fee-payments.edit', compact('feePayment', 'students', 'feeMasters', 'summary'));
    }

    /* =================================================================
     | AJAX: live summary for the create/edit page
     | (Student/Dept/Year/Fee Name/Total/Paid/Balance)
     |================================================================*/
    public function summary(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|exists:students,id',
            'fee_master_id' => 'required|exists:fee_masters,id',
        ]);

        $excludeId = $request->input('exclude_payment_id');

        $summary = $this->buildSummary(
            $request->student_id,
            $request->fee_master_id,
            $excludeId
        );

        if (!$summary) {
            return response()->json(['message' => 'Student or Fee not found.'], 404);
        }

        return response()->json($summary);
    }

    /* =================================================================
     | CORE BUSINESS LOGIC — single source of truth.
     | total_amount / paid_amount / balance_amount / status are ALWAYS
     | derived here. Nothing is ever persisted to fee_payments for
     | these values.
     |================================================================*/
    private function buildSummary($studentId, $feeMasterId, $excludePaymentId = null)
    {
        $student = Student::find($studentId);
        $feeMaster = FeeMaster::find($feeMasterId);

        if (!$student || !$feeMaster) {
            return null;
        }

        $query = FeePayment::where('student_id', $studentId)
            ->where('fee_master_id', $feeMasterId);

        if ($excludePaymentId) {
            $query->where('id', '!=', $excludePaymentId);
        }

        $totalAmount   = (float) $feeMaster->amount;
        $paidAmount    = (float) $query->sum('amount_paid');
        $balanceAmount = max($totalAmount - $paidAmount, 0);

        $lastInstallment = (clone $query)->latest('payment_date')->latest('id')->first();

        $samplePaymentId = $lastInstallment?->id;
        $lastPaymentDate = $lastInstallment?->payment_date;
        $lastPaymentMode = $lastInstallment?->payment_mode;

        return [
            'student_id'         => $student->id,
            'student_name'       => $student->student_name ?? 'N/A',
            'department'         => $feeMaster->department ?? null,
            'year'               => $feeMaster->year ?? null,
            'fee_master_id'      => $feeMaster->id,
            'fee_name'           => $feeMaster->fee_name ?? 'N/A',
            'total_amount'       => $totalAmount,
            'paid_amount'        => $paidAmount,
            'balance_amount'     => $balanceAmount,
            'last_payment_date'  => $lastPaymentDate,
            'last_payment_mode'  => $lastPaymentMode,
            'status'             => $this->calculateStatus($paidAmount, $balanceAmount),
            'sample_payment_id'  => $samplePaymentId,
        ];
    }

    /**
     * Pending -> paid == 0 (no installment recorded at all for this combo)
     * Partial -> paid > 0 AND balance > 0
     * Paid    -> balance == 0
     *
     * NOTE: Because buildSummary() is only ever called for (student, fee)
     * combos that ALREADY have at least one row in fee_payments (see
     * index()/summariesWithStatus() below, which both start from
     * FeePayment::distinct()), a combo with paid_amount == 0 can only
     * happen if every installment for that combo is literally ₹0 — which
     * shouldn't occur given store()'s 'min:1' validation. In practice,
     * every combo reaching this method will resolve to 'Partial' or
     * 'Paid'. True "never paid anything" pending students (no row at
     * all) are NOT covered by this method — see pending() below.
     */
    private function calculateStatus(float $paidAmount, float $balanceAmount): string
    {
        if ($balanceAmount <= 0) {
            return 'Paid';
        }

        if ($paidAmount > 0) {
            return 'Partial';
        }

        return 'Pending';
    }

    /* =================================================================
     | STORE — create one installment
     |================================================================*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'fee_master_id' => 'required|exists:fee_masters,id',
            'amount_paid'   => 'required|numeric|min:1',
            'payment_mode'  => 'required|string',
            'payment_date'  => 'nullable|date',
            
        ]);

        $summary = $this->buildSummary($validated['student_id'], $validated['fee_master_id']);

        if (!$summary) {
            return back()->withInput()->withErrors([
                'fee_master_id' => 'Could not resolve student or fee record.',
            ]);
        }

        if ($validated['amount_paid'] > $summary['balance_amount']) {
            return back()->withInput()->withErrors([
                'amount_paid' => "Amount cannot exceed remaining balance (₹{$summary['balance_amount']}).",
            ]);
        }

        FeePayment::create([
            'student_id'    => $validated['student_id'],
            'fee_master_id' => $validated['fee_master_id'],
            'amount_paid'   => $validated['amount_paid'],
            'payment_mode'  => $validated['payment_mode'],
            'payment_date'  => $validated['payment_date'] ?? now(),
           
        ]);

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Installment recorded successfully.');
    }

    /* =================================================================
     | UPDATE — edit one installment
     | Student/Fee CAN be changed here (your edit page allows reassigning
     | both), so we re-validate the new balance under the NEW combo.
     |================================================================*/
    public function update(Request $request, FeePayment $feePayment)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'fee_master_id' => 'required|exists:fee_masters,id',
            'amount_paid'   => 'required|numeric|min:1',
            'payment_mode'  => 'required|string',
            'payment_date'  => 'nullable|date',
            
        ]);

        $summary = $this->buildSummary(
            $validated['student_id'],
            $validated['fee_master_id'],
            $feePayment->id // exclude this installment's current amount before re-checking
        );

        if (!$summary) {
            return back()->withInput()->withErrors([
                'fee_master_id' => 'Could not resolve student or fee record.',
            ]);
        }

        if ($validated['amount_paid'] > $summary['balance_amount']) {
            return back()->withInput()->withErrors([
                'amount_paid' => "Amount cannot exceed remaining balance (₹{$summary['balance_amount']}).",
            ]);
        }

        $feePayment->update([
            'student_id'    => $validated['student_id'],
            'fee_master_id' => $validated['fee_master_id'],
            'amount_paid'   => $validated['amount_paid'],
            'payment_mode'  => $validated['payment_mode'],
            'payment_date'  => $validated['payment_date'] ?? $feePayment->payment_date,
            
        ]);

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Installment updated successfully.');
    }

    /* =================================================================
     | SHOW — full installment history for this student+fee combo
     |================================================================*/
    public function show(FeePayment $feePayment)
    {
        $summary = $this->buildSummary($feePayment->student_id, $feePayment->fee_master_id);

        $installments = FeePayment::where('student_id', $feePayment->student_id)
            ->where('fee_master_id', $feePayment->fee_master_id)
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        return view('fee-payments.show', compact('summary', 'installments'));
    }

    public function destroy(FeePayment $feePayment)
    {
        $feePayment->delete();

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Installment deleted successfully.');
    }

    /* =================================================================
     | FILTERED LIST VIEWS — status is computed, so filtering happens
     | in PHP after building summaries, not via a stored column.
     |
     | "Pending" page = anything NOT fully paid yet, i.e. Pending OR
     | Partial. This matches the page's own subtitle ("View all
     | pending & partially paid fee records") and is the actionable
     | list for office staff — both need a payment collected.
     |================================================================*/
    public function pending()
    {
        return view('fee-payments.pending', [
            'payments' => $this->summariesWithStatus(['Pending', 'Partial']),
        ]);
    }

    public function paid()
    {
        return view('fee-payments.paid', [
            'payments' => $this->summariesWithStatus(['Paid']),
        ]);
    }

    private function summariesWithStatus(array $statuses)
    {
        $combos = FeePayment::select('student_id', 'fee_master_id')->distinct()->get();

        return $combos
            ->map(fn($c) => $this->buildSummary($c->student_id, $c->fee_master_id))
            ->filter()
            ->filter(fn($s) => in_array($s['status'], $statuses, true))
            ->values();
    }
}