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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('account_type')->default('user');
            $table->string('designation')->nullable();
            $table->foreignId('region_id')->nullable();
            $table->foreign('region_id')
                    ->references('id')
                    ->on('regions');
            $table->foreignId('service_id')->nullable();
            $table->foreign('service_id')
                ->references('id')
                ->on('services');
            $table->foreignId('unit_id')->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('is_active')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
