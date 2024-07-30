<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPstoIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('psto_id')->nullable(); // Add the new column
            $table->foreign('psto_id')
            ->references('id')
            ->on('pstos');
            // or if you want to keep the records even if the psto record is deleted, use
            // $table->foreign('psto_id')->references('id')->on('psto')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
