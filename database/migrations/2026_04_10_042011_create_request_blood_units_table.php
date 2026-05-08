<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_blood_units', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')
                  ->references('request_id')
                  ->on('requests')
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('blood_unit_id');
            $table->foreign('blood_unit_id')
                  ->references('blood_unit_id')
                  ->on('blood_units')
                  ->cascadeOnDelete();

            $table->primary(['request_id', 'blood_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_blood_units');
    }
};