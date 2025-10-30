<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Expand enum to include both old and new values
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'to_do', 'in_progress', 'completed', 'on_hold', 'cancelled') DEFAULT 'pending'");
        
        // Step 2: Update existing data to new values
        DB::table('tasks')->where('status', 'pending')->update(['status' => 'to_do']);
        DB::table('tasks')->where('status', 'on_hold')->update(['status' => 'to_do']);
        DB::table('tasks')->where('status', 'cancelled')->update(['status' => 'to_do']);
        
        // Step 3: Remove old enum values, keep only new ones
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('to_do', 'in_progress', 'completed') DEFAULT 'to_do'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'on_hold', 'cancelled') DEFAULT 'pending'");
    }
};
