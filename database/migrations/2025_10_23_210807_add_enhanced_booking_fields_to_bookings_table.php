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
            // Basic booking information
            $table->date('start_date')->nullable()->after('booking_date');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('source')->nullable()->after('end_date'); // Where booking came from
            $table->string('contact_no')->nullable()->after('source');
            $table->string('email')->nullable()->after('contact_no');
            $table->text('via_address')->nullable()->after('delivery_address'); // Via address
            
            // Booking type and pricing
            $table->enum('booking_type', ['fixed', 'hourly'])->default('fixed')->after('via_address');
            $table->integer('booked_hours')->nullable()->after('booking_type'); // For fixed bookings
            $table->integer('helpers_count')->default(1)->after('booked_hours');
            $table->decimal('deposit', 10, 2)->default(0)->after('helpers_count');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('deposit');
            $table->text('details_shared_with_customer')->nullable()->after('hourly_rate');
            $table->boolean('booking_confirmation_sent')->default(false)->after('details_shared_with_customer');
            $table->decimal('total_fare', 10, 2)->default(0)->after('booking_confirmation_sent');
            $table->integer('total_completion_time_hours')->nullable()->after('total_fare');
            $table->decimal('total_earning_inc_deposit', 10, 2)->default(0)->after('total_completion_time_hours');
            $table->decimal('ulez_mileage_charges', 10, 2)->default(0)->after('total_earning_inc_deposit');
            $table->decimal('porter_cost', 10, 2)->default(0)->after('ulez_mileage_charges');
            
            // Payment information
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer'])->nullable()->after('porter_cost');
            $table->decimal('remaining_amount', 10, 2)->default(0)->after('payment_method');
            $table->decimal('discount', 10, 2)->default(0)->after('remaining_amount');
            $table->text('discount_reason')->nullable()->after('discount');
            $table->text('notes')->nullable()->after('discount_reason');
            $table->string('review_link')->nullable()->after('notes');
            $table->date('week_start')->nullable()->after('review_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'start_date', 'end_date', 'source', 'contact_no', 'email', 'via_address',
                'booking_type', 'booked_hours', 'helpers_count', 'deposit', 'hourly_rate',
                'details_shared_with_customer', 'booking_confirmation_sent', 'total_fare',
                'total_completion_time_hours', 'total_earning_inc_deposit', 'ulez_mileage_charges',
                'porter_cost', 'payment_method', 'remaining_amount', 'discount', 'discount_reason',
                'notes', 'review_link', 'week_start'
            ]);
        });
    }
};
