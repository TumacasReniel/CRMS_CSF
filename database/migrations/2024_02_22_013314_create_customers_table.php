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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('sex');
            $table->string('age_group');
            $table->string('client_type');
            $table->boolean('digital_literacy')->default(0);
            $table->boolean('pwd')->default(0);
            $table->boolean('pregnant')->default(0);
            $table->boolean('senior_citizen')->default(0);
            $table->foreignId('pstc_id')->nullable();
            $table->foreign('pstc_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('unit_id')->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
