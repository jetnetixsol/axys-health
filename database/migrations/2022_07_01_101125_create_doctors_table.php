<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            // $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('clinic_id');
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('mobile_number', 100);
            $table->string('speciality', 255);
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
        Schema::dropIfExists('doctors');
    }
}
