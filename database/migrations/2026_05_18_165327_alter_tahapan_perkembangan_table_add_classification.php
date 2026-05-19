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
        Schema::table('tahapan_perkembangan', function (Blueprint $table) {
            $table->string('kategori')->after('id')->nullable();
            $table->integer('batas_evaluasi_bulan')->after('umur_maksimal_bulan')->nullable();
            $table->text('sumber_referensi')->after('batas_evaluasi_bulan')->nullable();
            $table->text('catatan')->after('sumber_referensi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahapan_perkembangan', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'batas_evaluasi_bulan', 'sumber_referensi', 'catatan']);
        });
    }
};
