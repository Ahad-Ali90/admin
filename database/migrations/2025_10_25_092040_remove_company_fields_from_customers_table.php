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
        Schema::table('customers', function (Blueprint $table) {
            // Check if columns exist before dropping
            if (Schema::hasColumn('customers', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('customers', 'customer_type')) {
                $table->dropColumn('customer_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Re-add columns if migration is rolled back
            $table->enum('customer_type', ['individual', 'business'])->default('individual')->after('postcode');
            $table->string('company_name')->nullable()->after('customer_type');
        });
    }
};
