<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 255);
            $table->string('imei', 255);
            $table->string('model_number', 255);
            $table->integer('signal');
            $table->integer('battery');
            $table->bigInteger('clinic_id')->nullable();
            $table->bigInteger('doctor_id')->nullable();
            $table->bigInteger('patient_id')->nullable();
            $table->string('session', 255)->default('end');
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
        Schema::dropIfExists('devices');
    }
}
