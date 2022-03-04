<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('biodata_id')->index();
            $table->unsignedBigInteger('certificate_id');
            $table->date('valid_start');
            $table->date('valid_end');
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
        Schema::dropIfExists('pilot_certificates');
    }
}
