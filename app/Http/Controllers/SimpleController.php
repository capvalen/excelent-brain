<?php

namespace App\Http\Controllers;

use App\Models\Medical_comment;
use App\Models\Patient;
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
}
