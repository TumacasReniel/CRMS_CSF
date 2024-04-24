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
        Schema::create('pstos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id');
            $table->foreign('region_id')
                    ->references('id')
                    ->on('regions');
            $table->string('psto_name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pstos');
    }
};
