<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- Step 1: add the new columns we need, nullable for now -----
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->foreignId('fee_master_id')
                ->nullable()
                ->after('student_id')
                ->constrained('fee_masters')
                ->nullOnDelete();

            
        });

        // --- Step 2: best-effort backfill of fee_master_id from fee_name
        // existing rows store fee_name as free text; match it to fee_masters
        // by name so we don't orphan historical payments. Anything that
        // doesn't match is left null and should be reviewed manually.
        $rows = DB::table('fee_payments')->select('id', 'fee_name')->get();

        foreach ($rows as $row) {
            $match = DB::table('fee_masters')
                ->where('fee_name', $row->fee_name)
                ->first();

            if ($match) {
                DB::table('fee_payments')
                    ->where('id', $row->id)
                    ->update(['fee_master_id' => $match->id]);
            }
        }

        // --- Step 3: rename amount -> amount_paid (this is now ONE
        // installment's amount, not the full fee) ----------------------
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->renameColumn('amount', 'amount_paid');
        });

        // --- Step 4: drop columns we no longer store. status/paid/balance
        // are now always computed from fee_masters.amount minus the sum
        // of amount_paid for a student+fee_master_id. ---------------------
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn(['status', 'student_name', 'fee_name']);
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->string('student_name')->nullable();
            $table->string('fee_name')->nullable();
            $table->enum('status', ['Paid', 'Pending'])->nullable();
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->renameColumn('amount_paid', 'amount');
        });

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropForeign(['fee_master_id']);
            $table->dropColumn(['fee_master_id', 'remarks']);
        });
    }
};