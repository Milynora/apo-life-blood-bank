<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');

            $table->unsignedBigInteger('hospital_id');
            $table->foreign('hospital_id')
                  ->references('hospital_id')
                  ->on('hospitals')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('blood_type_id');
            $table->foreign('blood_type_id')
                  ->references('blood_type_id')
                  ->on('blood_types');

            $table->unsignedInteger('quantity');
            $table->enum('fulfillment_type', ['pickup', 'delivery'])->default('pickup');
            $table->date('request_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'partially_fulfilled', 'fulfilled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};