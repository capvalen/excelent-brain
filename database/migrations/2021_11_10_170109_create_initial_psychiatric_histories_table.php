<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialPsychiatricHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_psychiatric_histories', function (Blueprint $table) {
            $table->id();
            $table->text('general_antecedent')->nullable();
            $table->text('main_signs_symptoms')->nullable();
            $table->text('illness')->nullable();
            $table->text('apc')->nullable();
            $table->text('languaje')->nullable();
            $table->text('thought')->nullable();
            $table->text('affect')->nullable();
            $table->text('percetion')->nullable();
            $table->text('superior_function')->nullable();
            $table->text('abstraction')->nullable();
            $table->text('conscience')->nullable();
            $table->text('insight')->nullable();
            $table->text('diagnostic_problems')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('plan')->nullable();
            $table->unsignedBigInteger('professional_id')->nullable();
            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('set null');
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
        Schema::dropIfExists('initial_psychiatric_histories');
    }
}
