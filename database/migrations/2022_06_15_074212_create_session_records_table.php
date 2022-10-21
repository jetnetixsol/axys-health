<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSessionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_records', function (Blueprint $table) {
            $table->id();
            $table->string('device_id',255);
            $table->bigInteger('patient_id');
            $table->string('weight',255)->nullable();
            $table->string('bodymass_index',255)->nullable();
            $table->string('fat',255)->nullable();
            $table->string('basal_matabolic_rate',255)->nullable();
            $table->string('muscle',255)->nullable();
            $table->string('irregular_heartbeat',255)->nullable();
            $table->decimal('pulse_rate',11,2)->nullable();
            $table->string('ovit',255)->nullable();
            $table->decimal('systolic',11,2)->nullable();
            $table->decimal('diastolic',11,2)->nullable();
            $table->string('ops',255)->nullable();
            $table->string('ts',255)->nullable();
            $table->date('date')->nullable();
            $table->string('status',20)->default('active');
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
        Schema::dropIfExists('session_records');
    }
}
