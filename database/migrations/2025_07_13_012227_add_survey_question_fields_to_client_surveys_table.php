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
        Schema::table('client_surveys', function (Blueprint $table) {
            // Survey question fields
            $table->enum('toilet_move', ['stay', 'move'])->nullable()->after('home_age_category');
            $table->enum('wall_change', ['yes', 'no'])->nullable()->after('toilet_move');
            $table->enum('include_tiles', ['yes', 'no'])->nullable()->after('wall_change');
            $table->string('property_type')->nullable()->after('include_tiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_surveys', function (Blueprint $table) {
            $table->dropColumn(['toilet_move', 'wall_change', 'include_tiles', 'property_type']);
        });
    }
};
