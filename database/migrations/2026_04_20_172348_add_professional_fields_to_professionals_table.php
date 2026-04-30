<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfessionalFieldsToProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('especialidad_receta')->nullable()->after('profession');
            $table->string('cmp')->nullable()->after('especialidad_receta');
            $table->string('rne')->nullable()->after('cmp');
            $table->string('cpsp')->nullable()->after('rne');
            $table->string('signature_path')->nullable()->after('signing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            //
        });
    }
}
