<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega numero_cuota a deudas y extra_payments, y cuota_dividida a membresias.
     */
    public function up()
    {
        Schema::table('deudas', function (Blueprint $table) {
            $table->unsignedTinyInteger('numero_cuota')->nullable()->after('idPago');
        });

        Schema::table('extra_payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('numero_cuota')->nullable()->after('correlativo');
        });

        Schema::table('membresias', function (Blueprint $table) {
            $table->boolean('cuota_dividida')->default(false)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('deudas', function (Blueprint $table) {
            $table->dropColumn('numero_cuota');
        });

        Schema::table('extra_payments', function (Blueprint $table) {
            $table->dropColumn('numero_cuota');
        });

        Schema::table('membresias', function (Blueprint $table) {
            $table->dropColumn('cuota_dividida');
        });
    }
};
