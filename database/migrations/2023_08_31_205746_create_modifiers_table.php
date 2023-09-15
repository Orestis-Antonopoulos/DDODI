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
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('slot'); // Slot where it can be applied: main hand, helmet, etc.
            $table->integer('bonus_health_flat')->nullable();
            $table->integer('bonus_health_percent')->nullable();
            $table->integer('bonus_dice')->nullable();
            $table->integer('bonus_flat')->nullable();
            // Add more columns for other kinds of bonuses or effects
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifiers');
    }
};
