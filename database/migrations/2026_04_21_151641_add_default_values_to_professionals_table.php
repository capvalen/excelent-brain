<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDefaultValuesToProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('professionals')->whereNull('especialidad_receta')->update(['especialidad_receta' => '']);
        DB::table('professionals')->whereNull('cmp')->update(['cmp' => '']);
        DB::table('professionals')->whereNull('rne')->update(['rne' => '']);
        DB::table('professionals')->whereNull('cpsp')->update(['cpsp' => '']);
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
