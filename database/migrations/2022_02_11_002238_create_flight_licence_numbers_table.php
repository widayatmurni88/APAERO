<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightLicenceNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_licence_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->date('valid_start')->nullable();
            $table->date('valid_end')->nullable();
            $table->boolean('active')->default(true);
            $table->uuid('biodata_id')->index();
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
        Schema::dropIfExists('flight_licence_numbers');
    }
}
