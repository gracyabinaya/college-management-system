<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use Illuminate\Http\Request;

class FeePaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index()
    {
        $payments = FeePayment::all();
        return view('fee-payments.index', compact('payments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('fee-payments.create');
    }

    /**
     * Store payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'fee_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_mode' => 'nullable|string|max:50',
            'status' => 'required|in:Paid,Pending',
            'payment_date' => 'nullable|date',
        ]);

        // Remove payment date if not paid
        if ($validated['status'] !== 'Paid') {
            $validated['payment_date'] = null;
        }

        FeePayment::create($validated);

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Show single payment
     */
    public function show(FeePayment $feePayment)
    {
        return view('fee-payments.show', compact('feePayment'));
    }

    /**
     * Show edit form
     */
    public function edit(FeePayment $feePayment)
    {
        return view('fee-payments.edit', compact('feePayment'));
    }

    /**
     * Update payment
     */
   public function update(Request $request, FeePayment $feePayment)
{

    $validated = $request->validate([
        'student_name' => 'required|string|max:255',
        'fee_name' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'payment_mode' => 'required|string|max:50',
        'status' => 'required|in:Paid,Pending',
        'payment_date' => 'nullable|date',
    ]);

    if ($validated['status'] !== 'Paid') {
        $validated['payment_date'] = null;
    }

    dd($request->all());
    
    $feePayment->update($validated);

    return redirect()
        ->route('fee-payments.index')
        ->with('success', 'Payment updated successfully.');
}

    /**
     * Delete payment
     */
    public function destroy(FeePayment $feePayment)
    {
        $feePayment->delete();

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Pending payments
     */
    public function pending()
    {
        $payments = FeePayment::where('status', 'Pending')->get();

        return view('fee-payments.pending', compact('payments'));
    }

    /**
     * Paid payments
     */
    public function paid()
    {
        $payments = FeePayment::where('status', 'Paid')->get();

        return view('fee-payments.paid', compact('payments'));
    }
}