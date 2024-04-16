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
        Schema::create('c_s_f_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers');
            $table->foreignId('region_id');
            $table->foreign('region_id')
                    ->references('id')
                    ->on('regions');
            $table->foreignId('service_id');
            $table->foreign('service_id')
                    ->references('id')
                    ->on('services');
            $table->foreignId('unit_id');
            $table->foreign('unit_id')
                    ->references('id')
                    ->on('units');
            $table->foreignId('sub_unit_id')->nullable();
            $table->foreign('sub_unit_id')
                    ->references('id')
                    ->on('sub_units');
            $table->foreignId('psto_id')->nullable();
            $table->foreign('psto_id')
                    ->references('id')
                    ->on('pstos');
            $table->string('client_type')->nullable();
            $table->string('sub_unit_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_s_f_forms');
    }
};
