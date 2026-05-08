<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveSlotToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * Agrega columna 'active_slot' e índice único para prevenir doble registro.
     * active_slot = 1 para citas activas (status 1,2), NULL para inactivas.
     * MySQL ignora NULLs en índices únicos, por lo que solo las activas se validan.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->tinyInteger('active_slot')->nullable()->after('status');
        });

        // Los registros existentes quedan con active_slot=NULL (no afectados por el índice único).
        // Solo las citas nuevas creadas a partir de ahora tendrán active_slot=1.

        Schema::table('appointments', function (Blueprint $table) {
            $table->unique(['schedule_id', 'date', 'active_slot'], 'unique_active_schedule_slot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('unique_active_schedule_slot');
            $table->dropColumn('active_slot');
        });
    }
}
