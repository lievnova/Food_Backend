<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('combined_ingredients', function (Blueprint $table) {
            // Change combined_ingredients_id to auto-increment (bigIncrements)
            $table->bigIncrements('food_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('combined_ingredients', function (Blueprint $table) {
            // Revert the change if needed
            $table->bigInteger('food_id')->change();
        });
    }
};
