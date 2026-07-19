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
        Schema::create('skdn_targets', function (Blueprint $table) {
            $table->id();
            $table->string('posyandu_name')->default('Nusa Indah 1');
            $table->string('month'); // e.g., "06" or "Juni"
            $table->string('year');  // e.g., "2026"
            $table->integer('s_value')->default(0); // Jumlah Sasaran
            $table->timestamps();
            
            // Unik per posyandu, bulan, tahun
            $table->unique(['posyandu_name', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skdn_targets');
    }
};
