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
            // Company information fields - only add if they don't exist
            if (!Schema::hasColumn('bookings', 'company_name')) {
                $table->string('company_name')->nullable()->after('is_company_booking');
            }
            if (!Schema::hasColumn('bookings', 'company_phone')) {
                $table->string('company_phone')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('bookings', 'company_commission_amount')) {
                $table->decimal('company_commission_amount', 10, 2)->default(0)->after('company_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'company_phone', 'company_commission_amount']);
        });
    }
};
