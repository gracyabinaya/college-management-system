<?php

namespace App\Http\Controllers;

use App\Models\FeeReceipt;
use Illuminate\Http\Request;

class FeeReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $receipts = FeeReceipt::all();

    return view('fee-receipts.index', compact('receipts'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('fee-receipts.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    FeeReceipt::create([
        'receipt_number' => 'REC-' . time(),
        'student_name' => $request->student_name,
        'fee_name' => $request->fee_name,
        'amount' => $request->amount,
        'payment_date' => $request->payment_date,
    ]);

    return redirect()->route('fee-receipts.index');
}
    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeReceipt $feeReceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeReceipt $feeReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function show(FeeReceipt $feeReceipt)
{
    return view('fee-receipts.show', compact('feeReceipt'));
}

public function destroy(FeeReceipt $feeReceipt)
{
    $feeReceipt->delete();

    return redirect()
        ->route('fee-receipts.index')
        ->with('success', 'Receipt deleted successfully');
}
    

    public function download($id)
{
    return "PDF Download Working";
}
}
