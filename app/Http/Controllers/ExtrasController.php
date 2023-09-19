<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Patient;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
			'respuesta' => $request->input('respuesta')
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
		$anteriores = DB::table('recordatorios as r')
		->join('users as u', 'r.creador', '=', 'u.id')
		->select('r.*', 'u.email as nomCreador')
		->whereDate('fecha', '<', $fecha)
		->where('estado', 1)
		->orderByRaw("FIELD(estado, 1, 4, 2, 3)")
		->orderBy('fecha', 'desc')
		->get();
		$avisos = DB::table('recordatorios as r')
		->join('users as u', 'r.creador', '=', 'u.id')
		->select('r.*', 'u.email as nomCreador')
		->whereDate('fecha', $fecha)
		->orderByRaw("FIELD(estado, 1, 4, 2, 3)")
		->orderBy('fecha', 'desc')
		->get();
		return response()->json([
			'avisos'=>$avisos, 'anteriores'=> $anteriores
		]);
	}
	public function listarAvisosAhora(){
		$ahora = Carbon::now();
		$hace = $ahora->copy()->subMinutes(1, 'minute');
		$hasta = $ahora->copy()->addMinutes(8);

		$avisos = DB::table('recordatorios as r')
		->where('estado', 1)
		->where('fecha', '>=', $hace->toDateTimeString())
		->where('fecha', '<=', $hasta->toDateTimeString())
		->get();
		return response()->json([
			'avisos'=>$avisos,  'ahora' => $ahora->toDateTimeString(), 'hace'=>  $hace->toDateTimeString(), 'hasta' =>  $hasta->toDateTimeString()
		]);
	}
	public function nuevoInteresado(Request $request){
		DB::insert("INSERT INTO `interesados`(`nombre`, `celular`, `motivo`, `referencia`, `correo`, 
		`idProfesional`, `origen`, `idUsuario`) VALUES 
		(?,?,?,?,?,
		?,?, 1)",
		[
			$request->input('nombre'), $request->input('celular'), $request->input('motivo'), $request->input('referencia'), $request->input('correo'),
			$request->input('idProfesional'), $request->input('origen')
		]);
		$ultimoID = DB::getPdo()->lastInsertId();
		return response()->json([
			'mensaje' => $ultimoID
		]);
	}
	public function listarInteresados($fecha){
		try {
			
			if( $fecha== Carbon::now()->format('Y-m-d') ){
				$interesados = DB::table('interesados as i')
				->leftjoin('professionals as p', 'p.id', '=', 'i.idProfesional')
				->join('users as u', 'i.idUsuario', '=', 'u.id')
				->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre')
				->where('i.activo', '=', '1')
				->orderBy('atendido', 'asc')
				->orderBy('fecha', 'asc')
				->get();
			}else{
				$interesados = DB::table('interesados as i')
			->leftjoin('professionals as p', 'p.id', '=', 'i.idProfesional')
			->join('users as u', 'i.idUsuario', '=', 'u.id')
			->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre')
			->where('i.activo', '=', '1')
			->whereDate('fecha', '=', $fecha)
			->orderBy('atendido', 'asc')
			->orderBy('fecha', 'asc')
			->get();
			}
		
		return response()->json([ 'interesados'=> $interesados]);
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
		if( $fecha== Carbon::now()->format('Y-m-d') ){
			$resultados = DB::table('deudas as d')->where('d.activo', 1)
			->join('patients as p', 'p.id', '=', 'd.patient_id' )
			->whereDate('d.fecha', '<=', $fecha)
			//->whereNotIn('estado', [2,3]) Ver la forma de disminuir la lista de hoy, porque se hará larga
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();
		}else{
			$resultados = DB::table('deudas as d')->where('d.activo', 1)
			->join('patients as p', 'p.id', '=', 'd.patient_id' )
			->whereDate('d.fecha', '=', $fecha)
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();

		}

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
		->whereIn('rol', ['recepcionista', 'administrador', 'interno'])
		->select('id', 'nombre', 'email', 'rol', 'privilegios', 'password')
		->orderBy('nombre')
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
		$fechas_membresias = json_decode ($request->input('fechas_membresias'));
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

		foreach($fechas_membresias as $sesion){
			$cita = Appointment::create([
				'date'=>$sesion->fecha,
				'patient_condition'=>2, //consideremos paciente antiguo
				'type'=>$membresia['tipo'],
				'mode'=>1,
				'status'=>1,
				'clasification'=>6,
				'professional_id'=>$sesion->idProfesional,
				'patient_id'=>$sesion->idPaciente,
				'schedule_id'=>$sesion->idHorario,
				'formato_nuevo'=>1,
				'verAviso'=>1,
				'byDoctor'=>0,
			]);

			Payment::create([
				'observation'=>'',
				'bank'=>'',
				'voucher' => '',
				'pay_status'=> 1,
				'price' => 0,
				'appointment_id' => $cita->id,
				'continuo' => 2,
				'user_id' => 1,
				'rebaja' => 0,
				'motivoRebaja' => 'Creado por membresía',
				'descuento' => 0,
				'motivoDescuento' => ''
			]);

		}


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
				$pagoExtra->continuo = 3;
				$pagoExtra->user_id = $request->input('user_id');
				$pagoExtra->save();
			}else{
				DB::table('deudas')->insert([
					'patient_id' => $request->input('idPaciente'),
					'motivo' => $request->input('nombreMembresia'),
					'user_id' => $request->input('user_id'),
					'fecha' => $fecha->dia,
					'monto' => $fecha->monto,
					'idMembresia' => $idMembresia,
					'idPago' => $membresia['tipo']
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
		/* ->whereNotIn('id', [28] ) //15
		->where('servicio', 1) */
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
	public function verAdjuntoPago($id){
		$foto = DB::table('payments_files')->where('payment_id', $id)->get();
		return response()->json([ 'archivo' => $foto ]);
	}
	public function eliminarAdjunto($id, Request $request){
		$archivo = public_path('storage/adjuntos/'. $request->get('archivo') );
		unlink($archivo);
		DB::table('payments_files')->where('payment_id', $id)->delete();
		return response()->json([ 'mensaje' => 'foto borrada' ]);
	}
	public function subirArchivoPago( Request $request){
		$file = $request->file('file');
		$filename = uniqid() . '.' . $file->getClientOriginalExtension();
		//$file->storeAs('adjuntos', $filename,'public');
		$file->move(public_path('storage/adjuntos'), $filename);


		DB::table('payments_files')->insert([
			'file' => $filename,
			'payment_id' => $request->get('idPago')
		]);
		
		return response()->json([ 'archivo' => $filename ]);
	}

	public function respuestaInteresado(Request $request){
		//var_dump($request->all()); die();
		$responde = json_decode ($request->input('respuesta'));
		DB::table('interesados')->where('id', $request->get('id'))
		->update([
			'respuesta' => $responde->respuesta,
			'atendido' => $responde->tipo
		]);
		return response()->json([ 'mensaje' => 'Respuesta guardada']);
	}

	public function pagarDeudaMembresia(Request $request){
		DB::table('deudas')->where('id', $request->input('idDeuda'))
		->update([
			'idActualiza' => $request->input('user_id'),
			'fechaActualiza' => Carbon::now(),
			'estado' => $request->input('estado'),
			'observaciones' => $request->input('observacion')
		]);
		if($request->input('estado')=='2'){//realizó el pago
			$pagoExtra = new Extra_payment;
				$pagoExtra->customer = $request->input('nombre');
				$pagoExtra->price = $request->input('precio');
				$pagoExtra->moneda = 1;
				$pagoExtra->voucher = '';
				$pagoExtra->appointment_id = 0;
				$pagoExtra->type = $request->input('tipo');
				$pagoExtra->observation = 'Cancelación de deuda';
				$pagoExtra->continuo = 3;
				$pagoExtra->user_id = $request->input('user_id');
				$pagoExtra->save();
		}
		return response()->json(['mensaje' => 'Actualizado con éxito']);
	}

	public function nuevoUsuarioBasico(Request $request){
		$idUsuario = DB::table('users')
		->insertGetId([
			'nombre'=>$request->input('usuario.nombre'),
			'email'=>$request->input('usuario.email'),
			'rol'=>$request->input('usuario.rol'),
			'privilegios'=>$request->input('usuario.privilegios'),
			'password'=> Hash::make($request->input('usuario.clave')),
		]);
		return response()->json(['mensaje'=>'Creado con éxito', 'id' => $idUsuario]);
	}

	public function actualizarDatosUsuarioBasico(Request $request){
		$clave = $request->get('clave')=='' ? '' : Hash::make($request->get('clave'));
		
		DB::table('users')->where('id', $request->input('usuario.id'))
		->update([
			'nombre'=>$request->input('usuario.nombre'),
			'email'=>$request->input('usuario.email'),
			'rol'=>$request->input('usuario.rol'),
			'privilegios'=>$request->input('usuario.privilegios')
		]);
		if($clave){
			DB::table('users')->where('id', $request->input('usuario.id'))
			->update([ 'password'=>$clave ]);
		}
		return response()->json(['mensaje' => 'Actualizado con éxito']);
	}

	public function eliminarUsuario(Request $request){
		DB::table('users')->where('id', $request->get('id'))
		->update([
			'activo'=>0
		]);
		return response()->json(['mensaje' => 'Actualizado con éxito']);
	}

	public function crearAcontecimiento(Request $request){
		$id = DB::table('acontecimientos')
		->insertGetId([
			'idProfesional'=>$request->input('idProfesional'),
			'idPaciente'=>$request->input('idPaciente'),
			'edad'=>$request->input('acontecimiento.edad'),
			'acontecimiento'=>$request->input('acontecimiento.acontecimiento'),
			'sintomas'=>$request->input('acontecimiento.sintomas'),
			
		]);
		return response()->json(['mensaje'=>'Creado con éxito', 'id' => $id]);
	}

	public function cargarLineas($id){
	try {
		$lineas = DB::table('acontecimientos as a')->where('a.idPaciente', $id)
		->join('professionals as p', 'p.id', '=', 'a.idProfesional')
		->select('a.*', 'p.name as nomProfesional')
		->where('a.activo', 1)
		->orderBy('a.fecha', 'desc')
		->get();

		return $lineas;
	} catch (\Throwable $th) {
		echo $th;
	}
	}
}
