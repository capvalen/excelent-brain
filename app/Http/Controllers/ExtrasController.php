<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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

	public function cargarUsuarios(){
		$usuarios = DB::table('users')
		->where('activo', 1)
		->select('id', 'nombre', 'email', 'rol', 'privilegios')
		->get();
		return response()->json( $usuarios );

	}

	public function addRecomendation( Request $request){
		//print_r($request->all()); die();
		DB::table('recommendations')->insert([
			'professional_id' => $request->input('professional_id'),
			'comment' => $request->input('texto'),
			'patient_id' => $request->input('patient_id'),
		]);
		return response()->json([ 'mensaje' => 'Actualizado exitoso' ]);
	}

	public function listRecomendation($id){
		$recommendations = DB::table('recommendations as r')->where('patient_id', $id)
		->join('professionals as pro', 'pro.id', 'r.professional_id')
		->where('activo', '=', 1)
		->orderBy('date', 'desc')
		->get();

		return response()->json($recommendations);
	}

	public function buscarCartera(Request $request){
		//var_dump($request->all());
		$citasResumidas = Appointment::where('professional_id', $request->get('idProfesional'))
			->whereYear('date', $request->get('año'))
			->whereMonth('date', $request->get('mes'))
			->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
			->groupBy('patient_id')
			->with('patient')
			->get();
		$citasCompletas = Appointment::where('professional_id', $request->get('idProfesional'))
			->whereYear('date', $request->get('año'))
			->whereMonth('date', $request->get('mes'))
			->orderBy('date', 'desc')
			->with('patient')
			->get();
		return response()->json([ "resumidas"=>$citasResumidas, 'completas'=>$citasCompletas ]);
		
	}

	public function listarPrecios(){
		return DB::table('precios')->where('activo', 1)->get();
	}
}
