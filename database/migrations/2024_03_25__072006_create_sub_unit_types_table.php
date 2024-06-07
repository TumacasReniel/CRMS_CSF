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
        Schema::create('sub_unit_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_unit_id');
            $table->foreign('sub_unit_id')
                    ->references('id')
                    ->on('sub_units');
            $table->foreignId('region_id');
            $table->foreign('region_id')
                    ->references('id')
                    ->on('regions');
            $table->string('type_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_unit_types');
    }
};
