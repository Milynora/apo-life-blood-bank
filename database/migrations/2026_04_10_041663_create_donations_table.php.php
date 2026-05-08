<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id('donation_id');

            $table->unsignedBigInteger('donor_id');
            $table->foreign('donor_id')
                  ->references('donor_id')
                  ->on('donors');

            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')
                  ->references('staff_id')
                  ->on('staff');

            $table->unsignedBigInteger('screening_id');
            $table->foreign('screening_id')
                  ->references('screening_id')
                  ->on('screenings')->cascadeOnDelete();

            $table->foreignId('blood_type_id')
                  ->nullable()
                  ->constrained('blood_types', 'blood_type_id')
                  ->nullOnDelete();

            $table->date('donation_date');
            $table->decimal('volume', 6, 2)->comment('in mL');
            $table->enum('status', ['successful', 'failed'])->default('successful');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};