<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('student_id')->unique();
            $table->string('student_name');

            $table->integer('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();

            $table->string('father_name')->nullable();

            $table->string('last_class_studied')->nullable();
            $table->string('previous_year_grade')->nullable();
            $table->string('last_school_studied')->nullable();

            $table->string('contact_number')->nullable();
            $table->string('alternate_contact_number')->nullable();

            $table->string('aadhaar_number')->nullable();

            $table->string('photo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};