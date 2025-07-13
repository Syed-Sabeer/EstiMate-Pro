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
        Schema::table('builder_pricings', function (Blueprint $table) {
            // Drop the existing enum and recreate it with the new value
            $table->enum('price_type', ['m2', 'linear_meter', 'fixed', 'hourly', 'percentage'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builder_pricings', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('price_type', ['m2', 'linear_meter', 'fixed', 'hourly'])->change();
        });
    }
};
