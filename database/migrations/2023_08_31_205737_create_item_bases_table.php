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
        Schema::create('item_bases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // katana, longsword, rapier, dagger, etc.
            $table->string('type'); // weapon, armor, jewelry, potion
            $table->string('slot'); // main hand, off hand, helmet, ring, etc.
            $table->integer('dice'); // max damage per dice
            $table->integer('many_dices'); // number of dices
            $table->integer('flat'); // flat damage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_bases');
    }
};
