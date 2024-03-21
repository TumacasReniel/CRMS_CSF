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
        Schema::create('unit_sub_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id');
            $table->foreign('unit_id')
                    ->references('id')
                    ->on('units');
            $table->foreignId('sub_unit_id');
            $table->foreign('sub_unit_id')
                    ->references('id')
                    ->on('sub_units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_sub_units');
    }
};
