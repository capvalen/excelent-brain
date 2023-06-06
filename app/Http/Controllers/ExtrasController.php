<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtrasController extends Controller
{
	//
	public function nuevoAviso(Request $request){
		DB::insert("INSERT INTO `recordatorios`(`fecha`, `actividad`, `responsable`, `creador`, `actualizador`, `tipo`) VALUES 
		(?,?,?,?,?,
		?)",
		[
			$request->input('fecha'), $request->input('actividad'), $request->input('responsable'), $request->input('creador'), $request->input('creador'),
			$request->input('tipo')
		]);
		$ultimoID = DB::getPdo()->lastInsertId();
		return response()->json([
			'mensaje' => $ultimoID
		]);
	}
	public function actualizarAviso(Request $request){
		$resultado = DB::table('recordatorios')
		->where('id', '=', $request->input('id'))
		->update([
			'estado' => $request->input('tipo'),
			'actualizador' => $request->input('actualizador'),
			'observaciones' => $request->input('observaciones'),
		]);

		if($resultado ){
			return response()->json([
				'mensaje' => 'Actualizado exitoso'
			]);
		}else{
			return response()->json([
				'mensaje' => 'Error Actualizando'
			]);
		}
	}
	public function listarAvisos($fecha){
		$avisos = DB::table('recordatorios as r')
		->join('users as u', 'r.creador', '=', 'u.id')
		->select('r.*', 'u.email as nomCreador')
		->whereDate('fecha', $fecha)->get();
		return $avisos;
	}
	public function nuevoInteresado(Request $request){
		DB::insert("INSERT INTO `interesados`(`nombre`, `celular`, `motivo`, `referencia`, `correo`) VALUES 
		(?,?,?,?,?)",
		[
			$request->input('nombre'), $request->input('celular'), $request->input('motivo'), $request->input('referencia'), $request->input('correo')
		]);
		$ultimoID = DB::getPdo()->lastInsertId();
		return response()->json([
			'mensaje' => $ultimoID
		]);
	}
	public function listarInteresados(){
		$interesados = DB::table('interesados')-> where('activo', '=', '1')->get();
		return $interesados;
	}
	public function borrarInteresados($id){
		try {
			$resultado = DB::table('interesados')->where('id', '=', $id)
			->update([
				'activo' => 0
			]);
	
			if($resultado ){
				return response()->json([
					'mensaje' => 'Actualizado exitoso'
				]);
			}
		} catch (\Throwable $th) {
			echo $th;
		}
	}
	public function cambiarLike($id, $like){
		DB::table('patients')->where('id', '=', $id)
		->update([ 'club' => $like ]);
	}
	public function pedirReporte($id, Request $request){
		//print_r( $request->get('año') ); die();
		switch ($id) {
			case '0': //Reporte de recetas vacias
			$recetas = DB::table('prescriptions as pre')
				->leftJoin('professionals as pro', 'pro.id', '=', 'pre.professional_id')
				->join('patients as p', 'p.id', '=', 'pre.patient_id')
				->where('p.activo', 0)
				->where('p.dni', null)
				->whereYear('attention_date', $request->get('año') )
				->whereMonth('attention_date', $request->get('mes') )
				->select('pre.*', 'pro.nombre', 'p.name')
				->get();
			return $recetas; break;
			default:
				# code...
				break;
		}
	}
}
