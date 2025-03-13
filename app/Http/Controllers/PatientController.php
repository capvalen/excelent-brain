<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Medical_evolution;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Patient_seguimiento;
use App\Models\Prescription;
use App\Models\Professional;
use App\Models\Reschedule;
use App\Models\Triaje;
use App\Models\Relative;
use Faker\Provider\ar_EG\Person;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PatientController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$patient = Patient::where('activo', '=', 1)
		->with('initial_psychiatric_history', 'initial_psychological_history')
		->whereNotNull('dni')
		
			->get();
		return response()->json($patient);
	}

	public function patientMine($id)
	{
		/* try {
			$citas = Appointment::where('professional_id', $id)
			->latest('created_at')->take(6)
		->get();

		$patients=array();
		foreach ($citas as $cita) {
			$paciente = Patient::where('id', $cita->patient_id)
			->with('initial_psychiatric_history', 'initial_psychological_history', 'appointments')
			->get();
			$patients[] = $paciente[0];
		}

		return response()->json($patients);
		} catch (\Throwable $th) {
			echo $th;
		} */
		
		$patients = Patient::join('appointments as a', 'a.patient_id', '=', 'patients.id')
		->with('initial_psychiatric_history', 'initial_psychological_history')

		->where('a.professional_id', $id)
		->where('activo', 1)
		->where('a.status', '<>', 3)
		->latest('a.created_at')->take(10)
		
		->select('patients.*' )->get();
		return response()->json($patients);
		
	}

	public function patientMineText($texto)
	{
		
		DB::statement("SET SQL_MODE=''");

		$pacientes = Patient::
		with('appointments')
		->with('initial_psychiatric_history', 'initial_psychological_history')
		->whereHas('appointments', function($query){
				$query->where('status', '<>', 3)
				->latest('created_at');
			})
		->where('patients.name', 'like', '%'. $texto.'%' )
		->orWhere('patients.dni', $texto)
		->where('activo', 1)
		->groupBy('patients.id')
		->get();

		return response()->json($pacientes);
	}

	/**
	 * Return all patient with recipes and addrres
	 */
	public function insertPatient(Request $request){
		
		$repetido = Patient::where('dni', $request->input('paciente.dni'));
		if( $repetido->count() >0){
			echo 'repetidos '. $repetido->count(); 
		}else{
			//echo $request->input('paciente.name');die();
			$paciente = Patient::create([
					'dni'=>$request->input('paciente.dni'),
					'name'=> trim(str_replace('  ', ' ' , $request->input('paciente.name'))),
					'nombres'=> trim(str_replace('  ', ' ' , $request->input('paciente.nombres'))),
					'phone'=>$request->input('paciente.phone'),
					'instruction_degree'=> $request->input('paciente.instruction_degree') ?? 6,
					'gender'=> $request->input('paciente.gender') ?? 2,
					'birth_date'=> $request->input('birth_date') =='null' ? null: $request->input('paciente.birth_date'),
					'occupation'=> $request->input('occupation') =='null' ? null: $request->input('paciente.occupation'),
					'marital_status'=> $request->input('paciente.marital_status')
				]);
			Address::create([
				'address'=> $request->input('paciente.address.address'),
				'district'=>$request->input('paciente.address.district'),
				'province'=>$request->input('paciente.address.province'),
				'department'=>$request->input('paciente.address.department'),
				'patient_id' => $paciente->id
			]);

			Relative::where('patient_id',$paciente->id)->delete();

			if($request->input('paciente.contacto')){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('paciente.contacto')),
					'phone'=> str_replace('null', '', $request->input('paciente.contacto_celular')),
					'kinship'=> str_replace('null', '', $request->input('paciente.parentezco')),
					'patient_id' => $paciente->id
				]);
			}

			if($request->input('paciente.contacto2') ){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('paciente.contacto2')),
					'phone'=> str_replace('null', '', $request->input('paciente.contacto_celular2')),
					'kinship'=> str_replace('null', '', $request->input('paciente.parentezco2')),
					'patient_id' => $paciente->id
				]);
			}
			echo $paciente->id;
		}

	}

	public function getPatient ()
	{
		$patient = Patient::with('relative', 'address', 'prescriptions')
		->get();

		return response()->json($patient);
	}
	public function getLast10Patients (){
		$patients = Patient::where('activo', '=', 1)
		->with('relative', 'address', 'prescriptions')
		->latest('created_at')->take(20)
		->get();
		foreach($patients as $patient){
			$triaje = DB::table('triaje')->where('patient_id', $patient->id)->get();
			//$patient->triaje_count=$triaje->count();
			$patient->triajes = $triaje ;
			$semaforo = DB::table('semaforo')->where('patient_id', $patient->id )->where('activo',1)->orderBy('registro', 'desc')->get();
			$patient->semaforo = $semaforo;

			$conteo= Appointment::where('patient_id', $patient->id)
			->where('status', 4)
			->get();
			$patient->reprogramaciones = count($conteo);
		}

		return response()->json($patients);
	}
	public function getLast10PatientsAdmin (){
		$patients = Patient::
		with('relative', 'address', 'prescriptions')
		->latest('created_at')->take(20)
		->get();
		foreach($patients as $patient){
			$triaje = DB::table('triaje')->where('patient_id', $patient->id)->get();
			//$patient->triaje_count=$triaje->count();
			$patient->triajes = $triaje ;
			$semaforo = DB::table('semaforo')->where('patient_id', $patient->id )->where('activo',1)->orderBy('registro', 'desc')->get();
			$patient->semaforo = $semaforo;

			$conteo= Appointment::where('patient_id', $patient->id)
			->where('status', 4)
			->get();
			$patient->reprogramaciones = count($conteo);
		}

		return response()->json($patients);
	}
	public function searchPatientByNameDni ($nombre){
		$patients = Patient::where('activo', 1)
		->where('name', 'LIKE', "%".$nombre ."%")
		->orWhere('dni', $nombre )
		->with('relative', 'address', 'prescriptions')
		->orderBy('name', 'asc')
		->get();
		foreach($patients as $patient){
			$triaje = DB::table('triaje')->where('patient_id', $patient->id)->get();
			//$patient->triaje_count=$triaje->count();
			$patient->triajes = $triaje ;
			$semaforo = DB::table('semaforo')->where('patient_id', $patient->id )->where('activo',1)->orderBy('registro', 'desc')->get();
			$patient->semaforo = $semaforo;

			$conteo= Appointment::where('patient_id', $patient->id)
			->where('status', 4)
			->get();
			$patient->reprogramaciones = count($conteo);
		}

		return response()->json($patients);
	}
	public function searchPatientByNameDniAdmin ($nombre){
		$patients = Patient::
		where('name', 'LIKE', "%".$nombre ."%")
		->orWhere('dni', $nombre )
		->with('relative', 'address', 'prescriptions')
		->orderBy('name', 'asc')
		->get();
		foreach($patients as $patient){
			$triaje = DB::table('triaje')->where('patient_id', $patient->id)->get();
			//$patient->triaje_count=$triaje->count();
			$patient->triajes = $triaje ;
			$semaforo = DB::table('semaforo')->where('patient_id', $patient->id )->where('activo',1)->orderBy('registro', 'desc')->get();
			$patient->semaforo = $semaforo;

			$conteo= Appointment::where('patient_id', $patient->id)
			->where('status', 4)
			->get();
			$patient->reprogramaciones = count($conteo);
		}

		return response()->json($patients);
	}
	public function verReprogramaciones ($id){
		$reprogramaciones= Appointment::where('patient_id', $id)
		->where('status', 4)
		->get();
		forEach($reprogramaciones as $reprogramacion){
			$queMotivo = Reschedule::where('appointment_id', $reprogramacion->id)->get();
			$reprogramacion -> motivo = count($queMotivo)>0? $queMotivo[0]->reason:'';
		}
		return response()->json($reprogramaciones);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$patient = Patient::find($id);
		return response()->json($patient);
	}

	/**
	 * Search patients take 6
	 */

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request,  Patient $patient)
	{
		
		$patient->update($request->all());	

		// $patient->relative->update([
		//     'name' => $request->input('relative.name'),
		//     'phone' => $request->input('relative.phone'),
		//     'kinship' => $request->input('relative.kinship'),
		// ]);

		$patient->address->update([
			'district' => $request->input('address.district'),
			'address' => $request->input('address.address'),
			'province' => $request->input('address.province'),
			'department' => $request->input('address.department'),
		]);

		if( $request->get('relative')[0]['id']<>-1 ){
			Relative::find($request->input('relative')[0]['id'])
			->update([
				'name' => $request->get('relative')[0]['name'] ?? '',
				'phone' => $request->get('relative')[0]['phone'] ?? '',
				'kinship' => $request->get('relative')[0]['kinship'] ?? ''
			]);
		}

		return response()->json(['mensaje' => 'success']);
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
	public function buscarPaciente($dni)
	{
	  $paciente = Patient::where('dni',$dni)->with('address')->first();
	 
		if( $paciente ){
			$relaciones = Relative::where('patient_id', $paciente->id)->get();
			$deudas = DB::table('deudas')->where('patient_id', $paciente->id )
			->where('activo', 1)
			->where('estado', 1)
			->whereDate('fecha','<=', Carbon::now())
			->orderBy('fecha', 'desc')
			->get();

			//Verificar si tiene membresía activa
			$membresia = DB::table('membresias as m')
			->join('precios as p', 'p.id', '=', 'm.tipo')
			->select('m.*', 'p.descripcion', 'p.sesiones')
			->where('patient_id', $paciente->id)
			->where('m.activo', 1)
			->where('m.estado', 2)
			->whereDate( 'm.inicio', '<=', Carbon::now() )
			->whereDate( 'm.fin', '>=', Carbon::now() )
			->first();

			return response()->json([
				'patient'=>$paciente,
				'relacion' => $relaciones,
				'deudas' => $deudas,
				'membresia' => $membresia
				]);
		}else{
			return response()->json([ 'patient'=>$paciente ]);
		}
	}

	public function showEvolution ($idPaciente, $idUsuario=-1) {
		if($idUsuario==10){
			$evoluciones = Patient::where('id',$idPaciente)
			->with('cies', 'initial_psychiatric_history', 'initial_psychological_history', 'relative', 'appointments', 'prescriptions')
			->with('medical_evolutions.professional','medical_evolutions.comentarios')
			->with(['medical_evolutions'=> function($query) {
				$query->where('activo','=', 1);
			}])
			->first();
			$evoluciones->maximo=0;
		}else{
			$threeMonthsAgo = now()->subMonths(3);
	
			$evoluciones = Patient::where('id',$idPaciente)
			->with('cies', 'initial_psychiatric_history', 'initial_psychological_history', 'relative', 'appointments', 'prescriptions')
			->with('medical_evolutions.professional','medical_evolutions.comentarios')
			->with(['medical_evolutions'=> function($query) use($threeMonthsAgo) {
				$query->where('activo','=', 1)
				->whereBetween('date', [ $threeMonthsAgo, now() ]);
			}])
			->first();
			$evoluciones->maximo = Medical_evolution::where('date', '<', $threeMonthsAgo)
			->where('patient_id', $idPaciente)
			->count();
		}
		
		//return response()->json([$evoluciones]); die();
		
		$triaje = DB::table('triaje')->where('patient_id', $evoluciones->id)->get();
		$evoluciones->triajes = $triaje ;
		$examenes = DB::table('exams')->where('patient_id', $evoluciones->id)->get();
		$evoluciones->examenes_basicos = $examenes;
		//$evoluciones->examenes->push( $examenes );
		$burns = DB::table('burns')->where('patient_id', $evoluciones->id)->get();
		$gads = DB::table('gads')->where('patient_id', $evoluciones->id)->get();
		$scrs = DB::table('scrs')->where('patient_id', $evoluciones->id)->get();
		$zung_anxieties = DB::table('zung_anxieties')->where('patient_id', $evoluciones->id)->get();
		$zung_depressions = DB::table('zung_depressions')->where('patient_id', $evoluciones->id)->get();
		$evoluciones->examenes_personalizados =  array( 'scrs' => $scrs, 'burns' => $burns, 'gads' => $gads, 'zung_anxieties' => $zung_anxieties, 'zung_depressions' => $zung_depressions);

		return response()->json($evoluciones);
	}

	public function getNamePatient($id){
		$patient = Patient::find($id);
		$last_recipe = Prescription::where('patient_id',$id)->orderBy('id','desc')->first();
		if($last_recipe){
			$data_last_recipe = $last_recipe->kairos()->withPivot('amount','way','indications')->get();
						return response()->json([
							'patient' => $patient,
							'last_recipe' => $data_last_recipe
						]);
		}else{
			return response()->json($patient);
		}
	}

	public function getNames(){
		$patients = Patient::all();
		return $patients;
	}

	public function getNameExam($id){
		$appointment = Appointment::where('id',$id)->first();
		$patient = $appointment->patient()->first();

		return $patient;
	}

	/**
	 * Generate Xlsx for patient, in the rol recepcion
	 * @param date
	 */

	public function createXlsx ($date)
	{
		
		$dataPatients = Appointment::where('date', '=', $date)
		->with('professional','patient','schedule', 'payment', 'patient.address','patient.relative')
		->get();
		// return $dataPatients;

		return view('recepcion.excel', compact('dataPatients'));
	}

	public function monthXlsx ($date){
		$dataPatients = Appointment::where('date', 'like', $date.'%')
		->with('professional','patient','schedule', 'payment', 'patient.address','patient.relative')
		->get();

		return view('recepcion.excel', compact('dataPatients'));
	}

	/**
	 * Return patients total cant 
	 * @param any
	 */

	public function returnTotalPatients () {
		$month = date('m');
		$cantTotal = DB::table('appointments')
			->distinct('patient_id')
			->where('date', 'like','%-'.$month.'-%')
			->pluck('patient_id')
			->count();
			
		return response()->json($cantTotal);
	}

	public function discharge($id, $idProfesional){
		$patient = Patient::find($id);
		$patient->update([
			'discharge' => 1
		]);
		Patient_seguimiento::insert([
			'registro' => Carbon::now(),
			'patient_id'=>$id,
			'user_id' => $idProfesional,
			'idSeguimiento' => 7,
			'observaciones' => 'Dado de alta por un profesional',
			'activo' => 1
		]);
		return response()->json([
			'msg'=> 'Dado de alta exitósamente'
		]);
	}

	public function agePerMonth($month){
		$patients = Patient::where('created_at','like','%2022-'.$month.'%')
							//->where('birth_date','<','2004-01-01')
							->where('birth_date','>','2010-01-01')
							->get();
		return $patients->count();
	}

	public function noBirthday(){
		$noBirthdays = Patient::where('birth_date',null)
								->with('initial_psychiatric_history')
								->with('initial_psychological_history')
								->with('medical_evolutions')
								->get();

		return $noBirthdays;
	}

	public function test(){
		$patients = Patient::withCount('medical_evolutions')
							->orderBy('medical_evolutions_count','desc')
							->get();
		
		return $patients;
	}

	public function updateFaults($id,$faults){
		$patient = Patient::find($id);
		$patient->update([
			'faults' => $faults+1
		]);

		return response()->json([
			'msg' => 'success'
		]);
	}

		public function insertarTriaje(Request $request, $id){
			//print_r($request[0]['campo']);
			//var_dump( $request[0]);
			//var_dump( $request->all()); die();
			$paciente = Patient::find($id);
			$paciente ->update($request[0]);
			$triaje = $request[1];
			

			/* $triaje = new Triaje();
			$triaje->patient_id = $request[1]['patient_id'];
			$triaje->motivo = $request[1]['motivo'];
			$triaje->sintomatologia = $request[1]['sintomatologia'];
			$triaje->antecedentes = $request[1]['antecedentes'];
			$triaje->prioridad = $request[1]['prioridad'];
			$triaje->especialista = $request[1]['especialista']; 
			$triaje->save(); */
			try {
				$triaje = DB::insert('INSERT INTO `triaje`(`patient_id`, `motivo`, `sintomatologia`, `antecedentes`, `prioridad`, `especialista`,
			 `responsable`,`fv`, `fc`, `fr`, `pa`, `t`,
			 `pruebas`, `referencia`, peso, talla
			) VALUES (?,?,?,?,?,?,
			?,?,?,?,?,?,
			?,?,?,?)',
			[ $id, $triaje['motivo'],$triaje['sintomatologia'],$triaje['antecedentes'],$triaje['prioridad'], $triaje['especialista'],
			$triaje['responsable'], $triaje['fv'], $triaje['fc'], $triaje['fr'], $triaje['pa'], $triaje['t'],
			$triaje['pruebas'], $triaje['referencia'],$triaje['peso'], $triaje['talla']
			]);
		
			$ultimoID = DB::getPdo()->lastInsertId();
			return response()->json([
				'mensaje' => $ultimoID
			]);
			} catch (\Throwable $th) {
				echo $th;
			}
		}

		public function listarCumpleanos($buscar){
			$fecha = Carbon::create($buscar);
			$mes = $fecha->month;
			$año = $fecha->day;
			$pacientes = Patient::whereMonth('birth_date', '=', $mes )
			->whereDay('birth_date', '=', $año)
			//->orderBy('birth_date', 'asc')
			->orderByRaw("DAY(birth_date) ASC")
			->get();

			foreach ($pacientes as $paciente) {
				$direccion = DB::table('addresses')->where('patient_id', $paciente->id)->get();
				$paciente->address = $direccion[0];
				$confirmadas = Appointment::where('patient_id', $paciente->id)
				->where('status', 2)
				->get();
				$paciente->confirmados = count($confirmadas);
			}

			return $pacientes;
		}

		public function insertarSemaforo(Request $request){
			DB::table('semaforo')->insert([
				'patient_id'=>$request->input('id'),
				'codigo'=>$request->input('semaforo.codigo'),
				'observaciones'=>$request->input('semaforo.observaciones'),
			]);
			return response()->json([
				'msg' => 'insertado con éxito'
			]);
		
		}

		public function eliminarSemaforo($id){
			DB::table('semaforo')->where('id',$id)->delete();
			return response()->json([
				'msg' => 'eliminado'
			]);
		}

		public function editarPariente($id, Request $request){

			Relative::where('patient_id',$id)->delete();

			if($request->input('nombre')){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('nombre')),
					'phone'=> str_replace('null', '', $request->input('celular2')),
					'kinship'=> str_replace('null', '', $request->input('parentesco')),
					'patient_id' => $id
				]);
			}

			if($request->input('nombre2') ){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('nombre2')),
					'phone'=> str_replace('null', '', $request->input('celular2')),
					'kinship'=> str_replace('null', '', $request->input('parentesco2')),
					'patient_id' => $id
				]);
			}
			return response()->json([ 'msg' => 'Actualizado con éxito' ]);
		}

		public function datosPacienteSemaforo($id){
			$semaforo = DB::table('semaforo')->where('patient_id', $id )->where('activo',1)->orderBy('registro', 'desc')->get();
			return response()->json($semaforo);
		}
		
		public function verHistorialFaltas($id){
			
			$faltas = DB::table('faltas as f')
			->join('professionals as p', 'p.id', '=', 'f.idProfesional')
			->join('schedules as s', 's.id', '=', 'f.idHorario')
			->where('f.idPaciente', $id)
			->where('esFalta', 1)
			->select('f.*', 'p.name', 's.check_time as hora' )->get();

			return response()->json($faltas);
		}

		public function patientById($id){
			$patients = Patient::
			where('id', $id)
			->with('relative', 'address', 'prescriptions')
			->get();
			foreach($patients as $patient){
				$triaje = DB::table('triaje')->where('patient_id', $patient->id)->get();
				//$patient->triaje_count=$triaje->count();
				$patient->triajes = $triaje ;
				$semaforo = DB::table('semaforo')->where('patient_id', $patient->id )->where('activo',1)->orderBy('registro', 'desc')->get();
				$patient->semaforo = $semaforo;
			}

			return response()->json($patients);
		}

		public function addHobbie($id, Request $request){
			//var_dump($request->all()); die();
			$paciente = Patient::find($id);
			$paciente->update([
				'hobbies' => json_encode($request->input('misHobbies'))
			]);
	
			return response()->json([ 'msg' => 'Actualizado con éxito' ]);
		}

		public function subirArchivo(Request $request){
		
			if ($request->file('file')->isValid()) {
				$file = $request->file('file');
				$fileName = time() . '.' . $file->getClientOriginalExtension();
				$file->move(public_path('storage/archivos'), $fileName);

				$archivo = DB::table('archivos')->insertGetId([
					'patient_id' => $request->get('idPaciente'),
					'nombre' => $file->getClientOriginalName(),
					'archivo' => $fileName,
					'user_id' => $request->get('idProfesional')
				]);

				return response()->json(['archivo' =>$fileName, 'fecha'=>Carbon::now(), 'nombre' => $file->getClientOriginalName(), 'id'=> $archivo ]);
			}

			return response()->json(['error' => 'Error al subir el archivo.'], 400);

		}

		public function pedirArchivos(Request $request){
			//var_dump($request->all()); die();
			$archivos = DB::table('archivos')->where('patient_id', $request->get('idPaciente'))->get();
			return $archivos;
		}

		public function crearSOS(Request $request){
			$persona = Patient::where('id', $request->get('id'));
			$persona->update([
				'sos'=>'1'
			]);

			DB::table('sos')->insert([
				'idPaciente' => $request->get('id'),
				'idProfesional' => $request->get('idProfesional'),
				'comentarios' => $request->get('comentarios')
			]);

			return response()->json(['mensaje' => 'Paciente activado']); 
		}
		public function quitarSOS(Request $request){
			$persona = Patient::where('id', $request->get('id'));
			$persona->update([
				'sos'=>'0'
			]);

			DB::table('sos')->where('idPaciente',$request->get('id') )->update([
				'activo'=>0
			]);

			return response()->json(['mensaje' => 'Paciente activado']); 
		}

		public function pedirSOS(){
			$pacientes = DB::table('sos')
			->join('professionals', 'sos.idProfesional', '=', 'professionals.id')
			->join('patients', 'sos.idPaciente', '=', 'patients.id')
			->select('sos.*', 'professionals.name as nombreProfesional', 'patients.*', 'sos.id as idSos')
			->where('sos.activo','=', 1)
			->orderByDesc('idSos')->get();

			foreach ($pacientes as $paciente) {
				$relaciones = Relative::where('patient_id', $paciente->idPaciente)->get();
				$paciente->relaciones = $relaciones;
				$paciente->profesional = Professional::where('id', $paciente->idProfesional)->get();
			}
			return response()->json(['pacientes' => $pacientes ]);
		}

		public function crearComentarioSOS(Request $request){
			DB::table('sos_seguimientos')->insert([
				'idSos'=> $request->get('idSos'),
				'idUsuario'=> $request->get('idUsuario'),
				'comentario'=> $request->get('comentario')
			]);
			return 'ok';
		}
		public function listarComentariosSOS($idSos){
			$comentarios = DB::table('sos_seguimientos')->where('idSos', $idSos)->get();
			foreach($comentarios as $comentario){
				$profesional = DB::table('users')->where('id', $comentario->idUsuario)->first();
				$comentario->nombreUsuario = $profesional->nombre;
			}
			return $comentarios;
		}

		public function agendarNuevaCita(Request $request){
			DB::table('proximos')->insert([
				'patient_id' =>$request->get('idPaciente'),
				'professional_id' =>$request->get('idProfesional'),
				'fecha' =>$request->get('fecha')
			]);
			return 'ok';
		}

		public function pedirSeguimientos(){
			$seguimientos = DB::table('seguimientos')
			->orderBy('seguimiento', 'asc')->get();
			return $seguimientos;
		}

}
