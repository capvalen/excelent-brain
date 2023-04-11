<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKairoPrescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kairo_prescription', function (Blueprint $table) {
            $table->id();
            $table->string('amount')->nullable();
            $table->string('dose')->nullable();
            $table->string('way')->nullable();
            $table->text('indications')->nullable();
            $table->unsignedBigInteger('kairo_id');
            $table->foreign('kairo_id')->references('id')->on('kairos')->onDelete('cascade');
            $table->unsignedBigInteger('prescription_id');
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
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
        Schema::dropIfExists('kairo_prescription');
    }
}
