<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('requests', function (Blueprint $table) {
        $table->enum('urgency', ['routine', 'urgent', 'emergency'])
              ->default('routine')
              ->after('quantity');
    });
}

public function down(): void
{
    Schema::table('requests', function (Blueprint $table) {
        $table->dropColumn('urgency');
    });
}
};
