<?php

namespace App\Http\Controllers;

use App\Models\Acuerdo;
use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Faltas;
use App\Models\Membresia;
use App\Models\Patient;
use App\Models\Patient_seguimiento;
use App\Models\Payment;
use App\Models\Precio;
use App\Models\Prescription;
use App\Models\Professional;
use App\Models\Reschedule;
use Carbon\Carbon;
use Exception;
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
		`idProfesional`, `origen`, `idUsuario`, `idSeguimiento`, `idPaciente`) VALUES 
		(?,?,?,?,?,
		?,?,?,?,?)",
		[
			$request->input('nombre'), $request->input('celular'), $request->input('motivo'), $request->input('referencia'), $request->input('correo'),
			$request->input('idProfesional'), $request->input('origen'), $request->input('idUsuario'), $request->input('idSeguimiento'), $request->input('idPaciente')
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
			->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre', 's.seguimiento as nomSeguimiento', 's.color', 's.icono')
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
			->select('i.*', 'p.nombre as nomProf', 'u.nombre as usuNombre', 's.seguimiento as nomSeguimiento', 's.color', 's.icono')
			->whereDate('fecha', '<=', $fecha)
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
			//->where('estado', 1)
			->whereNotIn('estado', [2,3]) //Ver la forma de disminuir la lista de hoy, porque se hará larga
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();
			$cobrados = DB::table('deudas as d')->where('d.activo', 1)
			->join('patients as p', 'p.id', '=', 'd.patient_id' )
			->whereDate('d.fechaActualiza', '=', $fecha)
			->whereIn('estado', [2,3])
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();
		}else{
			$resultados = DB::table('deudas as d')->where('d.activo', 1)
			->join('patients as p', 'p.id', '=', 'd.patient_id' )
			->whereDate('d.fecha', '<=', $fecha)
			//->where('estado', 1)
			->whereNotIn('estado', [2,3]) //Ver la forma de disminuir la lista de hoy, porque se hará larga
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();
			$cobrados = DB::table('deudas as d')->where('d.activo', 1)
			->join('patients as p', 'p.id', '=', 'd.patient_id' )
			->whereDate('d.fechaActualiza', '=', $fecha)
			->whereIn('estado', [2,3])
			->orderBy('estado', 'asc')
			->orderBy('d.fecha', 'asc')
			->select( 'd.*', 'p.*', 'd.id as idDeuda')
			->get();

		}

		foreach ($resultados as $resultado) {
			$direccion = DB::table('addresses')->where('patient_id', $resultado->patient_id)->get();
			$resultado->address = $direccion[0];
		}

		return array('deudas' => $resultados, 'cobrados' => $cobrados);
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
			case '18':
				$interesados = DB::table('interesados as i')
				->join('seguimientos as s', 's.id', 'i.idSeguimiento')
				->whereYear('fecha', $request->get('año') )
				->whereMonth('fecha', $request->get('mes') )
				->where('origen', 2)
				->select('i.*', 's.icono')
				->get();
				return $interesados;
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

	public function cambiarEstadoMembresia($id,$estado,$congelar){
		$membresia = Membresia::find($id);

		if($membresia->estado == 5){
			$fecha = Carbon::now();
			$fechaFinal = $nuevaFechaFin = $fecha->addDays($membresia->congelados);

			$membresia->update([
				'estado' => $estado,
				'fin' => $fechaFinal,
				'congelados' => 0,
				'comentarios' => $membresia->comentarios . ' Descongelado el ' . Carbon::now()->format('d/m/Y')
			]);
		}
		else{
			$membresia->update([
				'estado' => $estado,
				'congelados' => $congelar
			]);
		}
		return response()->json([ 'mensaje' => 'Actualizado exitoso' ]);
	}

	public function guardarMembresia(Request $request)
	{
    $fechas = json_decode($request->input('fechas'));
    $fechas_membresias = json_decode($request->input('fechas_membresias'));
    $membresia = json_decode($request->input('membresia'), true);

    // Obtener la IdSede del usuario
    $idSede = DB::table('users')->where('id', $request->input('user_id'))->value('IdSede');
		$hoy = Carbon::now();
		$fin = $hoy->addMonths($request->input('meses'));

    // Insertar en la tabla membresias
    $idMembresia = DB::table('membresias')->insertGetId([
        'patient_id' => $request->input('idPaciente'),
        'fin' => $fin->format('Y-m-d'),  //$membresia['fin'],
        'tipo' => $membresia['tipo'],
        'user_id' => $request->input('user_id'),
        'cuotas' => count($fechas),
        'monto' => $membresia['precio'],
				'estado' => 2,
        'comentarios' => $request->input('comentarios')
    ]);

    // Crear citas para las fechas de la membresía
    foreach ($fechas_membresias as $sesion) {
        $cita = Appointment::create([
            'date' => $sesion->fecha,
            'patient_condition' => 2, // paciente antiguo
            'type' => $membresia['tipo'],
            'mode' => 1,
            'status' => 1,
            'clasification' => 6,
            'professional_id' => $sesion->idProfesional,
            'patient_id' => $sesion->idPaciente,
            'schedule_id' => $sesion->idHorario,
            'formato_nuevo' => 1,
            'verAviso' => 1,
            'byDoctor' => 0,
            'idMembresia' => $idMembresia
        ]);

        // Crear el pago extra
        Payment::create([
            'observation' => '',
            'bank' => '',
            'voucher' => '',
            'pay_status' => 1,
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

    // Guardar los pagos adicionales (cuando "pago" es true)
    foreach ($fechas as $fecha) {
        if ($fecha->pago) { // cuando es true
            $pagoExtra = new Extra_payment;
            $pagoExtra->customer = $request->input('customer');
            $pagoExtra->price = $fecha->monto;
            $pagoExtra->moneda = 1;
            $pagoExtra->voucher = '';
            $pagoExtra->appointment_id = 0;
            $pagoExtra->type = 7; // pago de membresía
            $pagoExtra->observation = '';
            $pagoExtra->continuo = 3;
            $pagoExtra->idMembresia = $idMembresia;
            $pagoExtra->user_id = $request->input('user_id');

            // Asignar la IdSede que corresponde al usuario
            $pagoExtra->idSede = $idSede;
						//$pagoExtra->idSede = $request->input('idSede');

            $pagoExtra->save();

            // Actualizar estado de la membresía
            Membresia::where('id', $idMembresia)
                ->update([
                    'estado' => 2
                ]);
        } else {
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

    return response()->json(['mensaje' => 'Actualizado exitoso']);
	}

	public function anularMembresia(Request $request){
		$membresia = Membresia::where('id', $request->input('id'));
		$membresia->update([
			'activo' => 0,
			'estado' => 3	
		]);
		
		return response()->json([ 'mensaje' => 'Eliminado exitoso' ]);
	}

	public function reservarCitaDoctor(Request $request, Appointment $appointment){
		//var_dump($request->all()); die();
		$num_citas = $appointment::where('idMembresia', '=', $request->get('idMembresia') )->count();
		$request->merge(['num_sesion' => $num_citas+1 ]);

		$appointment ->fill($request->all());
		$appointment->save();
		

		Payment::create([
			'observation'=>'',
			'bank'=>'',
			'voucher' => '',
			'pay_status'=>2,
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
	public function pedirHistorialDeudas($idPaciente){
		$seguimientos = DB::table('deudas_pacientes as d')
		->join('seguimientos as s', 's.id', '=', 'd.idSeguimiento')
		->where('d.patient_id', $idPaciente)
		->where('d.activo', 1)
		->orderBy('registro', 'desc')
		->get();
		return response()->json($seguimientos);
	}

	public function buscarCartera(Request $request){
		if(!$request->get('texto')){
			if($request->get('año')==-1 ):
				$citasResumidas = Appointment::where('professional_id', $request->get('idProfesional'))
					->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
					->groupBy('patient_id')
					->with('patient')
					->orderBy('date', 'desc')
					->get();
				$citasCompletas = Appointment::where('professional_id', $request->get('idProfesional'))
					->with('patient')
					->orderBy('date', 'desc')
					->get();
			elseif($request->get('mes')==-1):
			/* 	$citasResumidas = Appointment::where('professional_id', $request->get('idProfesional'))
					->whereYear('date', $request->get('año'))
					->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
					->with('patient')
					->groupBy('patient_id')
					->orderBy('date', 'desc')
					->get(); */
				$citasCompletas = Appointment::where('professional_id', $request->get('idProfesional'))
					->whereYear('date', $request->get('año'))
					->with('patient')
					->orderBy('date', 'desc')
					->get();

				$citasResumidas = [];

				foreach ($citasCompletas as $cita) {
					$existe = false;
					foreach ($citasResumidas as $item) {
						if ($item['patient_id'] == $cita['patient_id']) {
							$existe = true;
							break;
						}
					}					
					if (!$existe) $citasResumidas[] = $cita;
				}
				/* usort($citasResumidas, function($a, $b) {
					return strtotime($b['date']) - strtotime($a['date']);
				}); */

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
					->with('patient')
					->orderBy('date', 'desc')
					->get();
			endif;
			foreach($citasCompletas as $cita){
				$pago = Payment::where('appointment_id', $cita->id)->get();
				$cita->payment = $pago[0] ?? [];
				$cita->proximo = DB::table('proximos')->where('patient_id', $cita->patient_id)->orderBy('fecha', 'desc')->first();
			}

		}
		if($request->get('texto')<>''){
			$pacientes = Patient::where('name', 'like', '%'. $request->get('texto').'%')->get();
			$citasResumidas=[]; $citasCompletas=[];
			foreach ($pacientes as $paciente) {
				$cResumen = Appointment::where('patient_id', $paciente->id)
					->select('patient_id', DB::raw('0 as visitas'),  DB::raw('0 as sinconfirmar'), DB::raw('0 as confirmar'), DB::raw('0 as anulados'), DB::raw('0 as reprogramados'), DB::raw('0 as faltas'), DB::raw('"" as actual'))
					->where('professional_id', $request->get('idProfesional'))
					->with('patient')
					->groupBy('patient_id')
					->orderBy('date', 'desc')
					->get();
				$cCompletas[] = Appointment::where('patient_id', $paciente->id)
					->where('professional_id', $request->get('idProfesional'))
					->with('patient')
					->orderBy('date', 'desc')
					->get();
				if(is_null($cResumen) || count($cResumen)==0) continue;
				else $citasResumidas[] = $cResumen[0];
				if(is_null($cCompletas) || count($cCompletas)==0) continue;
				else
					foreach ($cCompletas as $citaGrupo) {
						foreach ($citaGrupo as $cita) {
							$citasCompletas[] = $cita; 
						}
					}
			}
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
	public function listarPreciosTodos(){
		return DB::table('precios')
		/*->where('activo', 1)
		 ->whereNotIn('id', [28] ) //15
		->where('servicio', 1) */
		->orderBy('descripcion', 'asc')
		->get();
	}
	public function preciosMembresias(){
		return DB::table('precios')->where('activo', 1)
		//->whereIn('id', [15, 28] )
		->where('idClasificacion', '5')
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
		if($request->get('idRegistro')){
			DB::table('interesados')->where('id', $request->get('idRegistro'))
			->update([
				'idSeguimiento' => $request->get('idSeguimiento')
			]);
		}
		return $idSeguimiento;
	}
	public function insertarDeudasSeguimiento(Request $request){
		//var_dump($request->all()); die();
		$idSeguimiento = DB::table('deudas_pacientes')->insertGetId([
			'patient_id' => $request->get('patient_id'),
			'user_id' => $request->get('idUsuario'),
			'idSeguimiento' => $request->get('idSeguimiento'),
			'observaciones' => $request->get('motivo'),
			'idDeuda' =>$request->get('idRegistro')
		]);
		if($request->get('idRegistro')){
			switch($request->get('idSeguimiento')){
				case '9' : $seguir = 1; break;
				case '10' : $seguir = 2; break;
				case '11' : $seguir = 3; break;
				case '12' : $seguir = 4; break;
			}
			DB::table('deudas')->where('id', $request->get('idRegistro'))
			->update([
				'idActualiza' => $request->get('idUsuario'),
				'estado' => $seguir,
				'fechaActualiza' => Carbon::now(),
				'observaciones' => DB::raw("CONCAT(observaciones,'{$request->get('motivo')}' )")
			]);
		}
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
	public function subirArchivoAcuerdo( Request $request){
		$file = $request->file('file');
		if($file){
			$filename = uniqid() . '.' . $file->getClientOriginalExtension();
			//$file->storeAs('adjuntos', $filename,'public');
			$file->move(public_path('storage/adjuntos'), $filename);
		}else $filename='';

		$acuerdo = new Acuerdo();
		$acuerdo->patient_id = $request->get('patient_id');
		$acuerdo->titulo = $request->get('titulo') ?? '';
		$acuerdo->descripcion = $request->get('descripcion') ?? '';
		$acuerdo->archivo = '';
		$acuerdo->ruta = $filename;
		$acuerdo->user_id = $request->get('user_id');
		$acuerdo->activo = 1;
		$acuerdo->save();

		return response()->json([ 'archivo' => $acuerdo ]);
	}
	public function subirArchivoTriaje( Request $request){
		$file = $request->file('file');
		$filename = uniqid() . '.' . $file->getClientOriginalExtension();
		//$file->storeAs('adjuntos', $filename,'public');
		$file->move(public_path('storage/adjuntos'), $filename);


		$archivo = DB::table('triaje_archivo')->insert([
			'patient_id' => $request->get('idPaciente'),
			'ruta' => $filename,
		]);
		
		return response()->json([ 'archivo' => $archivo ]);
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
		 // Obtener la IdSede del usuario
		$idSede = DB::table('users')->where('id', $request->input('user_id'))->value('IdSede'); 
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
				// Asignar la IdSede que corresponde al usuario
				$pagoExtra->idSede = $idSede;
				$pagoExtra->save();
			Membresia::where('id', $request->input('idMembresia'))
			->update([
				'estado' => 2
			]);
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
		->select('m.*', 'p.descripcion', 'p.sesiones', 'p.meses', 'p.idClasificacion')
		->where('patient_id', $id)
		->where('m.activo', 1)
		->orderBy('inicio', 'desc')->get();
		foreach ($membresias as $membresia) {
			$contador_paquetes = Appointment::where('idMembresia', $membresia->id)->count();
			$membresia->contador_paquetes = $contador_paquetes;
			
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


	public function crearPrecioNuevo(Request $request){
		
		Precio::insert([
			'descripcion' => $request->input('precioNuevo.nombre'),
			'idClasificacion' => $request->input('precioNuevo.tipo'),
			'nuevos' => 0,
			'continuos' => 0,
			'sesiones' => 0,
			'servicio' => in_array($request->input('precioNuevo.tipo'), [1,2,3,6]) ? 1 : 0,
		]);
		return response()->json([ 'mensaje' => 'Registro exitoso' ]);
	}
	
	public function pedirCitasMembresia($id){
		$citas = Appointment::where('idMembresia', $id)
		->with('professional')
		->with('schedule')
		->with('precio')
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
			'especialMembresias' => $request->get('especialMembresias'),
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
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->where('patient_condition', 1) 
				->whereIn('status', [3]) //5
				->with('precio')
				->get();
				$continuos = Appointment::
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->where('patient_condition', 2)
				->whereIn('status', [3])

				->with('precio')
				->get();
				$sinRepetir = Appointment::whereBetween('date', [$request->get('inicio'), $request->get('fin')])
				->whereIn('status', [3])
				->with('precio')
				->get()
				->groupBy('patient_id')
				->map(function ($group) {
						return $group->first(); // O ->last() según necesites
				})
				->values();
			
				return response()->json(array('nuevos'=>$nuevos, 'continuos'=> $continuos, 'sinRepetir' => $sinRepetir)); break;

			case '2':
				$cartera = Appointment::
				whereBetween('date', [ $request->get('inicio'), $request->get('fin') ])
				->whereIn('status', [1,2])
				->with('professional')
				->with('precio')
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				$total = count($cartera);
				$nuevos = $cartera->where('patient_condition', 1)->count();
				$continuos = $cartera->where('patient_condition', 2)->count();
				$continuosSin = $cartera->whereIn('patient_condition', [1,2])
				->groupBy('patient_id')
				->map(function ($group) {
						return $group->first(); // O ->last() según necesites
				})
				//->values();
				->count();
				$sinRepetir = $cartera->groupBy('patient_id')
				->map(function ($group) {
						return $group->first();
				})
				->values();
				$especialidades = $cartera->groupBy('professional.profession');
				$especialidadesSinRepetir = $sinRepetir->groupBy('professional.profession');
				
				return response()->json(array(
					'cartera'=> $cartera,
					'total' => $total,
					'nuevos' => $nuevos,
					'continuos' => $continuos,
					'continuosSin' => $continuosSin,
					'especialidades'=>$especialidades,
					'carteraSinRepetir' => $sinRepetir,
					'sinRepetir' => $especialidadesSinRepetir
				)); break;
			case '3':
				$cartera = Appointment::
				/* whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes')) */
				//whereBetween('created_at', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				whereBetween('date', [ $request->get('inicio'), $request->get('fin') ])
				->whereBetween('status', [1,	2]) //,3,4
				->where('type', '!=', null)
				->with('professional')
				->with('precio')
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				$sinRepetir = $cartera->groupBy('patient_id')
				->map(function ($group) {
						return $group->first();
				})
				->values();
				$profSin = $sinRepetir->groupBy('professional.profession');
				
				return response()->json(array(
					'cartera'=> $cartera,
					'sinRepetir' => $sinRepetir,
					'profSin' => $profSin
				)); break;

			case '4': 
				$cartera = [];
				//Codigo anterior
				/* $carteras = Appointment::
				whereBetween('date', [$request->get('inicio') , $request->get('fin') ])
				->with('professional')
				->with(['payment'=> function($query) {
					$query->where('payments.pay_status','=', 2);
				}])
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				foreach ($carteras as $cart) {
					if($cart->payment)
						$cartera[] = $cart;
				}*/
				$cartera = Extra_payment::
				whereBetween('date', [$request->get('inicio') , $request->get('fin') ])
				->with(['appointment' => function ($query) use ($request) {
					$query->whereBetween('date', [$request->get('inicio'), $request->get('fin')]);
				}])
				->with('appointment.professional')
				->where('idSede', $request->get('idSede'))
				->where('activo', 1)
				->whereIn('type', [5,8,16])
				->get();
				$profesionales = $cartera->groupBy('appointment.professional.name')
					->map(function ($citas, $professionalName) {
							// Obtener el primer pago para extraer datos del profesional
							$firstPayment = $citas->first();
							$professional = $firstPayment->appointment->professional ?? null;
							$suma = $citas->sum('price');
							return [
									'citas' => $citas->pluck('appointment')->filter()->values(),
									'profesion' => $professional->profession ?? 'Sin profesión',
									'suma' => $suma
							];
					})
					->toArray();
				return response()->json( array(
					'carteras' => $cartera,
					'profesionales' => $profesionales
				)); 

				break;
			case '5':
				$citas = Reschedule::
				/* whereYear('reschedules.created_at', $request->get('año'))
				->whereMonth('reschedules.created_at', $request->get('mes')) */
				whereBetween('reschedules.created_at',[$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
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
				whereBetween('registro', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				->whereIn('idSeguimiento', [7])
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
				whereBetween('attention_date', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
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
				where date_format(ci.created_at, '%Y-%m-%d') between '{$request->get('inicio')}' and '{$request->get('fin')}'
				group by cie_id, c.code, c.description
				order by contador desc;");
				return response()->json($diagnosticos); break;
			case '9':
				$ids = Appointment::whereBetween('date', [
					$request->get('inicio'),
					$request->get('fin')
				])
				->selectRaw('MIN(id) as id')
				->where('status', 2)
				->groupBy('patient_id')
				->pluck('id');
				$pacientes = Appointment::whereIn('id', $ids)
				->with('patient')
				->get();
				$contador = count($pacientes);
				$edades = $pacientes->groupBy(function ($item) {
					$edad = now()->diffInYears($item->patient->birth_date);
					// Agrupamos por edad
					$grupo = floor($edad / 10) * 10;
					//return $edad; //. ' años'
					return $grupo . ' - ' . ($grupo + 10);
				});
				$edades = $edades->sortKeys(); //sortKeysDesc
				
				$sexos = $pacientes->groupBy(function($item){
					$sexo = $item->patient->gender ?? 'sin_dato';
					return $sexo;
				});
				$ids = str_replace(['[', ']'], ['(', ')'], $ids);

				$recomendados = DB::select("SELECT recomendation, count(recomendation) as contador
				FROM `appointments`
				where recomendation is not null
				and recomendation <> 'null'
				and date_format(`date`, '%Y-%m-%d') between '{$request->get('inicio')}' and '{$request->get('fin')}'
				and id in {$ids}
				group by recomendation
				order by contador desc;");
			
				return response()->json(array( 'edades'=> $edades, 'sexos'=> $sexos, 'recomendados' => $recomendados, 'total'=>$contador )); 
				
				break;

			case '10':
				$pagos = Extra_payment::
				/* whereYear('date', $request->get('año'))
				->whereMonth('date', $request->get('mes')) */
				whereBetween('date', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				->where('type', '!=', 6)
				->with('comprobante')
				->get();
				$agrupados = $pagos->groupBy(function ($item){
					return $item->comprobante->descripcion;
				});
				return response()->json($agrupados); break;

			case '11':
				//Codigo anterior
				/* $medios1 = DB::select("SELECT sum(price) as suma, ti.descripcion
				FROM `extra_payments` as ex
				inner join tipo_pagos as ti on ti.id = ex.type
				where date_format(`date`, '%Y-%m-%d') between '{$request->get('inicio')}' and '{$request->get('fin')}'
				and ex.type<>6
				and idMembresia<>0
				group by ex.type, ti.descripcion;");
		
				$medios2 = DB::select("SELECT sum(price) as suma, p.descripcion
				FROM `extra_payments` as ex
				inner join precios as p on p.id = ex.type
				where date_format(`date`, '%Y-%m-%d') between '{$request->get('inicio')}' and '{$request->get('fin')}'
				and ex.type<>6
				and idMembresia=0
				group by ex.type, p.descripcion;");

				$medios = array_merge($medios1, $medios2);

				$contador = count($medios);
				return response()->json(array('pagos'=> $medios, 'total'=>$contador)); */

				$sumaPsicologia = DB::selectOne(DB::raw("SELECT 
						SUM(extra_payments.price) AS total
				FROM 
						extra_payments 
				LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
				LEFT JOIN professionals ON appointments.professional_id = professionals.id
				LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
				LEFT JOIN precios ON appointments.type = precios.id
				WHERE 
						extra_payments.idsede = {$request->get('idSede')}
						AND extra_payments.activo = 1 
						AND professional_id <> 18 
						AND extra_payments.type IN (5, 8, 16)
						AND extra_payments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
						AND appointments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
						AND professionals.profession = 'psicologo'"));

				$sumaPsiquiatria = DB::selectOne(DB::raw("SELECT 
						SUM(extra_payments.price) AS total
				FROM 
						extra_payments 
				LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
				LEFT JOIN professionals ON appointments.professional_id = professionals.id
				LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
				LEFT JOIN precios ON appointments.type = precios.id
				WHERE 
						extra_payments.idsede = {$request->get('idSede')} 
						AND extra_payments.activo = 1 
						AND professional_id <> 18 
						AND extra_payments.type IN (5, 8, 16)
						AND extra_payments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
						AND appointments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
						AND professionals.profession = 'psiquiatra'"));

				$sumaCertificados = DB::selectOne(DB::raw("SELECT 
						SUM(extra_payments.price) AS total
				FROM 
				extra_payments 
				LEFT JOIN appointments ON extra_payments.appointment_id = appointments.id
				LEFT JOIN professionals ON appointments.professional_id = professionals.id
				LEFT JOIN tipo_pagos ON extra_payments.type = tipo_pagos.id
				LEFT JOIN precios ON appointments.type = precios.id
				WHERE 
					extra_payments.idsede = {$request->get('idSede')} 
					AND extra_payments.activo = 1 
					AND professional_id = 18 
					AND extra_payments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
					AND appointments.date BETWEEN '{$request->get('inicio')}' AND '{$request->get('fin')}' 
				"));
				
				$extras = Extra_payment::
				whereIn('type', [0,2,3,4]) //1,7,15 <= membresías
				->whereBetween('date', [ $request->get('inicio'), $request->get('fin') ])
				->where('idSede', $request->get('idSede'))
				->where('activo', 1)
				->with('tipo_pagos')
				->get();
				$extrasAgrupado = $extras->groupBy('tipo_pagos.descripcion')
				->map(function ($group) {
        return [
            'total' => $group->sum('price')
        	];
    		});

				return response()->json(array(
					'psicologia' => $sumaPsicologia,
					'psiquiatria' => $sumaPsiquiatria,
					'certificados' => $sumaCertificados,
					'extras' => $extrasAgrupado
				));
				break;
			case '12':
				$pagos = Extra_payment::
				whereBetween('date', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				->where('type', '!=', 6)
				->where('idSede', $request->get('idSede'))
				->with('method_payment')
				->get();
				$agrupados = $pagos->groupBy(function ($item){
					$moneda = $item->method_payment->tipo ?? 'Ninguno';
					return $moneda;
				});
				return response()->json($agrupados); break; //, 'agrupados'=>$agrupados

				default: break;
				
				case '13':
				$cartera = Appointment::
				/* whereYear('created_at', $request->get('año'))
				->whereMonth('created_at', $request->get('mes')) */
				//whereBetween('created_at', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				whereBetween('date', [ $request->get('inicio'), $request->get('fin') ])
				->where('status', 2)
				->with('professional')
				->with('precio')
				->with('schedule')
				->with('patient')
				->orderBy('professional_id', 'asc')
				->get();
				$total = count($cartera);
				$especialidades = $cartera->groupBy('professional.profession')
				->map(function ($citas, $especialidad) {
						$nuevos = $citas->where('patient_condition', 1)->count();
						$continuos = $citas->where('patient_condition', 2)->count();

						return [
								'citas' => $citas,
								'especialidad' => $especialidad,
								'nuevos' => $nuevos,
								'continuos' => $continuos,
						];
				});
				return response()->json(array(
					'total' => $total,
					
					'especialidades'=>$especialidades
				));
			break;
			case '14':
				$recetas = Prescription::
				/* whereYear('attention_date', $request->get('año'))
				->whereMonth('attention_date', $request->get('mes')) */
				whereBetween('attention_date', [$request->get('inicio') . " 00:00:00", $request->get('fin')." 23:59:59"] )
				->with('kairos')
				->with('patient')
				->with('professional')
				->get();
				
				/* $medicamentos = $recetas
				->flatMap(fn ($receta) => $receta->kairos) // obtiene todos los medicamentos en una sola colección
				->groupBy('id')                        // agrupa por el campo que identifica al medicamento
				->map(fn ($grupo) => $grupo->count())      // cuenta cuántos hay en cada grupo
				->sortDesc(); */
				$medicamentos = $recetas
				->flatMap(fn ($receta) => $receta->kairos) // obtiene todos los medicamentos en una sola colección
				->groupBy('id')                            // agrupa por id del medicamento
				->map(function ($grupo) {
						$primerMedicamento = $grupo->first();  // obtenemos un representante del grupo
						return [
								'nombre' => $primerMedicamento->name . ' ' .$primerMedicamento->concentration,
								'presentacion' => $primerMedicamento->presentation,
								'cantidad' => $grupo->count(),
						];
				})
				->sortByDesc('cantidad') // o sortDesc() si usas Collection y quieres orden descendente
				->values() ;// opcional: reindexa el array si no necesitas las keys por id

				return response()->json($medicamentos);
			break;
			case '15':
				$citas = Appointment::
				whereBetween('date', [$request->get('inicio'), $request->get('fin')] )
				->whereIn('status', [1,2])
				->with('precio')
				->with('patient')
				->with('pagosExtras')
				->with('professional')
				->with('schedule')
				->with('payment')
				->orderBy('professional_id', 'asc')
				->get()
				->sortBy('schedule.check_time')
				;
				$citaAgrup = $citas->groupBy('professional.name');
				return response()->json($citaAgrup);
			break;
				
		}
	}

	function dividirPago($id, Request $request){		
		//return $request->all();
		$pagoExtra = Extra_payment::find($id);
		
		$nuevoPago = new Extra_payment();
		$nuevoPago->customer = $pagoExtra->customer;
		$nuevoPago->price = $request->input('caso.monto');
		$nuevoPago->moneda = $request->input('caso.moneda');
		$nuevoPago->voucher = $pagoExtra->voucher;
		$nuevoPago->appointment_id = $pagoExtra->appointment_id;
		$nuevoPago->type = $pagoExtra->type;
		$nuevoPago->observation = $request->input('caso.observacion');
		$nuevoPago->continuo = $pagoExtra->continuo;
		$nuevoPago->idMembresia = $pagoExtra->idMembresia;
		$nuevoPago->user_id = $request->input('idUsuario');
		$pagoExtra->idSede =$pagoExtra->idSede;
		$pagoExtra->date = now()->toDateString();
		$nuevoPago->tipo = $pagoExtra->tipo;
		$nuevoPago->idSede = $pagoExtra->idSede;
		$nuevoPago->save();

		$pagoExtra->update([
			'price' => $pagoExtra->price - $request->input('caso.monto')
		]);
		return $nuevoPago;
	}

}
