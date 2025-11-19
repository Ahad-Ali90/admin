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
        Schema::create('color_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // CSS variable key like 'primary-color', 'button-bg', etc.
            $table->string('value'); // Color value like '#4f46e5', 'rgb(79, 70, 229)', etc.
            $table->string('category'); // Category: buttons, cards, tables, inputs, text, etc.
            $table->text('description')->nullable(); // Description of what this color is for
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_schemes');
    }
};
