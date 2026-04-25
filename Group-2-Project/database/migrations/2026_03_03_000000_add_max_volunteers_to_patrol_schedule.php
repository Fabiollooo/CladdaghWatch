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
        Schema::table('cw_patrol_schedule', function (Blueprint $table) {
            $table->unsignedInteger('maxVolunteers')->nullable()->default(null)->after('patrol_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cw_patrol_schedule', function (Blueprint $table) {
            $table->dropColumn('maxVolunteers');
        });
    }
};
