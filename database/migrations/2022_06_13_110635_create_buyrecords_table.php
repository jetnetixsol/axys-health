<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyrecords', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('device_id');
            $table->bigInteger('clinic_id')->nullable();
            $table->bigInteger('doctor_id')->nullable();
            $table->decimal('total_price',11,2);
            $table->bigInteger('quantity');
            $table->string('buyer',25);
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
        Schema::dropIfExists('buyrecords');
    }
}
