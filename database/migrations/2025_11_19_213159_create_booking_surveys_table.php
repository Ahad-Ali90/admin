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
        Schema::create('booking_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('survey_type', ['video_call', 'video_recording', 'list'])->nullable();
            $table->enum('status', ['done', 'pending', 'not_agreed'])->nullable();
            $table->text('list_content')->nullable(); // For list type
            $table->string('video_path')->nullable(); // For video recording storage
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_surveys');
    }
};
