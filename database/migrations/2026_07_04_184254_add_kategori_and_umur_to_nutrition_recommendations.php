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
        Schema::table('nutrition_recommendations', function (Blueprint $table) {
            $table->enum('kategori_stunting', ['Stunting', 'Normal'])->default('Normal')->after('category');
            $table->string('rentang_umur')->default('0-6 Bulan')->after('kategori_stunting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutrition_recommendations', function (Blueprint $table) {
            $table->dropColumn(['kategori_stunting', 'rentang_umur']);
        });
    }
};