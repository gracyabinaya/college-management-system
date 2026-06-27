<?php

namespace App\Http\Controllers;

use App\Models\FeeReceipt;
use App\Models\FeePayment;
use App\Models\FeeMaster;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FeeReceiptController extends Controller
{
    /**
     * Generate the next receipt number for the current month, e.g.
     * REC-202606-00001, REC-202606-00002, ... resetting to 00001
     * at the start of each new month.
     *
     * Wrapped in a transaction + lockForUpdate to avoid a race
     * condition producing duplicate numbers if two receipts are
     * generated at the same moment.
     */
    private function generateReceiptNumber(): string
    {
        $monthKey = now()->format('Ym'); // e.g. "202606"

        return DB::transaction(function () use ($monthKey) {

            $prefix = "REC-{$monthKey}-";

            $lastForMonth = FeeReceipt::where('receipt_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->orderBy('receipt_number', 'desc')
                ->first();

            $nextSequence = 1;

            if ($lastForMonth) {
                $lastSequence = (int) substr($lastForMonth->receipt_number, -5);
                $nextSequence = $lastSequence + 1;
            }

            return $prefix . str_pad((string) $nextSequence, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Create + persist a receipt tied to a real FeePayment row.
     * This is the ONLY path that should ever create a FeeReceipt —
     * every receipt is always connected to an actual installment.
     */
    private function createReceiptForPayment(FeePayment $feePayment): FeeReceipt
    {
        $feePayment->loadMissing(['student', 'feeMaster']);

        return FeeReceipt::create([
            'receipt_number' => $this->generateReceiptNumber(),
            'student_id'     => $feePayment->student_id,
            'fee_payment_id' => $feePayment->id,
            'student_name'   => $feePayment->student->student_name ?? 'N/A',
            'fee_name'       => $feePayment->feeMaster->fee_name ?? 'N/A',
            'amount'         => $feePayment->amount_paid,
            'payment_date'   => $feePayment->payment_date ?? now()->toDateString(),
        ]);
    }

    /**
     * Auto-generate a receipt from an existing fee payment
     * (called from the "Generate Receipt" action on the Fee Payments page).
     */
    public function generateFromPayment(FeePayment $feePayment)
    {
        $feePayment->loadMissing(['student', 'feeMaster']);

        if ($feePayment->feeReceipt) {
            return redirect()
                ->route('fee-receipts.show', $feePayment->feeReceipt->id)
                ->with('success', 'A receipt already exists for this payment.');
        }

        $receipt = $this->createReceiptForPayment($feePayment);

        return redirect()
            ->route('fee-receipts.show', $receipt->id)
            ->with('success', 'Receipt generated successfully from payment.');
    }

    /**
     * Display all receipts
     */
    public function index()
    {
        $receipts = FeeReceipt::with('student')->latest()->get();

        return view('fee-receipts.index', compact('receipts'));
    }

    /**
     * Show create form — lists only FeePayment installments that
     * don't already have a receipt, so every receipt created here
     * is guaranteed to be linked to a real payment.
     */
    public function create()
    {
        $unreceiptedPayments = FeePayment::with(['student', 'feeMaster'])
            ->whereDoesntHave('feeReceipt')
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        return view('fee-receipts.create', compact('unreceiptedPayments'));
    }

    /**
     * Store receipt — now just picks an existing FeePayment by ID.
     * No free-typed fee_name/amount; everything is pulled from the
     * real payment record so the receipt can never be disconnected.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_payment_id' => 'required|exists:fee_payments,id',
        ]);

        $feePayment = FeePayment::with(['student', 'feeMaster'])
            ->findOrFail($validated['fee_payment_id']);

        if ($feePayment->feeReceipt) {
            return back()->withErrors([
                'fee_payment_id' => 'A receipt already exists for this payment.',
            ]);
        }

        $receipt = $this->createReceiptForPayment($feePayment);

        return redirect()
            ->route('fee-receipts.show', $receipt->id)
            ->with('success', 'Receipt created successfully.');
    }

    /**
     * Show receipt
     */
    public function show(FeeReceipt $feeReceipt)
    {
        $feeReceipt->loadMissing(['student', 'feePayment.feeMaster']);

        $summary = $this->buildReceiptSummary($feeReceipt);

        return view('fee-receipts.show', compact('feeReceipt', 'summary'));
    }

    /**
     * Edit receipt form — payment_mode/remarks/date can still be
     * corrected via the underlying FeePayment if needed, but the
     * receipt<->payment link itself is permanent once created.
     */
    public function edit(FeeReceipt $feeReceipt)
    {
        $feeReceipt->loadMissing(['student', 'feePayment.feeMaster']);

        return view('fee-receipts.edit', compact('feeReceipt'));
    }

    /**
     * Update receipt — only the payment_date is editable here
     * (e.g. to correct a data-entry mistake). student/fee/amount
     * always come from the linked FeePayment, never edited directly.
     */
    public function update(Request $request, FeeReceipt $feeReceipt)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
        ]);

        $feeReceipt->update([
            'payment_date' => $validated['payment_date'],
        ]);

        return redirect()
            ->route('fee-receipts.index')
            ->with('success', 'Receipt updated successfully.');
    }

    /**
     * Delete receipt
     */
    public function destroy(FeeReceipt $feeReceipt)
    {
        $feeReceipt->delete();

        return redirect()
            ->route('fee-receipts.index')
            ->with('success', 'Receipt deleted successfully.');
    }

    /**
     * Build all the derived fields the receipt PDF needs (department,
     * year, total fee, total paid to date, balance) by reading live
     * from FeeMaster + summing FeePayment rows — nothing is stored
     * redundantly on fee_receipts itself.
     */
    private function buildReceiptSummary(FeeReceipt $receipt): array
    {
        $feePayment = $receipt->feePayment;
        $feeMaster  = $feePayment?->feeMaster;

        $totalFee = (float) ($feeMaster->amount ?? 0);

        $totalPaidToDate = 0.0;

        if ($feePayment) {
            $totalPaidToDate = (float) FeePayment::where('student_id', $feePayment->student_id)
                ->where('fee_master_id', $feePayment->fee_master_id)
                ->sum('amount_paid');
        }

        $balance = max($totalFee - $totalPaidToDate, 0);

        return [
            'department'          => $feeMaster->department ?? null,
            'year'                => $feeMaster->year ?? null,
            'total_fee'           => $totalFee,
            'paid_this_time'      => (float) $receipt->amount,
            'total_paid'          => $totalPaidToDate,
            'balance'             => $balance,
            'payment_mode'        => $feePayment->payment_mode ?? null,
            
            'student_college_id'  => $receipt->student->student_id ?? null,
        ];
    }

    /**
     * Download receipt PDF
     */
    public function download($id)
    {
        $receipt = FeeReceipt::with(['student', 'feePayment.feeMaster'])->findOrFail($id);

        $summary = $this->buildReceiptSummary($receipt);

        $pdf = Pdf::loadView('fee-receipts.pdf', [
            'receipt' => $receipt,
            'summary' => $summary,
        ])->setPaper('a4');

        return $pdf->download('Receipt-' . $receipt->receipt_number . '.pdf');
    }
}