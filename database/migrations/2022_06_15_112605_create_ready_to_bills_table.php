<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadyToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ready_to_bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id');
            $table->text('device_ids');
            $table->date('from');
            $table->date('to');
            $table->string('code', 255)->nullable();
            $table->decimal('paid', 10, 2)->default(0.00);
            $table->decimal('charges', 11, 2)->default(0.00);
            $table->string('status', 20)->default('ready');
            $table->string('payment_status', 7)->default('unpaid');
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
        Schema::dropIfExists('ready_to_bills');
    }
}
