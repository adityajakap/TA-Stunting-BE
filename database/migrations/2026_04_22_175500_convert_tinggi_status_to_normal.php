<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConvertTinggiStatusToNormal extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration will update existing detection records that have status 'Tinggi'
     * to the new normalized status 'Normal'.
     */
    public function up()
    {
        DB::table('detections')->where('status', 'Tinggi')->update(['status' => 'Normal']);
    }

    /**
     * Reverse the migrations.
     *
     * We intentionally leave down() as a no-op because reverting would risk converting
     * legitimate 'Normal' values back to 'Tinggi'. If you need a revert, please
     * run a custom query with a safe condition.
     */
    public function down()
    {
        // no-op
    }
}
