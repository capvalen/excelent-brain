<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Asigna numero_cuota retroactivamente a registros existentes.
     * Lógica: Para cada membresía, primero numera los pagos (extra_payments)
     * por created_at ASC, luego las deudas pendientes por fecha ASC.
     */
    public function up()
    {
        // Obtener todas las membresías que tienen cuotas > 0
        $membresias = DB::table('membresias')
            ->where('cuotas', '>', 0)
            ->pluck('id');

        foreach ($membresias as $idMembresia) {
            $contador = 1;

            // Primero los pagos ya realizados (ordenados cronológicamente)
            $pagos = DB::table('extra_payments')
                ->where('idMembresia', $idMembresia)
                ->whereNull('numero_cuota')
                ->orderBy('created_at', 'asc')
                ->pluck('id');

            foreach ($pagos as $idPago) {
                DB::table('extra_payments')
                    ->where('id', $idPago)
                    ->update(['numero_cuota' => $contador]);
                $contador++;
            }

            // Luego las deudas pendientes (ordenadas por fecha de pago)
            $deudas = DB::table('deudas')
                ->where('idMembresia', $idMembresia)
                ->where('activo', 1)
                ->whereNull('numero_cuota')
                ->orderBy('fecha', 'asc')
                ->pluck('id');

            foreach ($deudas as $idDeuda) {
                DB::table('deudas')
                    ->where('id', $idDeuda)
                    ->update(['numero_cuota' => $contador]);
                $contador++;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('deudas')->update(['numero_cuota' => null]);
        DB::table('extra_payments')
            ->whereNotNull('idMembresia')
            ->update(['numero_cuota' => null]);
    }
};
