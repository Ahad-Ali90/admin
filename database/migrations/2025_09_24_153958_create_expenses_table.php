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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->enum('category', ['fuel', 'maintenance', 'staff_pay', 'insurance', 'vehicle_finance', 'office', 'marketing', 'other'])->default('other');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('receipt_number')->nullable();
            $table->string('supplier')->nullable();
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'cheque'])->default('card');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
