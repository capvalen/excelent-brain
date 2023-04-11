<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('millons', function (Blueprint $table) {
            $table->id();
            $table->integer('schizoid');
            $table->integer('avoidant');
            $table->integer('depressant');
            $table->integer('dependent');
            $table->integer('histrionic');
            $table->integer('narcissistic');
            $table->integer('antisocial');
            $table->integer('agressive_sadistic');
            $table->integer('compulsive');
            $table->integer('naysayer');
            $table->integer('self_destructive');
            $table->integer('schizotypal');
            $table->integer('limit');
            $table->integer('paranoid');
            $table->integer('anxiety_disorder');
            $table->integer('somatoform_disorder');
            $table->integer('bipolar_disorder');
            $table->integer('dysthymic_disorder');
            $table->integer('alcohol_dependence');
            $table->integer('substance_dependence');
            $table->integer('post_traumatic_stress_disorder');
            $table->integer('thought_disorder');
            $table->integer('major_depression');
            $table->integer('delusional_disorder');
            $table->integer('sincerity');
            $table->integer('social_desirability');
            $table->integer('devaluation');
            $table->tinyInteger('validaity');
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
        Schema::dropIfExists('millons');
    }
}
