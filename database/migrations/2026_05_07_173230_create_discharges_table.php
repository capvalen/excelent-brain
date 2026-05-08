<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDischargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discharges', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->unsigned();
            $table->integer('professional_id')->unsigned()->nullable();
            $table->tinyInteger('type')->comment('1: Psicológica, 2: Psiquiátrica');
            $table->text('comments')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Activo, 2: Inactivo, 3: Reingreso');
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
        Schema::dropIfExists('discharges');
    }
}
