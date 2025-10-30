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
        Schema::create('booking_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('expense_type', [
                'driver_payment',
                'porter_payment', 
                'congestion_charge',
                'ulez_charge',
                'toll_charge',
                'extra_waiting_time',
                'fuel',
                'parking',
                'other'
            ]);
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->foreignId('paid_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->date('expense_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_expenses');
    }
};
