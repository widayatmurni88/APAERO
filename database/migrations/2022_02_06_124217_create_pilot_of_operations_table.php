<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotOfOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_of_operations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('hours_operation')->default(0);
            $table->uuid('biodata_id')->index();
            $table->unsignedBigInteger('type_of_operation_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pilot_of_operations');
    }
}
