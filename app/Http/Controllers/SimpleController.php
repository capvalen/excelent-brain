<?php

namespace App\Http\Controllers;

use App\Models\Medical_comment;
use App\Models\Patient;
use App\Models\Nutricion;
use Illuminate\Support\Facades\DB;
use App\Models\Payment_method;
use Illuminate\Http\Request;

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

		$token = "apis-token-7375.-NocVzIovJyr371-cz9a5bYHVsPxRMNt";
				
		$curl = curl_init();

		// Buscar dni
		curl_setopt_array($curl, array(
			// para user api versión 2
			CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 2,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Referer: https://apis.net.pe/consulta-dni-api',
				'Authorization: Bearer ' . $token
			),
		));

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
}
