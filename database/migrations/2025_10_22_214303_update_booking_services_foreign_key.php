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
        Schema::table('booking_services', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['service_id']);
            
            // Add new foreign key constraint to service_types table
            $table->foreign('service_id')->references('id')->on('service_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_services', function (Blueprint $table) {
            // Drop the service_types foreign key
            $table->dropForeign(['service_id']);
            
            // Restore the original foreign key to services table
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }
};
