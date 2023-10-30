<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Faltas;
use App\Models\Patient;
use App\Models\Patient_seguimiento;
use App\Models\Payment;
use App\Models\Precio;
use App\Models\Prescription;
use App\Models\Professional;
use App\Models\Reschedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Extract;
use PhpParser\Node\Stmt\TryCatch;

class ExtrasController extends Controller
{
	//
	public function nuevoAviso(Request $request){
		DB::insert("INSERT INTO `recordatorios`(`fecha`, `actividad`, `responsable`, `creador`, `actualizador`, `tipo`, `idResponsable`) VALUES 
		(?,?,?,?,?,
		?, ?)",
		[
			$request->input('fecha'), $request->input('actividad'), $request->input('responsable'), $request->input('creador'), $request->input('creador'),
			$request->input('tipo'), $request->input('idResponsable')
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
		`idProfesional`, `origen`, `idUsuario`, `idSeguimiento`) VALUES 
		(?,?,?,?,?,
		?,?,?,?)",
		[
			$request->input('nombre'), $request->input('celular'), $request->input('motivo'), $request->input('referencia'), $request->input('correo'),
			$request->input('idProfesional'), $request->input('origen'), $request->input('idUsuario'),  $request->input('idSeguimiento')
		]);
		$ultimoID = DB::getPdo()->lastInsertId();
		return response()->json([
			'mensaje' => $ultimoID
		]);
	}

