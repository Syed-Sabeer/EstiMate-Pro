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
        Schema::create('client_surveys', function (Blueprint $table) {
            $table->id();
            // Link to builder/user account
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Client details
            $table->string('client_name');
            $table->string('client_phone');

            // Measurements (either total_area or detailed)
            $table->double('total_area')->nullable();
            $table->double('floor_length')->nullable();
            $table->double('floor_width')->nullable();
            $table->double('wall_height')->nullable();

            // Bathroom type
            $table->string('bathroom_type')->nullable();
            $table->enum('tiling_level', ['Budget', 'Standard', 'Premium']);
            $table->string('design_style')->nullable();
            $table->string('home_age_category')->nullable();

            // Uploaded photos (max 5 image paths stored in JSON)
            $table->json('photos')->nullable();

            // --- INTERNAL USE FIELDS ---

            // Area calculations
            $table->double('calculated_floor_area')->nullable();
            $table->double('calculated_wall_area')->nullable();
            $table->double('calculated_total_area')->nullable();

            // Tiled areas per level
            $table->double('budget_area')->nullable();
            $table->double('standard_area')->nullable();
            $table->double('premium_area')->nullable();

            // Estimate range
            $table->double('base_estimate')->nullable();
            $table->double('high_estimate')->nullable();

            $table->enum('status', [
                'New',
                'Contacted',
                'Site Visit Done',
                'Quote Sent',
                'Quote Accepted',
                'Quote Unsuccessful',
                'Client Not Interested',
                'Client Uncontactable'
            ])->default('New');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_surveys');
    }
};
