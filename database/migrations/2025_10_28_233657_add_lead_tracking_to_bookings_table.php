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
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('lead_source', ['website', 'phone', 'email', 'whatsapp', 'facebook', 'instagram', 'referral', 'walk_in', 'other'])->default('phone')->after('status');
            $table->enum('lead_status', ['inquiry', 'quoted', 'converted', 'not_converted', 'follow_up'])->default('inquiry')->after('lead_source');
            $table->text('lead_notes')->nullable()->after('lead_status');
            $table->timestamp('inquiry_date')->nullable()->after('lead_notes');
            $table->timestamp('conversion_date')->nullable()->after('inquiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['lead_source', 'lead_status', 'lead_notes', 'inquiry_date', 'conversion_date']);
        });
    }
};
