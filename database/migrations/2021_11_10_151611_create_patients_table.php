<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('dni',8);
            $table->string('email')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('phone',12)->nullable();
            $table->string('occupation')->nullable();
            $table->tinyInteger('instruction_degree')->nullable();
            $table->tinyInteger('marital_status')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
