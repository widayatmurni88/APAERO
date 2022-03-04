<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_experiences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('hours_flight')->default(0);
            $table->uuid('biodata_id')->index();
            $table->unsignedBigInteger('air_craf_id');
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
        Schema::dropIfExists('flight_experiences');
    }
}
