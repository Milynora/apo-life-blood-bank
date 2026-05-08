<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id('blood_unit_id');

            $table->unsignedBigInteger('donation_id')->nullable();
$table->foreign('donation_id')
      ->references('donation_id')
      ->on('donations')
      ->nullOnDelete();

            $table->unsignedBigInteger('blood_type_id');
            $table->foreign('blood_type_id')
                  ->references('blood_type_id')
                  ->on('blood_types');

            $table->date('stored_date');
            $table->date('expiry_date');
            $table->enum('status', ['available', 'reserved', 'used', 'expired'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_units');
    }
};