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
        Schema::table('detections', function (Blueprint $table) {
            if (!Schema::hasColumn('detections', 'added_by')) {
                $table->enum('added_by', ['orangtua', 'kader'])->default('orangtua')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            if (Schema::hasColumn('detections', 'added_by')) {
                $table->dropColumn('added_by');
            }
        });
    }
};
