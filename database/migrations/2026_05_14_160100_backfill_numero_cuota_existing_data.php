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
            $pagos = DB::table('extra_payments')
                ->where('idMembresia', $idMembresia)
                ->whereNull('numero_cuota')
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();

            $deudas = DB::table('deudas')
                ->where('idMembresia', $idMembresia)
                ->where('activo', 1)
                ->whereNull('numero_cuota')
                ->orderBy('fecha', 'asc')
                ->get()
                ->toArray();

            $pagosAsignados = [];
            $deudaPagoMap = [];

            // 1. Asociar deudas con estado=2 a sus extra_payments
            foreach ($deudas as $deuda) {
                if ($deuda->estado == 2 && $deuda->fechaActualiza) {
                    $fechaAct = substr($deuda->fechaActualiza, 0, 10); // YYYY-MM-DD
                    foreach ($pagos as $index => $pago) {
                        if (!in_array($pago->id, $pagosAsignados)) {
                            $fechaPago = substr($pago->created_at, 0, 10);
                            // Criterio principal: mismo usuario y misma fecha
                            if ($pago->user_id == $deuda->idActualiza && $fechaPago == $fechaAct) {
                                $deudaPagoMap[$deuda->id] = $pago->id;
                                $pagosAsignados[] = $pago->id;
                                break;
                            }
                        }
                    }
                    // Si no lo encontramos por usuario y fecha, buscar solo por fecha
                    if (!isset($deudaPagoMap[$deuda->id])) {
                        foreach ($pagos as $index => $pago) {
                            if (!in_array($pago->id, $pagosAsignados)) {
                                $fechaPago = substr($pago->created_at, 0, 10);
                                if ($fechaPago == $fechaAct) {
                                    $deudaPagoMap[$deuda->id] = $pago->id;
                                    $pagosAsignados[] = $pago->id;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            // Los pagos que no fueron asignados son "pagos iniciales" u otros extra_payments
            $pagosIniciales = [];
            foreach ($pagos as $pago) {
                if (!in_array($pago->id, $pagosAsignados)) {
                    $pagosIniciales[] = $pago;
                }
            }

            $contador = 1;

            // Numeramos los pagos iniciales
            foreach ($pagosIniciales as $pago) {
                DB::table('extra_payments')
                    ->where('id', $pago->id)
                    ->update(['numero_cuota' => $contador]);
                $contador++;
            }

            // Y luego secuenciamos las deudas (pendientes y pagadas)
            foreach ($deudas as $deuda) {
                DB::table('deudas')
                    ->where('id', $deuda->id)
                    ->update(['numero_cuota' => $contador]);
                
                // Si esta deuda tiene un pago asociado, le ponemos el mismo numero
                if (isset($deudaPagoMap[$deuda->id])) {
                    DB::table('extra_payments')
                        ->where('id', $deudaPagoMap[$deuda->id])
                        ->update(['numero_cuota' => $contador]);
                }
                
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
