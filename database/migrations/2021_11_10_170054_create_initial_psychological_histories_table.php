<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialPsychologicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_psychological_histories', function (Blueprint $table) {
            $table->id();
            $table->text('illness')->nullable();
            $table->text('antecedent')->nullable();
            $table->text('dynamic')->nullable();
            $table->text('attitude')->nullable();
            $table->text('dx')->nullable();
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
        Schema::dropIfExists('initial_psychological_histories');
    }
}
