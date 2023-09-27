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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->string('slot');  // Slot where it can be applied: main hand, helmet, etc.
            $table->string('item_type');  // sword, blunt, etc.
            $table->string('item_name');
            $table->string('item_description');
            $table->json('special_ability_1')->nullable();
            $table->json('special_ability_2')->nullable();
            $table->json('special_ability_3')->nullable();
            // Add more columns for other kinds of bonuses or effects
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
