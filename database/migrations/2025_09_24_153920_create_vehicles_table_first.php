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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->enum('vehicle_type', ['van', 'truck', 'lorry', 'car'])->default('van');
            $table->string('color')->nullable();
            $table->integer('capacity_cubic_meters')->nullable();
            $table->integer('max_weight_kg')->nullable();
            
            // Status and Maintenance
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->date('mot_expiry_date');
            $table->date('insurance_expiry_date');
            $table->date('last_service_date')->nullable();
            $table->date('next_service_due')->nullable();
            $table->integer('mileage')->default(0);
            
            // Costs
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('monthly_insurance', 10, 2)->nullable();
            $table->decimal('monthly_finance', 10, 2)->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
