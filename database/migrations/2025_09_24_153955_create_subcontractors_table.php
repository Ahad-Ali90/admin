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
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('manager_name');
            $table->string('owner_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('website')->nullable();
            $table->text('address');
            $table->string('postcode');
            $table->string('city');
            
            // Business Details
            $table->string('business_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->integer('years_in_business')->nullable();
            $table->integer('fleet_size')->nullable();
            
            // Verification Status
            $table->boolean('insurance_verified')->default(false);
            $table->date('insurance_expiry_date')->nullable();
            $table->boolean('licenses_verified')->default(false);
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            
            // Service Areas
            $table->json('service_areas')->nullable(); // Array of postcodes/areas
            $table->json('services_offered')->nullable(); // Array of services
            
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
