<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrs', function (Blueprint $table) {
            $table->id();
            $table->integer('somatization');
            $table->integer('obsession');
            $table->integer('interpersonal_sensitivity');
            $table->integer('depression');
            $table->integer('anxiety');
            $table->integer('hostility');
            $table->integer('phobic_anxiety');
            $table->integer('paranoid_ideation');
            $table->integer('psychoticism');
            $table->integer('igs');
            $table->integer('sp');
            $table->integer('psdi');
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
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
        Schema::dropIfExists('scrs');
    }
}
