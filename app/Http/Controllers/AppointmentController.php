<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Extra_payment;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\Professional;
use App\Models\User;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Relative;
use App\Models\Medical_evolution;
use App\Models\Reschedule;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade as PDF;
use Faker\Provider\ar_SA\Payment as Ar_SAPayment;
use Illuminate\Support\Facades\App;

class AppointmentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		return Appointment::with('professional','patient', 'payment','schedule','patient.address','patient.relative','reschedule')
		->get();
	}

	/**
	 * Return appoitments for rol recepcion
	 * @param null
	 */

	public function indexAppointments()
	{
		$date = date('Y-m-d');

		return Appointment::where('date', '=', $date)
		->where('schedule_id','!=', null)
		->with('professional','patient','payment','schedule','patient.address','patient.relative')
		->orderBy('professional_id')
		->get();
	}

	/* public function testAppoitmentsReception(){
		$date = date('Y-m-d');
		
		$appointments = Appointment::where('date', '=', $date)
		->with('professional','patient','payment','schedule','patient.address','patient.relative')
		->with('schedule', function($query){
			$query->orderBy('check_time','DESC');
		})
		->orderBy('professional_id')
		->get()->toArray();


		$appointments.sort(function(a,b){
			return parseInt(a.id) - parseInt(b.id);
		});

	} */

	/**
	 * Return appoitments for reports
	 * @param date now
	 */

	public function reportsAppointments ($date) {
		return Appointment::with('professional','patient','schedule', 'payment', 'patient.address','patient.relative')
		->orderBy('professional_id')
		->where('date', 'like', $date.'%')
		->get();
	}

	public function getprof(){
		 return Professional::with('schedules')->where('profession','Psicólogo')
		 ->orWhere('profession','Psiquiatra')
				 ->orderBy('name', 'asc')
				 ->get();
	}

	public function getschedules(){
		return Schedule::get();
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
			try {

		$paciente_prueba = Patient::where('dni',$request->get('dni'))->first();

		$condition = Patient::where('dni', '=', $request->dni)
			->with('medical_evolutions')
			->first();

		$patient_condition = "1";

		if ($condition && count($condition->medical_evolutions) !== 0) {
			$patient_condition = "2";
		} else {
			$patient_condition = "1";
		}

		if(!$paciente_prueba){
			if($request->get('name') != null){
				$patient = Patient::create([
					'name' => trim(str_replace('  ', ' ' , $request->get('name'))),
					'email'=>$request->get('email'),
					'dni' => $request->get('dni'),
					'phone' => $request->get('phone'),
					'birth_date'=>$request->get('birth_date'),
					'occupation'=>$request->get('occupation'),
					'instruction_degree'=>$request->get('instruction_degree'),
					'marital_status'=>$request->get('marital_status'),
					'type_dni'=>$request->get('type_dni')
				]);
			}
				
		   
			
			$relative = Relative::create([
				'name'=> $request->input('name') =='null' ? null: $request->input('contacto'),
				'phone'=> $request->input('phone') =='null' ? null: $request->input('contacto_celular'),
				'kinship'=> $request->input('kinship') =='null' ? null: $request->input('parentezco'),
			  'patient_id' => $patient->id
			]);

			$address= Address::create([
				'address'=> $request->get('address'),
				'district'=>$request->get('district'),
				'province'=>$request->get('province'),
				'department'=>$request->get('department'),
				'patient_id' => $patient->id
			]);

			$appointment = Appointment::create([
				'professional_id' => $request->get('professional_id'),
				'date' => $request->get('date'),
				'schedule_id' => $request->get('schedule_id'),               
				'clasification'=>$request->get('clasification'),
				'type'=>$request->get('type'),
				'patient_condition'=>$patient_condition,
				'recomendation'=>$request->get('recomendation'),
				'mode'=>$request->get('mode'),
				'link'=>$request->get('link'),
				'status'=> 1,
				'patient_id' => $patient->id
			]);

			$payment = Payment::create([
				'observation' => ' ',
				'bank' => '',
				'voucher' => '',
				'pay_status' => 1,
				'price' => $request->get('price'),
				'appointment_id' => $appointment->id
			]);

		}else{

			$appointment = Appointment::create([
				'professional_id' => $request->get('professional_id'),
				'date' => $request->get('date'),
				'schedule_id' => $request->get('schedule_id'),
				'clasification'=>$request->get('clasification'),
				'type'=>$request->get('type'),
				'patient_condition'=>$patient_condition,
				'recomendation'=>$request->get('recomendation'),
				'mode'=>$request->get('mode'),
				'link'=>$request->get('link'),
				'status'=> 1,
				'patient_id' => $paciente_prueba->id
			]);

			$payment = Payment::create([
				'observation'=>'',
				'bank'=>'',
				'voucher'=>'',
				'pay_status'=> 1,
				'price' => $request->get('price'),
				'appointment_id' => $appointment->id
			]);
			
							
			$paciente_actualizar = Patient::find($paciente_prueba->id)

			//var_dump($request->input());die();
			//$paciente_actualizar = Patient::where('id', $paciente_prueba->id)

			->update([
				'phone'=>$request->get('phone'),
				'name'=> trim(str_replace('  ', ' ' , $request->get('name'))),
				'instruction_degree'=> $request->get('instruction_degree') ?? 6,
				'gender'=> $request->get('gender') ?? 2,
				'birth_date'=> $request->input('birth_date') =='null' ? null: $request->input('birth_date'),
				'occupation'=> $request->input('occupation') =='null' ? null: $request->input('occupation'),
				'marital_status'=> $request->get('marital_status')
			]);
			/* $paciente_actualizar->name = ;
			$paciente_actualizar->instruction_degree= $request->get('instruction_degree') ?? 6;
			$genero = $request->get('gender') ?? 2;
			$paciente_actualizar->gender= $genero;
			$paciente_actualizar->birth_date= $request->get('birth_date') ;
			$paciente_actualizar->occupation= $request->get('occupation') !=='' ? $request->get('occupation') : null;
			$paciente_actualizar->marital_status= $request->get('marital_status') ?? 1;
			$paciente_actualizar->save(); */

			Relative::where('patient_id',$paciente_prueba->id)
			->update([
				'name'=> $request->input('name') =='null' ? null: $request->input('contacto'),
				'phone'=> $request->input('phone') =='null' ? null: $request->input('contacto_celular'),
				'kinship'=> $request->input('kinship') =='null' ? null: $request->input('parentezco')
			]);
			/* $parentezco->name = $request->get('contacto') !=='' ? $request->get('contacto') : null;
			$parentezco->phone = $request->get('contacto_celular') !=='' ? $request->get('contacto_celular') : null;
			$parentezco->kinship = $request->get('parentezco') !=='' ? $request->get('parentezco') : null;
			$parentezco->save(); */

			$direccion = $request->get('address') ?? '';
			$direccion_paciente = Address::where('patient_id',$paciente_prueba->id)->first();
			$direccion_paciente->address= $direccion;
			$direccion_paciente->district= $request->get('district') =='null' ? null : $request->get('district');
			$direccion_paciente->province= $request->get('province') =='null' ? null : $request->get('province');
			$direccion_paciente->department= $request->get('department') =='null' ? null : $request->get('department');
			$direccion_paciente->save();
			

		}
				//echo 'nombre: '. trim(str_replace('  ', ' ' , $request->get('name')));
		return response()->json([ 'cita'=>$appointment ]);

		} catch (\Throwable $th) {
			echo $th;
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$professional = Professional::where('id', $id)->first();

		// return $professional;

		$date = date('d-m-Y');
		$dateWeekAfter = date_create(date("d-m-Y",strtotime($date."+ 4 days")));
		$dateWeekBefore = date_create(date("d-m-Y",strtotime($date."- 1 days")));

		$consults = Appointment::where('professional_id', $professional->id)
			->whereBetween('date', [$dateWeekBefore, $dateWeekAfter])
			->with('patient', 'professional', 'schedule', 'payment')
			->with('patient.initial_psychiatric_history')
			->with('patient.initial_psychological_history')
			->orderBy('date', 'asc')
			->get();


		return response()->json([
			'antes'=> $dateWeekBefore,
			'despues'=> $dateWeekAfter,
			'consult' => $consults,
		]);
	}

	public function showKurame($id)
	{
		$professional = Professional::where('id', $id)->first();

		// return $professional;

		$date = date('d-m-Y');
		$dateWeekAfter = date_create(date("d-m-Y",strtotime($date."+ 1 days")));
		$dateWeekBefore = date_create(date("d-m-Y",strtotime($date."- 1 days")));

		$consults = Appointment::where('type', 12)
			->where('professional_id', $professional->id)
			->whereBetween('date', [$dateWeekBefore, $dateWeekAfter])
			->with('patient', 'professional', 'schedule', 'payment')
			->with('patient.initial_psychiatric_history')
			->with('patient.initial_psychological_history')
			->orderBy('date', 'asc')
			->get();


		return response()->json([
			'antes'=> $dateWeekBefore,
			'despues'=> $dateWeekAfter,
			'consult' => $consults,
		]);
	}


	/**
	 * Return search of patients
	 * @param character
	 */

	public function searchAppointment ($nombre, $profesional, $fecha, $dni)
	{

		$queryAppointments = [];
		$appointments = Appointment::with('professional','patient', 'payment', 'schedule','patient.address','patient.relative')
		->get();

		foreach($appointments as $appointment) {
			if (preg_match("/$nombre/i", $appointment->patient->name)) {
				// --- Si existen los parametros
				// ---- Si existe la variable professional
				if ($profesional && preg_match("/$profesional/i", $appointment->professional->name)) {

					// ---- Si existe la variable fecha
					if ($fecha && $appointment->date == $fecha) {
						array_push($queryAppointments, $appointment);
					}

					if ($fecha == 'null') {
						array_push($queryAppointments, $appointment);
					}
				}

				if ($profesional == 'null' && $fecha == 'null') {
					array_push($queryAppointments, $appointment);
				}
			}
						//comparar si el DNI coincide
						if( preg_match("/$dni/", $appointment->patient->dni ) ){
							array_push($queryAppointments, $appointment);
						}
		}

		return response()->json($queryAppointments);
	}

	public function searchByDateAppointment($date){
		$appointments = Appointment::where('date', $date)
						->with('professional','patient', 'payment', 'schedule','patient.address','patient.relative')
						->get();
		return response()->json($appointments);                
	}

	public function showConsult ($idUser, $idDate)
	{
		$professional = Professional::where('user_id', $idUser)->first();

		$consult = Appointment::where('appointments.professional_id', $professional->id)
			->where('date', '=', $idDate)
			->with('patient', 'professional', 'schedule', 'payment')
			->with('patient.initial_psychiatric_history')
			->with('patient.initial_psychological_history')
			->orderBy('date', 'asc')
			->get();

		return response()->json($consult);
	}

	public function getEvolutions ($id) {
		$evolutions = Appointment::where('id', $id)
			->with('medical_evolution', 'patient', 'professional')
			->with('patient.initial_psychiatric_history', 'patient.initial_psychological_history', 'patient.relative')
			->first();

		return response()->json($evolutions);
	}

	public function deleteEvolution($id){
		$evolution = Medical_evolution::find($id);
		$evolution->delete();
		return response()->json([
			'msg' => 'success'
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Appointment $appointment)
	{
		try {

		//$appointment->update($request->all());
		$appointment->where('patient_id', $request->input('dataCita.id'));

		$appointment->payment->update([
			'price' => $request->input('dataCita.payment.price'),
			'voucher_issued' => $request->input('dataCita.payment.voucher_issued'),
			'pay_status' => $request->input('caso.pago'),
			'payment_method' => $request->input('caso.moneda'),
			'voucher' => $request->input('dataCita.payment.voucher'),
			'bank' => $request->input('dataCita.payment.bank'),
			'observation' => $request->input('dataCita.payment.observation')
		]);

		if($request->input('caso.pago') === '2'){
				$pagoExtra = new Extra_payment;
				$pagoExtra->customer = $request->input('dataCita.patient.name');
				$pagoExtra->price = $request->input('dataCita.payment.price');
				$pagoExtra->moneda = $request->input('caso.moneda');
				$pagoExtra->appointment_id = $request->input('dataCita.id');
				$pagoExtra->type =5;
				$pagoExtra->observation = '';
				$pagoExtra->save();
		}

		

		if ($request->reschedule) {
			$reschedule = Reschedule::create([
				'reason' => $request->reschedule,
				'appointment_id' => $request->id
			]);
		}
		if($request->get('status') == 2){
			
		}

		return response()->json(['mensaje' => 'se actualizó la cita']);
	} catch (\Throwable $th) {
		echo $th;
	}
	}

	public function updateStatus($idAppointment,$valueStatus, Request $request){
		$appointment = Appointment::find($idAppointment);

		function updateFieldStatus($appointment, $valueStatus){
			$appointment->update([
				'status' => $valueStatus
			]);
		}

		if($valueStatus == 3){
			$appointment->update([
				'schedule_id' => null
			]);
			updateFieldStatus($appointment, $valueStatus);
		}else if($valueStatus == 2){
			$medicalEvolutionExistents = Medical_evolution::where('patient_id', $request->get('patient_id'))
														 ->where('professional_id', $request->get('professional_id'))
														 ->where('date',$request->get('date'))
														 ->get();                   
			if(count($medicalEvolutionExistents) == 0){
				Medical_evolution::create([
					'type' => $request->get('type'),
					'date' => $request->get('date'),
					'auth' => 0,
					'patient_id'=> $request->get('patient_id'),
					'professional_id'=> $request->get('professional_id')
				]);
			}
			updateFieldStatus($appointment, $valueStatus);
		}else{
			updateFieldStatus($appointment, $valueStatus);
		}
		
		return response()->json(['mensaje' => 'Se actualizó el estado la cita']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Appointment $appointment)
	{
		DB::table('faltas')->insert([
			'idPaciente' => $appointment->patient_id,
			'idProfesional' => $appointment->professional_id,
			'idHorario' => $appointment->schedule_id,
			'fecha' => $appointment->date
		]);
		$patient = Patient::find( $appointment->patient_id );
		$patient->increment('faults');
		$patient->save();
		$appointment->delete();
	}

	/**
	 *  Return professionals and appointments
	 * @param id professional
	 * 
	 */

	public function getProfApo($id){
		/* $patients = Patient::with('medical_evolutions')->get();
		$evolutions = [];


		foreach ($patients as $medical_evolution) {
			foreach ($medical_evolution->medical_evolutions as $evolution) {
				if ($evolution->professional_id == $id) {
					array_push($evolutions, $evolution);
				}
			}
		}
		
		$evolutionProfessional = [];
		$patientsEvolutions = [];

		foreach ($patients as $clave => $patient) {
			unset($patient->medical_evolutions);
			foreach ($evolutions as $key => $evolution) {
				if ($patient->id == $evolution->patient_id) {
					array_push($evolutionProfessional, $evolution);
					unset($evolutions[$key]);
				}
			}

			$patient->evolution = $evolutionProfessional;

			if ($patient->evolution != []) {
				array_push($patientsEvolutions, $patient);
			}

			$evolutionProfessional = [];
		} */

		$no_confirmed = Appointment::where('status',1)
		->where('date', 'like', '%'.date('m').'%')
		->where('professional_id', $id)->get()->count();
		$confirmed = Appointment::where('status',2)
		->where('date', 'like', '%'.date('m').'%')
		->where('professional_id', $id)->get()->count();
		$cancelled = Appointment::where('status',3)
		->where('date', 'like', '%'.date('m').'%')
		->where('professional_id', $id)->get()->count();
		
		

		return response()->json([
			'patients' => [],
			'no_confirmed' => $no_confirmed,
			'confirmed' => $confirmed,
			'cancelled' => $cancelled
		]);


	}

	public function getAllPatients(){
		$patients = Patient::select('patients.id','patients.name', 'patients.phone', DB::raw('COUNT(medical_evolutions.id) as cantidad'))->leftJoin('medical_evolutions', 'patients.id', '=', 'medical_evolutions.patient_id')->groupBy('patients.id')->groupBy('patients.name')->groupBy('patients.phone')->orderBy('cantidad', 'asc')->take(1040)->get();

		return response()->json($patients);
	}

	public function getPatientsPerMonth($date,$id){
		$patientsPerMonth = Appointment::where('professional_id',$id)->where('date','like',$date.'%')->get()->count();
		$appointments = Appointment::where('professional_id', $id)
			->where('date','like',$date.'%')
			->with('patient')
			->with(['patient.medical_evolutions'=> function($query) use($id, $date){
				$query->where('professional_id','=', $id)
					  ->where('date','like',$date.'%');
			}])
			->with(['payment'=> function($query) {
				$query->where('payments.price','!=', null);
			}])
			->orderBy('date')
			->get();
		$extra_payments = Extra_payment::where('date','like',$date.'%')->get();     
		return response()->json([
			'patientsPerMonth' => $patientsPerMonth,
			'appointments' => $appointments,
			'extra_payments' => $extra_payments
		]);
	}

	public function updateMode($id){
		$appointment = Appointment::find($id);
		if($appointment->mode == 1){
			$appointment->update([
				'mode' => 2
			]);
		}else{
			$appointment->update([
				'mode' => 1
			]);
		}
		return response()->json([
			'msg' => 'success'
		]);
	}
	
	public function getPatientsPerDay($date,$id){
		$patientsPerMonth = Appointment::where('professional_id',$id)->where('date','like',$date.'%')->get()->count();
		$appointments = Appointment::where('professional_id', $id)
			->where('date','=',$date)
			->with('patient')
			->with(['patient.medical_evolutions'=> function($query) use($id, $date){
				$query->where('professional_id','like', $id)
					  ->where('date','=',$date);
			}])
			->with(['payment'=> function($query) {
				$query->where('payments.price','!=', null);
			}])
			->get();
		$extra_payments = Extra_payment::where('date','like',$date)->get();
		return response()->json([
			'patientsPerMonth' => $patientsPerMonth,
			'appointments' => $appointments,
			'extra_payments' => $extra_payments
		]);
	}

	/** 
	 * return total patients of month 
	 * @param any
	 */ 

	public function getPatientsMonth($date) 
	{
		$appointments = Appointment::where('date', 'like', $date.'%')
			->where('status', '=', 2)
			->get();

		foreach ($appointments as $appointment) {
			$appointment = $appointment->patient;
		} 
		
		$dataAppointment = [];
		
		foreach ($appointments as $appointment) {
			array_push($dataAppointment, $appointment->patient);
		}
		
		asort($dataAppointment);
		
		$objectCountAppointment  = [];
		$count = 0;
		$idInitial = null;
		$positionArr = 0;

		foreach ($dataAppointment as $key => $data) {
			if ($data->id == $idInitial) {
				if ($data->cant) {
					$count = $data->cant;
				} else {
					$count++;
				}

				$data->cant = $count;

				foreach ($objectCountAppointment as $app) {
					if ($app->id == $data->id) {
						$app->cant = $data->cant;
					}
				}
			} else {
				$positionArr = $key;
				$idInitial = $data->id;
				$count = 1;
				$data->cant = $count;

				if ($count == 1) {
					array_push($objectCountAppointment, $data);
				}
			}
		}

		$sumTotal = null;

		foreach ($objectCountAppointment as $patient) {
			$sumTotal += $patient->cant;
		}

		usort($objectCountAppointment, object_sorter('cant', 'DESC'));
		return response()->json([
			'pacientes' => $objectCountAppointment,
			'totalPacientes' => $sumTotal
		]);
	}

	public function pdfTriaje($id){
		$triaje = DB::table('triaje')->find($id);
		$paciente = Patient::find($triaje->patient_id);
		$apoderado = Relative::where('patient_id', $triaje->patient_id )->first();
		$especialista = Professional::find($triaje->especialista)->name;
		
		$pdf = PDF::loadView('interno.triaje', compact('triaje', 'paciente', 'apoderado', 'especialista'));
		$pdf -> setPaper('a5');
		return $pdf->stream('triaje.pdf');
	}

	public function createTicket($id){
		$appointment = Appointment::find($id);
		$patient = Patient::where('id',$appointment->patient_id)->get();
		$professional = Professional::where('id',$appointment->professional_id)->get();
		$schedule = Schedule::where('id',$appointment->schedule_id)->get();
		$pdf = PDF::loadView('recepcion.ticket', compact('appointment','patient','professional', 'schedule'));
		$pdf->setPaper('a7');
		return $pdf->stream('ticket.pdf');
	}

	public function cupon($id){
		$appointment = Appointment::find($id);
		$patient = Patient::where('id',$appointment->patient_id)->with('address')->get();
		$professional = Professional::where('id',$appointment->professional_id)->get();
		$schedule = Schedule::where('id',$appointment->schedule_id)->get();
		$payment = Payment::where('appointment_id', $appointment->id)->get();
		$pdf = PDF::loadView('recepcion.cupon', compact('appointment','patient','payment','schedule'));
		$pdf->setPaper('a7');
		return $pdf->stream('cupon.pdf');
	}

	public function pdfExtraCupon($id){
		$extra_payment = Extra_payment::find($id);
		$pdf = PDF::loadView('recepcion.cupon_extra', compact('extra_payment'));
		$pdf->setPaper('a7');
		return $pdf->stream('cupon_extra.pdf');
	}

	public function getDataForCharts($month){
		 
		$names = DB::table('payments')
				->select(DB::raw('SUM(payments.price) as total_price'), 'professionals.name')
				->join('appointments', 'appointments.id', '=', 'payments.appointment_id')
				->where('appointments.date','like',$month.'%')
				->where('payments.price', '!=', null)
				->join('professionals', 'appointments.professional_id', '=', 'professionals.id')
				->groupBy('name')
				->get()
				->pluck('name');

		$prices = DB::table('payments')
				->select(DB::raw('SUM(payments.price) as total_price'), 'professionals.name')
				->join('appointments', 'appointments.id', '=', 'payments.appointment_id')
				->where('appointments.date','like',$month.'%')
				->where('payments.price', '!=', null)
				->join('professionals', 'appointments.professional_id', '=', 'professionals.id')
				->groupBy('name')
				->get()
				->pluck('total_price');

		return response()->json([
			'names' => $names,
			'prices' => $prices
		]);
	}

		public function getDepartamentos(){
			$departamentos = DB::table('ubdepartamento')->select('idDepa', 'departamento' )->get();
			$provincias = DB::table('ubprovincia')->select('idDepa', 'provincia', 'idProv' )->get();
			$distritos = DB::table('ubdistrito')->select('idDist', 'distrito', 'idProv' )->get();
			return array( 'departamentos'=>$departamentos, 'provincias' => $provincias, 'distritos'=>$distritos);
		}
}

function object_sorter($clave,$orden=null) {
	return function ($a, $b) use ($clave,$orden) {
		  $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
		  return $result;
	};
}
