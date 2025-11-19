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
        Schema::table('booking_surveys', function (Blueprint $table) {
            $table->date('schedule_date')->nullable()->after('survey_type');
            $table->time('schedule_time')->nullable()->after('schedule_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_surveys', function (Blueprint $table) {
            $table->dropColumn(['schedule_date', 'schedule_time']);
        });
    }
};
