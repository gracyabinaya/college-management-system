<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If you have existing data you want to keep, write a data-migration
        // step BEFORE dropping the old table (e.g. copy old `amount` into
        // `amount_paid` on a new table, one row per old payment).
        Schema::dropIfExists('fee_payments');

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->foreignId('fee_master_id')
                ->constrained('fee_masters')
                ->onDelete('cascade');

            // This is ONE installment's amount, not the total fee.
            $table->decimal('amount_paid', 10, 2);

            $table->string('payment_mode');
            $table->date('payment_date')->nullable();
           

            $table->timestamps();

            // Speeds up SUM(amount_paid) per student+fee, used everywhere.
            $table->index(['student_id', 'fee_master_id']);
        });

        // Deliberately NOT adding: paid_amount, balance_amount, status.
        // These are always computed at query time, never stored.
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};