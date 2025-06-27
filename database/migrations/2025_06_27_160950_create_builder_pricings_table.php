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
        Schema::create('builder_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('item_name');
            $table->string('applicability');
            // $table->enum('applicability', ['All Estimates', 'Bathroom Only', 'Floor Only']);
            $table->enum('price_type', ['m2', 'fixed']);
            $table->double('base_price')->nullable();
            $table->double('markup_percent')->nullable();
            $table->double('final_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builder_pricings');
    }
};
