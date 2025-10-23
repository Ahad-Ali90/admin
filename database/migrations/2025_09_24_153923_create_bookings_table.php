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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin or Booking Grabber
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('porter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            
            // Booking Details
            $table->string('booking_reference')->unique();
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->datetime('booking_date');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            
            // Location Details
            $table->text('pickup_address');
            $table->text('delivery_address');
            $table->string('pickup_postcode')->nullable();
            $table->string('delivery_postcode')->nullable();
            
            // Job Details
            $table->text('job_description');
            $table->text('special_instructions')->nullable();
            $table->text('driver_notes')->nullable();
            $table->text('porter_notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
