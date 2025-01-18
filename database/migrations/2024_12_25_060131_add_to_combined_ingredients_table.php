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
        Schema::table('combined_ingredients', function (Blueprint $table) {
            $table->json('ingredient_id')->change(); // Change type to JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combined_ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredient_id')->change(); // Revert to original type
        });
    }
};
