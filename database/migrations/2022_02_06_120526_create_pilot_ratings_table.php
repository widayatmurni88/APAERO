<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('biodata_id')->index();
            $table->unsignedBigInteger('air_craft_id');
            $table->date('last_validity');
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
        Schema::dropIfExists('pilot_ratings');
    }
}
