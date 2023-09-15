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
        Schema::create('player_equipments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('item_base_id');
            $table->string('slot'); // Where is the item equipped: main hand, off hand, etc.
            $table->unsignedBigInteger('modifier_id')->nullable(); // Optional, if the item has a modifier

            // Foreign keys
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('item_base_id')->references('id')->on('item_bases');
            $table->foreign('modifier_id')->references('id')->on('modifiers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_equipments');
    }
};
