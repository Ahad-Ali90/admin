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
            // Add fields for multiple porters
            $table->json('porter_ids')->nullable()->after('porter_id');
            
            // Add company commission fields
            $table->boolean('is_company_booking')->default(false)->after('total_amount');
            $table->decimal('company_commission_rate', 5, 2)->nullable()->after('is_company_booking');
            $table->decimal('company_commission_amount', 10, 2)->nullable()->after('company_commission_rate');
            
            // Add extra hours tracking
            $table->integer('extra_hours')->nullable()->after('actual_hours');
            $table->decimal('extra_hours_rate', 8, 2)->nullable()->after('extra_hours');
            $table->decimal('extra_hours_amount', 10, 2)->nullable()->after('extra_hours_rate');
            
            // Add manual amount override
            $table->boolean('is_manual_amount')->default(false)->after('extra_hours_amount');
            $table->decimal('manual_amount', 10, 2)->nullable()->after('is_manual_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'porter_ids',
                'is_company_booking',
                'company_commission_rate',
                'company_commission_amount',
                'extra_hours',
                'extra_hours_rate',
                'extra_hours_amount',
                'is_manual_amount',
                'manual_amount'
            ]);
        });
    }
};
