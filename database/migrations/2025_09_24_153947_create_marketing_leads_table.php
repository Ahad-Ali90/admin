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
        Schema::create('marketing_leads', function (Blueprint $table) {
            $table->id();
            $table->date('week_start_date');
            $table->string('platform'); // Facebook, Instagram, Google, etc.
            
            // Social Media Metrics
            $table->integer('followers_start')->default(0);
            $table->integer('followers_end')->default(0);
            $table->integer('followers_growth')->default(0);
            $table->integer('posts_count')->default(0);
            $table->integer('total_engagement')->default(0);
            $table->integer('total_reach')->default(0);
            
            // Lead Generation
            $table->integer('leads_generated')->default(0);
            $table->decimal('ad_spend', 10, 2)->default(0);
            $table->integer('bookings_from_social')->default(0);
            $table->decimal('cost_per_lead', 8, 2)->default(0);
            $table->decimal('cost_per_booking', 8, 2)->default(0);
            
            // Customer Analysis
            $table->integer('new_customers')->default(0);
            $table->integer('repeat_customers')->default(0);
            $table->decimal('repeat_job_percentage', 5, 2)->default(0);
            $table->string('best_performing_channel')->nullable();
            $table->string('customer_source_breakdown')->nullable(); // JSON data
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['week_start_date', 'platform']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_leads');
    }
};
