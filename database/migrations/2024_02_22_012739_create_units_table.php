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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('services_id');
            $table->foreign('services_id')
            ->references('id')
            ->on('services');
            $table->string('unit_name');
            $table->string('unit_url')->nullable();
            $table->boolean('is_disabled')->default(0);
            $table->foreignId('pstc_id')->nullable();
            // $table->foreignId('hrdc_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
