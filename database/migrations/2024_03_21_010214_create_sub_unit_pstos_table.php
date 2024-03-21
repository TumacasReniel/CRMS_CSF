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
        Schema::create('sub_unit_pstos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_unit_id');
            $table->foreign('sub_unit_id')
                    ->references('id')
                    ->on('sub_units');
            $table->foreignId('psto_id');
            $table->foreign('psto_id')
                    ->references('id')
                    ->on('pstos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_unit_pstos');
    }
};
