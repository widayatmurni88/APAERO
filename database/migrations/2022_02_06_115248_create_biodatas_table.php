<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiodatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biodatas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',100);
            $table->string('place_birth', 100)->nullable();
            $table->date('date_birth')->nullable();
            $table->boolean('marrid')->default(false);
            $table->string('img_av')->default('person.jpg');
            $table->string('last_position')->default('NONE')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->uuid('user_id')->index();
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
        Schema::dropIfExists('biodatas');
    }
}
