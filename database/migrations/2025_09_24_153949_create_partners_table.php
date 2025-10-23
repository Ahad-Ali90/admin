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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('postcode')->nullable();
            $table->enum('fleet_type', ['logistics', 'removals', 'storage', 'other'])->default('logistics');
            $table->integer('fleet_size')->nullable();
            
            // Verification Status
            $table->boolean('insurance_verified')->default(false);
            $table->date('insurance_expiry_date')->nullable();
            $table->boolean('registration_verified')->default(false);
            $table->string('registration_number')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            
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
        Schema::dropIfExists('partners');
    }
};
