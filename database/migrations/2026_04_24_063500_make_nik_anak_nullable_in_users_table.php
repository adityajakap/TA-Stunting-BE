<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make nik_anak nullable so registration without NIK works
        if (Schema::hasColumn('users', 'nik_anak')) {
            Schema::table('users', function (Blueprint $table) {
                // use change() to alter column to nullable
                $table->string('nik_anak')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'nik_anak')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nik_anak')->nullable(false)->change();
            });
        }
    }
};
