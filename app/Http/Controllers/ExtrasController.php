<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Patient;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
		DB::insert("INSERT INTO `interesados`(`nombre`, `celular`, `motivo`, `referencia`, `correo`, 
		`idProfesional`, `origen`) VALUES 
		(?,?,?,?,?,
		?,?)",
		[
			$request->input('nombre'), $request->input('celular'), $request->input('motivo'), $request->input('referencia'), $request->input('correo'),
			$request->input('idProfesional'), $request->input('origen')
		]);
		$ultimoID = DB::getPdo()->lastInsertId();
		return response()->json([
			'mensaje' => $ultimoID
		]);
	}
	public function listarInteresados(){
		try {
			$interesados = DB::table('interesados as i')
		->join('professionals as p', 'p.id', '=', 'i.idProfesional')
		->join('users as u', 'i.idUsuario', '=', 'u.id')
		->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre')
		->where('i.activo', '=', '1')
		->orderBy('fecha', 'asc')
		->get();
		return $interesados;
		} catch (\Throwable $th) {
			echo $th;
		}
		
	}
	public function borrarInteresados($id){
		$resultado = DB::table('interesados')->where('id', '=', $id)
		->update([
			'activo' => 0
		]);

		if($resultado ){
			return response()->json([
				'mensaje' => 'Actualizado exitoso'
			]);
		}
	}

	public function listarDeudas($fecha){
		$resultados = DB::table('deudas as d')->where('d.activo', 1)
		->join('patients as p', 'p.id', '=', 'd.patient_id' )
		->whereDate('d.fecha', '<=', $fecha)
		->orderBy('d.fecha', 'asc')
		->get();

		foreach ($resultados as $resultado) {
			$direccion = DB::table('addresses')->where('patient_id', $resultado->patient_id)->get();
			$resultado->address = $direccion[0];
		}

		return array('deudas' => $resultados);
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

			case '1':
				DB::statement("SET SQL_MODE=''");//this is the trick use it just before your query

				$results = DB::select( DB::raw("SELECT MAX(ap.date) as fechaMax, ap.*, p.* FROM `appointments` ap
				inner join patients p on p.id = ap.patient_id
				where status not like 3 and datediff(now(), date) between 7 and 90
				and p.discharge = 0 and 
				date = (SELECT MAX(date) from appointments apo where apo.patient_id = ap.patient_id GROUP BY apo.patient_id )
				group by ap.patient_id
				order by date desc;") );
				return response()->json($results);
				break;
				
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
		
		DB::table('recommendations')->insert([
			'professional_id' => $request->input('professional_id'),
			'comment' => $request->input('texto'),
			'patient_id' => $request->input('patient_id'),
		]);
		return response()->json([ 'mensaje' => 'Actualizado exitoso' ]);
		
	}

	public function guardarMembresia( Request $request){
		
		$fechas = json_decode ($request->input('fechas'));
		$membresia = json_decode ($request->input('membresia'), true);
		//var_dump( $fechas ); die();
		$idMembresia=DB::table('membresias')-> insertGetId([
			'patient_id' => $request->input('idPaciente'),
			'fin' => $membresia['fin'],
			'tipo' => $membresia['tipo'],
			'user_id' => $request->input('user_id'),
			'cuotas' => count($fechas),
			'monto' => $membresia['precio'],
		]);


		foreach($fechas as $fecha){
			if( $fecha->pago ){ //cuando es true
				$pagoExtra = new Extra_payment;
				$pagoExtra->customer = $request->input('customer');
				$pagoExtra->price = $fecha->monto;
				$pagoExtra->moneda = 1;
				$pagoExtra->voucher = '';
				$pagoExtra->appointment_id = 0;
				$pagoExtra->type = 7; //pago de membresía
				$pagoExtra->observation = '';
				$pagoExtra->continuo = 1;
				$pagoExtra->user_id = $request->input('user_id');
				$pagoExtra->save();
			}else{
				DB::table('deudas')->insert([
					'patient_id' => $request->input('idPaciente'),
					'motivo' => $membresia['tipo']=='15' ? 'Pago de membresía Kurame': 'Pago de membresía Clínica de día',
					'user_id' => $request->input('user_id'),
					'fecha' => $fecha->dia,
					'monto' => $fecha->monto,
					'idMembresia' => $idMembresia
				]);
			}
		}

		return response()->json([ 'mensaje' => 'Actualizado exitoso' ]);
	}

	public function reservarCitaDoctor(Request $request, Appointment $appointment){
		//var_dump($request->all()); die();
		$appointment ->fill($request->all());
		$appointment->save();

		Payment::create([
			'observation'=>'',
			'bank'=>'',
			'voucher' => '',
			'pay_status'=> 1,
			'price' => $request->get('precio'),
			'appointment_id' => $appointment->id,
			'continuo' => 2,
			'user_id' => $request->get('professional_id'),
			'rebaja' => 0,
			'motivoRebaja' => '',
			'descuento' => 0,
			'motivoDescuento' => ''
		]);
		//return $appointment; // Ya contiene el ID
		return response()->json([ 'mensaje' => 'Actualizado exitoso' ]);
	}

	public function listRecomendation($id){
		
		$recommendations = DB::table('recommendations as r')->where('patient_id', $id)
		->join('professionals as pro', 'pro.id', 'r.professional_id')
		->where('r.activo', '=', 1)
		->orderBy('date', 'desc')
		->get();

		return response()->json($recommendations);
	}

	public function buscarCartera(Request $request){
		
		
		if($request->get('mes')==-1):
			$citasResumidas = Appointment::where('professional_id', $request->get('idProfesional'))
				->whereYear('date', $request->get('año'))
				->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
				->groupBy('patient_id')
				->with('patient')
				->get();
			$citasCompletas = Appointment::where('professional_id', $request->get('idProfesional'))
				->whereYear('date', $request->get('año'))
				->orderBy('date', 'desc')
				->with('patient')
				->get();
		else:
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
		endif;

		foreach($citasCompletas as $cita){
			$pago = Payment::where('appointment_id', $cita->id)->get();
			$cita->payment = $pago[0] ?? [];
		}

		return response()->json([ "resumidas"=>$citasResumidas, 'completas'=>$citasCompletas ]);
		
	}

	public function listarPrecios(){
		return DB::table('precios')->where('activo', 1)
		->whereNotIn('id', [15,28] )
		->orderBy('descripcion', 'asc')
		->get();
	}
	public function preciosMembresias(){
		return DB::table('precios')->where('activo', 1)
		//->whereIn('id', [15, 28] )
		->where('idClasificacion', '4')
		->whereNotIn('id', [19, 20, 21, 23, 24, 25, 26] )
		->orderBy('descripcion', 'asc')
		->get();
	}
	public function insertarSeguimiento(Request $request){
		//var_dump($request->all()); die();
		try {
			Patient::find($request->get('patient_id'))->update([
				'seguimiento' =>  $request->get('idSeguimiento')
			]);
	
			$idSeguimiento = DB::table('pacient_seguimiento')->insertGetId([
				'patient_id' => $request->get('patient_id'),
				'user_id' => $request->get('idUsuario'),
				'idSeguimiento' => $request->get('idSeguimiento')
			]);
			return $idSeguimiento;
		} catch (\Throwable $th) {
			echo $th;
		}
		
	}
}
