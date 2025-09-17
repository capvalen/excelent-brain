<?php

namespace App\Http\Controllers;

use App\Models\Extra_payment;
use App\Models\Medical_comment;
use App\Models\Patient;
use App\Models\Nutricion;
use Illuminate\Support\Facades\DB;
use App\Models\Payment_method;
use App\Models\Professional;
use App\Models\Schedule;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SimpleController extends Controller
{
	public function listarMonedas(){
		$monedas = Payment_method::where('activo',1)
		->orderBy('tipo', 'asc')
		->get();
		return $monedas;
	}

	public function cambiarMuerte(Request $request){
		//return var_dump($request->input('persona.vivo')==1); die();
		$persona = Patient::where( 'id',$request->input('persona.id'));
		$persona->update([
			'vivo' => ($request->input('persona.vivo') == 1) ? 0 : 1
		]);
		return json_encode(array('mensaje' => 'Actualizado'));
	}

	public function cambiarPersonaActivo(Request $request){
		//return var_dump($request->input('persona.vivo')); die();
		$persona = Patient::where( 'id',$request->input('persona.id'));
		$persona->update([
			'activo' => ($request->input('persona.activo') == 1) ? 0 : 1
		]);
		return json_encode(array('mensaje' => 'Actualizado'));
	}

	public function guardarSeguimiento(Request $request){
		$comentario = new Medical_comment;
		$comentario->idMedical = $request->get('idEvolucion');
		$comentario->professional_id = $request->get('idProfesional');
		$comentario->comment = $request->get('seguimiento');
		$comentario->save();
		return json_encode(array('mensaje' => 'Registrado', 'id' => $comentario->id));
	}

	public function pedirResultadosExamen(Request $request){
		switch ($request->get('examen')) {
			case 'scr': $respuesta = DB::table('scrs')->where('id',$request->get('id'))->first(); break;
			case 'burns': $respuesta = DB::table('burns')->where('id',$request->get('id'))->first(); break;
			default: break;
		}
		return $respuesta;
	}

	public function buscarDni($dni){

		$token = env('RENIEC_TOKEN');
		$url = "https://dniruc.apisperu.com/api/v1/dni/" . urlencode($dni).'?token='.$token;
				
		$curl = curl_init();
		// Configurar opciones de cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Devuelve el resultado como string
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);          // Tiempo máximo de espera
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Opcional: desactivar verificación SSL (no recomendado en producción)

		$response = curl_exec($curl);

		curl_close($curl);
		// Datos listos para usar
		$persona = json_decode($response, true);
		//var_dump($persona);
		if( !isset($persona['message']) )
			return json_encode( array('apellido_paterno' => $persona['apellidoPaterno'], 'apellido_materno' => $persona['apellidoMaterno'], 'nombres' => $persona['nombres']) );
		else
			return array();
	}

	public function crearNutricionPrimera(Request $request){
		$nutricion = new Nutricion;
		$nutricion -> idPaciente = $request->get('idPaciente');
		$nutricion -> peso = $request->get('peso');
		$nutricion -> talla = $request->get('talla');
		$nutricion -> imc = $request->get('imc');
		$nutricion -> perimetro = $request->get('perimetro');
		$nutricion -> grasa = $request->get('grasa');
		$nutricion -> comidas = $request->get('comidas');
		$nutricion -> intolerancia = $request->get('intolerancia');
		$nutricion -> alergias = $request->get('alergias');
		$nutricion -> fuma = $request->get('fuma');
		$nutricion -> bebe = $request->get('bebe');
		$nutricion -> signos = $request->get('signos');
		$nutricion -> diagnostico = $request->get('diagnostico');
		$nutricion -> dieta = $request->get('dieta');
		$nutricion -> suplemento = $request->get('suplemento');
		$nutricion->save();

		return json_encode(array('mensaje' => 'Registrado', 'id' => $nutricion->id));
	}

	public function listarNutriciones(Request $request){
		$nutriciones = Nutricion::where('idPaciente', $request->get('idPaciente'))
		->where('activo', 1)
		->get();
		return $nutriciones;
	}

	public function reportsJimmy(Request $request){
		//var_dump($request->input('reporte')); die();
		$fecha = $request->input('fecha');
		$sede = $request->input('sede');

		$carbonFecha = Carbon::parse($fecha);
		$diaSemana = $carbonFecha->locale('es')->dayName; // "lunes"
		$diaSemana = str_replace('miércoles', 'miercoles', $diaSemana);
		$diaSemana = str_replace('sábado', 'sabado', $diaSemana);
				
		$sqlCitas = "SELECT 
		schedules.day AS DÍA, 
		schedules.check_time AS HORARIO,  
		professionals.name AS PROFESIONAL, 
		COALESCE(
				CASE 
						WHEN appointments.status = 2 THEN concat(patients.name, ' ' ,patients.nombres)
						ELSE '-' 
				END, 
				'-'
		) AS PACIENTE,
		CASE 
				WHEN appointments.status = 2 THEN appointments.patient_condition
				ELSE '-' 
		END AS TIPO_PACIENTE,
		-- payment_method.tipo as MONEDA,
		CASE 
				WHEN appointments.status = 2 THEN COALESCE(payment_method.tipo, NULL)
				ELSE NULL
		END AS MONEDA,
		-- COALESCE(extra_payments.price, 0) AS PAGO,
		CASE 
				WHEN appointments.status = 2 THEN COALESCE(extra_payments.price, NULL)
				ELSE NULL
		END AS PAGO,
		-- extra_payments.voucher_issued AS VOUCHER,
		CASE 
				WHEN appointments.status = 2 THEN COALESCE(extra_payments.voucher_issued, NULL)
				ELSE NULL
		END AS VOUCHER,
		extra_payments.observation as OBSERVACION,
		-- tipo_pagos.descripcion AS TIPO_PAGO,
		CASE 
				WHEN appointments.status = 2 THEN COALESCE(tipo_pagos.descripcion, NULL)
				ELSE NULL
		END AS TIPO_PAGO,
		
		-- Fecha del primer adelanto (pago anterior a la cita)
		(
				SELECT MIN(ep.date)
				FROM extra_payments ep
				WHERE ep.appointment_id = appointments.id
				AND ep.date < appointments.date
		) AS FECHA_PAGO_ANTERIOR

		FROM schedules 
		LEFT JOIN professionals ON schedules.professional_id = professionals.id
		LEFT JOIN appointments ON appointments.schedule_id = schedules.id 
				AND appointments.date = '{$fecha}' -- Filtro temporal de fecha
		LEFT JOIN extra_payments ON appointments.id = extra_payments.appointment_id
				AND extra_payments.date = '{$fecha}' -- Filtro temporal de fecha
		LEFT join patients on appointments.patient_id = patients.id
		LEFT JOIN payment_method on extra_payments.moneda = payment_method.id
		LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
		inner join users u on professionals.user_id = u.id 
		WHERE schedules.day LIKE '{$diaSemana}' 
		AND professionals.activo = 1 and u.idSede = '{$sede}'
		ORDER BY schedules.professional_id ASC, schedules.check_time ASC;";
		


		$sqlSinCitas = "SELECT 
    Null AS DÍA,
    NULL AS HORARIO,  
    NULL AS PROFESIONAL, 
    COALESCE(NULLIF(extra_payments.customer, ''), '-') AS PACIENTE,  -- Muestra el nombre del paciente si existe
    NULL AS TIPO_PACIENTE,
    payment_method.tipo AS MONEDA,
    extra_payments.price AS PAGO,
    extra_payments.voucher_issued AS VOUCHER,
    extra_payments.observation as OBSERVACION,
    tipo_pagos.descripcion AS TIPO_PAGO,
    NULL AS FECHA_PAGO_ANTERIOR
		FROM extra_payments
		LEFT JOIN payment_method ON extra_payments.moneda = payment_method.id
		LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
		LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
		LEFT JOIN patients ON appointments.patient_id = patients.id
		WHERE extra_payments.appointment_id = 0
		AND extra_payments.type != 6
		and extra_payments.date = '{$fecha}'-- Filtramos por aquellos sin cita asociada
		ORDER BY DÍA ASC, HORARIO ASC;";

		$sqlEntradas = "SELECT 
    Null AS DÍA,
    NULL AS HORARIO,  
    NULL AS PROFESIONAL, 
    COALESCE(NULLIF(extra_payments.customer, ''), '-') AS PACIENTE,  -- Muestra el nombre del paciente si existe
    NULL AS TIPO_PACIENTE,
    payment_method.tipo AS MONEDA,
    extra_payments.price AS PAGO,
    extra_payments.voucher_issued AS VOUCHER,
    extra_payments.observation as OBSERVACION,
    tipo_pagos.descripcion AS TIPO_PAGO,
    NULL AS FECHA_PAGO_ANTERIOR
		FROM extra_payments
		LEFT JOIN payment_method ON extra_payments.moneda = payment_method.id
		LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
		LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
		LEFT JOIN patients ON appointments.patient_id = patients.id
		WHERE extra_payments.appointment_id = 0
		and extra_payments.type = 6
		and extra_payments.date = '{$fecha}'-- Filtramos por aquellos sin cita asociada
		ORDER BY DÍA ASC, HORARIO ASC;";

		$sqlAdelantos = "SELECT 
    Null AS DÍA,
    NULL AS HORARIO,  
    NULL AS PROFESIONAL, 
    COALESCE(NULLIF(extra_payments.customer, ''), '-') AS PACIENTE,  -- Muestra el nombre del paciente si existe
    appointments.patient_condition AS TIPO_PACIENTE,
    payment_method.tipo AS MONEDA,
    extra_payments.price AS PAGO,
    extra_payments.voucher_issued AS VOUCHER,
    extra_payments.observation as OBSERVACION,
    tipo_pagos.descripcion AS TIPO_PAGO,
    NULL AS FECHA_PAGO_ANTERIOR
		FROM extra_payments
		LEFT JOIN payment_method ON extra_payments.moneda = payment_method.id
		LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
		LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
		LEFT JOIN patients ON appointments.patient_id = patients.id
		WHERE extra_payments.date = '{$fecha}'-- Filtramos por aquellos sin cita asociada
		and appointments.date != '{$fecha}'
		and extra_payments.idSede = 1
		ORDER BY DÍA ASC, HORARIO ASC;";

		$sqlLimbo = "SELECT 
    Null AS DÍA,
    NULL AS HORARIO,  
    NULL AS PROFESIONAL, 
    COALESCE(NULLIF(extra_payments.customer, ''), '-') AS PACIENTE,  -- Muestra el nombre del paciente si existe
    appointments.patient_condition AS TIPO_PACIENTE,
    payment_method.tipo AS MONEDA,
    extra_payments.price AS PAGO,
    extra_payments.voucher_issued AS VOUCHER,
    extra_payments.observation as OBSERVACION,
    tipo_pagos.descripcion AS TIPO_PAGO,
    NULL AS FECHA_PAGO_ANTERIOR
		FROM extra_payments
		LEFT JOIN payment_method ON extra_payments.moneda = payment_method.id
		LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
		LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
		LEFT JOIN patients ON appointments.patient_id = patients.id
		WHERE extra_payments.date = '{fecha}'-- Filtramos por aquellos sin cita asociada
		and appointments.status = 6
		and extra_payments.idSede = 1
		ORDER BY DÍA ASC, HORARIO ASC;";
	
		$citas = DB::select($sqlCitas);
		$sinCitas = DB::select($sqlSinCitas);
		$entradas = DB::select($sqlEntradas);
		$adelantos = DB::select($sqlAdelantos);
		$limbo = DB::select($sqlLimbo);
		

		//return $sqlEntradas;
		return response()->json([
			array(
				'citas' => $citas,
				'sinCitas' => $sinCitas,
				'entradas' => $entradas,
				'adelantos' => $adelantos,
				'limbo' => $limbo
				)
		]);
		
	}
}
