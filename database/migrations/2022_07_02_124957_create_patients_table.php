<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn', 225);
            $table->string('full_name', 100);
            $table->string('mobile_number', 100);
            $table->string('email', 255)->nullable();
            $table->date('dob');
            $table->bigInteger('clinic_id');
            $table->bigInteger('doctor_id');
            $table->integer('sys_bp')->nullable();
            $table->integer('dia_bp')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->text('remarks')->nullable();
            $table->string('session')->default('start');
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
        Schema::dropIfExists('patients');
    }
}
