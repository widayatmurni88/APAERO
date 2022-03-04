<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotPassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_passports', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('valid_start');
            $table->date('valid_end');
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
        Schema::dropIfExists('pilot_passports');
    }
}
