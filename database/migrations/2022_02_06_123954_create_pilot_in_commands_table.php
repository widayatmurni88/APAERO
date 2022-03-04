<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotInCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_in_commands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('hours_flight')->default(0);
            $table->uuid('biodata_id')->index();
            $table->unsignedBigInteger('air_craft_id');
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
        Schema::dropIfExists('pilot_in_commands');
    }
}
