<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            // $table->id();
            $table->bigInteger('user_id');
            $table->string('address', 255);
            $table->string('manager_name', 255);
            $table->string('mobile_number', 100);
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->decimal('wallet_amount', 11, 2)->default(0.00);
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
        Schema::dropIfExists('clinics');
    }
}
