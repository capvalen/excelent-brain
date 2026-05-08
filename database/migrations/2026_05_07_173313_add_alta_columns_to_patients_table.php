<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAltaColumnsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('patients', 'alta_psicologica')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->boolean('alta_psicologica')->default(0)->after('discharge');
                $table->boolean('alta_psiquiatrica')->default(0)->after('alta_psicologica');
            });
        }

        // Data migration para mover los 160 pacientes dados de alta
        $patients = DB::table('patients')->where('discharge', 1)->get();
        foreach ($patients as $patient) {
            DB::table('patients')->where('id', $patient->id)->update([
                'alta_psicologica' => 1
            ]);

            // Tratar de buscar quién le dio el alta
            $seguimiento = DB::table('pacient_seguimiento')
                ->where('patient_id', $patient->id)
                ->where('idSeguimiento', 7) // Alta
                ->orderBy('registro', 'desc')
                ->first();

            $userId = $seguimiento ? $seguimiento->user_id : null;
            $fecha = $seguimiento ? $seguimiento->registro : $patient->updated_at;

            // Determinar tipo por la profesion si es que el prof existe
            $tipo = 1; // 1: Psicologica
            $actualProfId = null;

            if ($userId) {
                $prof = DB::table('professionals')->where('user_id', $userId)->first();
                if ($prof) {
                    $actualProfId = $prof->id;
                    if (strpos(strtolower($prof->profession), 'psiquiatra') !== false) {
                        $tipo = 2; // Psiquiatrica
                    }
                }
            }

            if ($tipo == 2) {
                DB::table('patients')->where('id', $patient->id)->update([
                    'alta_psicologica' => 0,
                    'alta_psiquiatrica' => 1
                ]);
            }

            DB::table('discharges')->insert([
                'patient_id' => $patient->id,
                'professional_id' => $actualProfId,
                'type' => $tipo, 
                'comments' => 'Alta antigua migrada automáticamente.',
                'status' => 1,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['alta_psicologica', 'alta_psiquiatrica']);
        });
    }
}