	public function listarInteresados($fecha = null){
		if($fecha == null) Carbon::now()->format('Y-m-d');
		
		$todos = DB::table('interesados as i')
			->leftjoin('professionals as p', 'p.id', '=', 'i.idProfesional')
			->join('users as u', 'i.idUsuario', '=', 'u.id')
			->join('seguimientos as s', 's.id', '=', 'i.idSeguimiento')
			->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre', 's.seguimiento as nomSeguimiento', 's.color')
			->whereDate('fecha', '=', $fecha)
			->where('i.atendido', '!=', 0)
			->where('i.activo', 1)
			->orderBy('atendido', 'asc')
			->orderBy('fecha', 'asc')
			->get();

		$interesados = DB::table('interesados as i')
			->leftjoin('professionals as p', 'p.id', '=', 'i.idProfesional')
			->join('users as u', 'i.idUsuario', '=', 'u.id')
			->join('seguimientos as s', 's.id', '=', 'i.idSeguimiento')
			->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre', 's.seguimiento as nomSeguimiento', 's.color')
			->where('i.atendido', 0)
			->where('i.activo', 1)
			->orderBy('atendido', 'asc')
			->orderBy('fecha', 'asc')
			->get();
		
		return response()->json([ 'anteriores'=> $todos, 'interesados' => $interesados ]);
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

				$results = DB::select( DB::raw("SELECT MAX(ap.date) as fechaMax, ap.*, p.*, pre.descripcion as nomServicio, pre.idClasificacion
				FROM `appointments` ap
				inner join patients p on p.id = ap.patient_id
				inner join precios pre on pre.id = ap.type
				where status not like 3 and datediff(now(), date) between 7 and 90
				and p.discharge = 0 and 
				date = (SELECT MAX(date) from appointments apo where apo.patient_id = ap.patient_id GROUP BY apo.patient_id )
				group by ap.patient_id
				order by date desc;") );
				return response()->json($results);
				break;
			case '2':
				$pacientes = Patient::where('hobbies', '<>', '[]')
				->whereNotNull('hobbies')
				->orderBy('name', 'asc')
				->get();
				return response()->json($pacientes);
				break;
			case '3':
				$pacientes = Patient::where('club', 1)
				->orderBy('name', 'asc')->get();
				return response()->json($pacientes); break;
			case '4':
				$pacientes = Patient::where('club', 2)
				->orderBy('name', 'asc')->get();
				return response()->json($pacientes); break;
			case '5':
				$resultados = DB::table('semaforo as s')
				->join('patients as p', 'p.id', '=', 's.patient_id')
				->select('p.name', 's.*')
				->orderBy('p.name', 'asc')
				->get();
				return response()->json($resultados); break;
			case '6':
				try {
					$resultados = Professional::where('activo', 1)->orderBy('name', 'asc')->get();
					$citas = collect();
				foreach ($resultados as $profesional) {
					$cita = Appointment::where('professional_id', $profesional->id)
					->with('payment')
					->with('schedule')
					->with('patient')
					->with('precio')
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->where('status', 2) //confirmado
					->get();
					$citas = $citas->merge($cita);
				}
				return response()->json(['doctores'=>$resultados, 'citas'=>$citas ]);
				} catch (\Throwable $th) {
					echo $th;
				}
			case '7':
				$servicios = DB::table('precios')->where('servicio', 1)->get();
				$citas = collect();
				foreach($servicios as $servicio){
					$cita = Appointment::where('type', $servicio->id)
					->with('payment')
					->with('schedule')
					->with('professional')
					->with('patient')
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->where('status', 2) //confirmado
					->get();
					$citas = $citas->merge($cita);
				}
				return response()->json(['servicios'=>$servicios, 'citas'=>$citas ]);
				break;
			case '8':
				$citas = Appointment::where('patient_condition', 1)
					->with('patient')
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')
					->get();
				$citasAgrupadas=$citas->groupBy('patient_id');
				return response()->json(['citas'=>$citasAgrupadas, 'conteo'=> $citasAgrupadas->count() ]);
				break;
			case '9':
				$citas = Appointment::where('patient_condition', 2)
					->with('patient')
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')
					->get();
				$citasAgrupadas=$citas->groupBy('patient_id');
				return response()->json(['citas'=>$citasAgrupadas, 'conteo'=> $citasAgrupadas->count() ]);
				break;
			case '10':
					$servicios = DB::table('precios')->where('servicio', 1)->get();
					$resultados = DB::table('extra_payments as e')->where('e.activo', 1)
					->join('payment_method as p', 'p.id', '=', 'e.moneda')
					->select('e.*', 'p.tipo')
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')
					->get();
					$monedas = DB::table('payment_method')->get();
				
				return response()->json(['pagos'=>$resultados, 'monedas'=>$monedas, 'servicios'=>$servicios ]);
				break;
			case '11':
				$cuantosPsicologia = Appointment::where('status', 2)
					->join('payments as pa', 'pa.appointment_id', '=', 'appointments.id')
					->join('precios as p', 'p.id', '=', 'appointments.type')
					->where('p.idClasificacion', 1)
					->whereHas('payment', function ($query){ $query->where('pay_status', 2); })
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')->count();
				$montoPsicologia = Appointment::where('status', 2)
					->join('payments as pa', 'pa.appointment_id', '=', 'appointments.id')
					->join('precios as p', 'p.id', '=', 'appointments.type')
					->where('p.idClasificacion', 1)
					->whereHas('payment', function ($query){ $query->where('pay_status', 2); })
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')->sum('price');
					$cuantosPsiquiatria = Appointment::where('status', 2)
					->join('payments as pa', 'pa.appointment_id', '=', 'appointments.id')
					->join('precios as p', 'p.id', '=', 'appointments.type')
					->where('p.idClasificacion', 2)
					->whereHas('payment', function ($query){ $query->where('pay_status', 2); })
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')->count();
				$montoPsiquiatria = Appointment::where('status', 2)
					->join('payments as pa', 'pa.appointment_id', '=', 'appointments.id')
					->join('precios as p', 'p.id', '=', 'appointments.type')
					->where('p.idClasificacion', 2)
					->whereHas('payment', function ($query){ $query->where('pay_status', 2); })
					->whereYear('date', $request->get('año'))
					->whereMonth('date', $request->get('mes') )
					->orderBy('date', 'asc')->sum('price');
				
				return response()->json(['montoPsicologia'=>$montoPsicologia, 'cuantosPsicologia'=> $cuantosPsicologia, 'montoPsiquiatria'=> $montoPsiquiatria, 'cuantosPsiquiatria'=> $cuantosPsiquiatria  ]);
				
				break;
			case '12':
				if($request->get('mes')=='-1'){
					$seguimientos = DB::table('pacient_seguimiento as ps')->where('ps.activo', 1)
					->join('patients as p', 'p.id', '=', 'ps.patient_id')
					->whereYear('ps.registro', $request->get('año'))
					->get();
				}else{
					$seguimientos = DB::table('pacient_seguimiento as ps')->where('ps.activo', 1)
					->join('patients as p', 'p.id', '=', 'ps.patient_id')
					->whereYear('ps.registro', $request->get('año'))
					->whereMonth('ps.registro', $request->get('mes') )
					->get();
				}
				$estados = DB::table('seguimientos')->where('id','<>', 1)->orderBy('seguimiento', 'asc')->get();
				return response()->json(['seguimientos'=>$seguimientos, 'estados'=>$estados]);
			case '13':
				$pagos = DB::table('extra_payments')
				->where('voucher','<>','')->whereNotNull('voucher')
				->where('voucher_issued','<>','')->whereNotNull('voucher_issued')
				->where('type','<>',6)
				->whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes') )
				->get();

				return response()->json(['pagos'=>$pagos]);
				break;
			case '14':
				$reprogramados = Reschedule::whereYear('created_at', $request->get('año') )
				->whereMonth('created_at', $request->get('mes') )
				->with('appointment')
				->with('appointment.professional')
				->with('appointment.patient')
				->with('appointment.precio')
				->with('appointment.schedule')
				->get();
				return $reprogramados;
				break;
			case '15':
				$reprogramados = Faltas::whereYear('fecha', $request->get('año') )
				->whereMonth('fecha', $request->get('mes') )
				->where('esFalta', 1)
				->where('idCita', '!=', 0)
				->with('appointment')
				->with('appointment.professional')
				->with('appointment.patient')
				->with('appointment.precio')
				->with('appointment.schedule')
				->get();
				return $reprogramados;
				break;
			case '16':
				$citas = Appointment::where('status', 2)
				->whereYear('date', $request->get('año') )
				->whereMonth('date', $request->get('mes') )
				->with('patient')
				->with('precio')
				->with('schedule')
				->with('professional')
				->get();
				return $citas;
				break;
			case '17':
				$pacientes = Patient::
				where('phone', '=', '')->orWhere('phone', '=', null)
				->orWhere('gender', '=', null)
				->where('activo', 1)
				->with('address')
				->whereHas('address', function($query){
					$query->where('address', '=', '')->orWhere('address', '=', null);
				})
				->orderBy('id', 'desc')
				->get();
				return $pacientes;
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
				'idMembresia'=>$idMembresia
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
				$pagoExtra->idMembresia = $idMembresia;
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

	public function pedirHistorialSeguimientos($id){
		$seguimientos = DB::table('pacient_seguimiento as p')
		->join('seguimientos as s', 's.id', '=', 'p.idSeguimiento')
		->where('p.patient_id', $id)
		->where('p.activo', 1)
		->orderBy('registro', 'desc')
		->get();
		return response()->json($seguimientos);
	}

	public function buscarCartera(Request $request){
		
		
		if($request->get('mes')==-1):
			$citasResumidas = Appointment::where('professional_id', $request->get('idProfesional'))
				->whereYear('date', $request->get('año'))
				->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
				->groupBy('patient_id')
				->with('patient')
				->orderBy('date', 'desc')
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
				->orderBy('date', 'desc')
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
			Patient::find($request->get('patient_id'))->update([
				'seguimiento' =>  $request->get('idSeguimiento')
			]);
	
			$idSeguimiento = DB::table('pacient_seguimiento')->insertGetId([
				'patient_id' => $request->get('patient_id'),
				'user_id' => $request->get('idUsuario'),
				'idSeguimiento' => $request->get('idSeguimiento'),
				'observaciones' => $request->get('motivo')
			]);
			return $idSeguimiento;
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
				$pagoExtra->idMembresia = $request->input('idMembresia');
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

	public function buscarMembresias($id){
		$membresias = DB::table('membresias as m')
		->join('precios as p', 'p.id', '=', 'm.tipo')
		->select('m.*', 'p.descripcion')
		->where('patient_id', $id)->orderBy('inicio', 'desc')->get();
		foreach ($membresias as $membresia) {
			if($membresia->cuotas>0){
				#buscar Pagos realizados y deudas
				$membresia->pagados = Extra_payment::where('idMembresia', $membresia->id)->get();
				$membresia->deudas = DB::table('deudas')->where('idMembresia', $membresia->id)->where('estado', 1)->where('activo', 1)->get();
			}else{
				$membresia->pagados=[];
				$membresia->deudas=[];
			}
		}
		return response()->json($membresias);
	}

	public function pedirCitasMembresia($id){
		$citas = Appointment::where('idMembresia', $id)
		->with('professional')
		->with('schedule')
		->get();
		return response()->json($citas);
	}

	public function ampliarFechaMembresia(Request $request){
		DB::table('deudas')->where('id', $request->get('id'))
		->update(['fecha' => $request->get('fecha')]);
		return response()->json(['mensaje' => 'Actualizdo con éxito']);
	}

	public function actualizarPrecioAdmin(Request $request){
		//$precio = DB::table('precios')->where('id', $request->get('id'));
		$precio = Precio::where('id', $request->get('id'));
		$precio->update([
			'nuevos' => $request->get('nuevos'),
			'continuos' => $request->get('continuos'),
			'descripcion' => $request->get('descripcion'),
			'sesiones' => $request->get('sesiones'),
			'servicio' => $request->get('servicio')
		]);
		return response()->json(['mensaje' => 'Actualizado ']);
	}

	function pedirReporteGerencial($id, Request $request){
		switch ($id) {
			case '1': //Reporte de tipo de paciente
				$nuevos = Appointment::
				/*whereYear('created_at', $request->get('año'))
				 ->whereMonth('created_at', $request->get('mes'))*/
				whereBetween('created_at', [$request->get('inicio'), $request->get('fin')] )
				->where('patient_condition', 1) 
				->with('precio')
				->get();
				$continuos = Appointment::whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes'))
				->where('patient_condition', 2)
				->with('precio')
				->get();
				return response()->json(array('nuevos'=>$nuevos, 'continuos'=> $continuos)); break;

			case '2': case '3':
				$cartera = Appointment::
				/* whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes')) */
				whereBetween('created_at', [$request->get('inicio'), $request->get('fin')] )
				->where('patient_condition', 1)
				->with('professional')
				->with('precio')
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				return response()->json($cartera); break;

			case '4': 
				$cartera = Appointment::
				/* whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes')) */
				whereBetween('created_at', [$request->get('inicio'), $request->get('fin')] )
				->where('patient_condition', 1)
				->with('professional')
				->with('payment')
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				return response()->json($cartera); break;
			case '5':
				$citas = Reschedule::
				/* whereYear('reschedules.created_at', $request->get('año'))
				->whereMonth('reschedules.created_at', $request->get('mes')) */
				whereBetween('reschedules.created_at', $request->get('inicio'), $request->get('fin') )
				->join('appointments', 'reschedules.appointment_id', '=', 'appointments.id')
				->with('appointment')
				->with('appointment.professional')
				->with('appointment.patient')
				->orderBy('appointments.professional_id', 'asc')
				->get();
				return response()->json($citas); break;
			case '6':
				$altas = Patient_seguimiento::
				/* whereYear('registro', $request->get('año'))
				->whereMonth('registro', $request->get('mes')) */
				whereBetween('registro', $request->get('inicio'), $request->get('fin') )
				->where('idSeguimiento', 4)
				->where('activo', 1)
				->with('patient')
				->with('user')
				->with('user.professional')
				->get();
				return response()->json($altas); break;
				
			case '7':
				$recetas = Prescription::
				/* whereYear('attention_date', $request->get('año'))
				->whereMonth('attention_date', $request->get('mes')) */
				whereBetween('attention_date', $request->get('inicio'), $request->get('fin') )
				->with('patient')
				->with('professional')
				->orderBy('professional_id', 'asc')
				->get();
				return response()->json($recetas); break;
				
			case '8':
				$diagnosticos = DB::select("SELECT count(cie_id) as contador, cie_id, c.code, c.description
				FROM `cie_patient` as ci
				inner join cies as c on c.id = ci.cie_id
				/* where year(ci.created_at) = {$request->get('año')}
				and month(ci.created_at) = {$request->get('mes')} */
				where date_format(ci.created_at, '%Y-%m-%d') between {$request->get('inicio')} and {$request->get('fin')}
				group by cie_id, c.code, c.description
				order by contador desc;");
				return response()->json($diagnosticos); break;
			case '9':
				$pacientes = Appointment::
				/* whereYear('date', $request->get('año'))
				->whereMonth('date', $request->get('mes')) */
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->with('patient')
				->get();
				$contador = count($pacientes);
				$edades = $pacientes->groupBy(function ($item) {
					$edad = now()->diffInYears($item->patient->birth_date);
					// Agrupamos por edad
					return $edad; //. ' años'
				});
				$edades = $edades->sortKeys(); //sortKeysDesc
				
				$sexos = $pacientes->groupBy(function($item){
					$sexo = $item->patient->gender ?? 'sin_dato';
					return $sexo;
				});

				$recomendados = DB::select("SELECT recomendation, count(recomendation) as contador FROM `appointments`
				where recomendation is not null
				/* and year(date) = {$request->get('año')}
				and month(date) = {$request->get('mes')} */
				where date_format(`date`, '%Y-%m-%d') between {$request->get('inicio')} and {$request->get('fin')}
				group by recomendation
				order by contador desc;");
			
				return response()->json(array( 'edades'=> $edades, 'sexos'=> $sexos, 'recomendados' => $recomendados, 'total'=>$contador )); break;

			case '10':
				$pagos = Extra_payment::
				/* whereYear('date', $request->get('año'))
				->whereMonth('date', $request->get('mes')) */
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->where('type', '!=', 6)
				->with('comprobante')
				->get();
				$agrupados = $pagos->groupBy(function ($item){
					return $item->comprobante->descripcion;
				});
				return response()->json($agrupados); break;

			case '11':
				$medios = DB::select("SELECT sum(price) as suma, ti.descripcion
				FROM `extra_payments` as ex
				inner join tipo_pagos as ti on ti.id = ex.type
				/* where year(date) = {$request->get('año')}
				and month(date) = {$request->get('mes')} */
				where date_format(`date`, '%Y-%m-%d') between {$request->get('inicio')} and {$request->get('fin')}
				and ex.type<>6
				group by ex.type, ti.descripcion;");
				/* $medios = Extra_payment::whereYear('date', $request->get('año'))
				->whereMonth('date', $request->get('mes'))
				->with('precio')
				->get(); */
				/* $pagos = $medios->groupBy(function($item){
					$pago = $item->precio->descripcion;
					return $pago;
				}); */
				$contador = count($medios);
				return response()->json(array('pagos'=> $medios, 'total'=>$contador)); break;
			case '12':
				$pagos = Extra_payment::
				/* whereYear('date', $request->get('año'))
				->whereMonth('date', $request->get('mes')) */
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->where('type', '!=', 6)
				->with('method_payment')
				->get();
				$agrupados = $pagos->groupBy(function ($item){
					$moneda = $item->method_payment->tipo;
					return $moneda;
				});
				return response()->json($agrupados); break; //, 'agrupados'=>$agrupados

				default: break;
		}

	}
	
}
