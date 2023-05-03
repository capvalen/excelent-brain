<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Triaje;
use App\Models\Relative;
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
		$patient = Patient::with('initial_psychiatric_history', 'initial_psychological_history')
			->whereNotNull('dni')
			->get();
		return response()->json($patient);
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
					'phone'=>$request->input('paciente.phone'),
					'name'=> trim(str_replace('  ', ' ' , $request->input('paciente.name'))),
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
		$patients = Patient::with('relative', 'address', 'prescriptions')
		->latest('created_at')->take(20)
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
	public function searchPatientByNameDni ($nombre){
		$patients = Patient::where('name', 'LIKE', "%".$nombre ."%")
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
		}

		return response()->json($patients);
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

		Relative::find($request->input('relative.id'))
		->update([
			'name' => $request->input('relative.name'),
			'phone' => $request->input('relative.phone'),
			'kinship' => $request->input('relative.kinship')
		]);

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
	public function buscar($dni)
	{
	  $paciente = Patient::where('dni',$dni)->with('address')->first();
	 
		//code...
		if( $paciente ){
			$relaciones = Relative::where('patient_id', $paciente->id)->first();
			return response()->json([
				'patient'=>$paciente,
				'relacion' => $relaciones
				]);
		}else{
			return response()->json([ 'patient'=>$paciente ]);
		}
	
			

	  
	}

	public function showEvolution ($idPaciente) {
		$evoluciones = Patient::where('id',$idPaciente)
		->with('cies', 'initial_psychiatric_history', 'initial_psychological_history', 'relative', 'medical_evolutions', 'appointments', 'prescriptions')
		->with('medical_evolutions.professional')
		->first();

		//return response()->json([$evoluciones]); die();
		
		$triaje = DB::table('triaje')->where('patient_id', $evoluciones->id)->get();
		$evoluciones->triajes = $triaje ;
		$examenes = DB::table('exams')->where('patient_id', $evoluciones->id)->get();
		$evoluciones->examenes = $examenes ;

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

	public function discharge($id){
		$patient = Patient::find($id);
		$patient->update([
			'discharge' => 1
		]);
		return response()->json([
			'msg'=> 'success'
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
			//$temp = $request->all();
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
			 `pruebas`, `referencia`
			) VALUES (?,?,?,?,?,?,
			?,?,?,?,?,?,
			?,?)',
			[ $triaje['patient_id'],$triaje['motivo'],$triaje['sintomatologia'],$triaje['antecedentes'],$triaje['prioridad'], $triaje['especialista'],
			$triaje['responsable'], $triaje['fv'], $triaje['fc'], $triaje['fr'], $triaje['pa'], $triaje['t'],
			$triaje['pruebas'], $triaje['referencia']
			]);
		
			$ultimoID = DB::getPdo()->lastInsertId();
			return response()->json([
				'mensaje' => $ultimoID
			]);
			} catch (\Throwable $th) {
				echo $th;
			}
		}

		public function listarCumpleanos($mes){
			$pacientes = Patient::whereMonth('birth_date', $mes )->orderBy('name', 'asc')->get();
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

}
