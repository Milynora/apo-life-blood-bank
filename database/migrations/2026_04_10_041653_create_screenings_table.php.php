<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id('screening_id');

            $table->foreignId('donor_id')
                  ->constrained('donors', 'donor_id')
                  ->cascadeOnDelete();

            $table->foreignId('staff_id')
                  ->constrained('staff', 'staff_id');

            $table->foreignId('appointment_id')
                  ->nullable()
                  ->constrained('appointments', 'appointment_id')
                  ->nullOnDelete();

            $table->string('blood_pressure', 20)->nullable();
            $table->decimal('hemoglobin_level', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->enum('eligibility_status', ['fit', 'unfit'])->default('fit');
            $table->text('remarks')->nullable();
            $table->date('screening_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};