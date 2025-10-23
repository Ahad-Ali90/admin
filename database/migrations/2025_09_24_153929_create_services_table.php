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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->decimal('fixed_rate', 8, 2)->nullable();
            $table->enum('pricing_type', ['hourly', 'fixed', 'per_item'])->default('hourly');
            $table->string('unit')->nullable(); // per hour, per item, per mile, etc.
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
