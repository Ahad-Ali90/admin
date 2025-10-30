<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop lead tracking columns (keep lead_source for platform tracking)
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['lead_status', 'lead_notes', 'inquiry_date', 'conversion_date']);
        });

        // Update status enum to include 'not_converted'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'not_converted') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('lead_status', ['inquiry', 'quoted', 'converted', 'not_converted', 'follow_up'])->default('inquiry')->after('lead_source');
            $table->text('lead_notes')->nullable()->after('lead_status');
            $table->timestamp('inquiry_date')->nullable()->after('lead_notes');
            $table->timestamp('conversion_date')->nullable()->after('inquiry_date');
        });

        // Restore original status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
