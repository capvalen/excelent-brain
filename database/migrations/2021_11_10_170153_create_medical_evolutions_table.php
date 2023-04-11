<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalEvolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_evolutions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->nullable();
            $table->date('date')->nullable();
            $table->time('schedule')->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('auth')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger('professional_id')->nullable();
            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('set null');
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
        Schema::dropIfExists('medical_evolutions');
    }
}
