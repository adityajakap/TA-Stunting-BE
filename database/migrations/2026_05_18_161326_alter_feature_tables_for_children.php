<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- Detections ---
        if (Schema::hasColumn('detections', 'user_id')) {
            if (!Schema::hasColumn('detections', 'child_id')) {
                Schema::table('detections', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->foreignId('child_id')->nullable()->after('id')->constrained('children')->onDelete('cascade');
                });
            }
            DB::table('detections')->update(['child_id' => DB::raw('user_id')]);
            Schema::table('detections', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // --- Tahapan Perkembangan Data ---
        if (Schema::hasColumn('tahapan_perkembangan_data', 'user_id')) {
            if (!Schema::hasColumn('tahapan_perkembangan_data', 'child_id')) {
                Schema::table('tahapan_perkembangan_data', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->foreignId('child_id')->nullable()->after('id')->constrained('children')->onDelete('cascade');
                });
            }
            DB::table('tahapan_perkembangan_data')->update(['child_id' => DB::raw('user_id')]);
            Schema::table('tahapan_perkembangan_data', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // --- BMI ---
        if (Schema::hasColumn('bmi', 'user_id')) {
            if (!Schema::hasColumn('bmi', 'child_id')) {
                Schema::table('bmi', function (Blueprint $table) {
                    $table->foreignId('child_id')->nullable()->after('id')->constrained('children')->onDelete('cascade');
                });
            }
            DB::table('bmi')->update(['child_id' => DB::raw('user_id')]);
            Schema::table('bmi', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }

    public function down(): void
    {
        // Not necessary for this context as we are just fixing it forward
    }
};
