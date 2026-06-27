<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_receipts', function (Blueprint $table) {

            $table->foreignId('fee_payment_id')
                  ->nullable()
                  ->after('student_id')
                  ->constrained()
                  ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('fee_receipts', function (Blueprint $table) {

            $table->dropForeign(['fee_payment_id']);
            $table->dropColumn('fee_payment_id');

        });
    }
};