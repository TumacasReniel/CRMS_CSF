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
        Schema::create('customer_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('rate_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ratings');
    }
};
