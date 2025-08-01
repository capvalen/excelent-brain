<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Limbo;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\Professional;
use App\Models\User;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Relative;
use App\Models\Medical_evolution;
use App\Models\Payment_method;
use App\Models\Precio;
use App\Models\Reschedule;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade as PDF;
use Faker\Provider\ar_SA\Payment as Ar_SAPayment;
use Illuminate\Support\Facades\App;
use PhpParser\Node\Stmt\TryCatch;

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

		try {
			$citas =  Appointment::where('date', '=', $date)
			->where('schedule_id','!=', null)
			->with(['professional','patient','payment','schedule','patient.address','patient.relative',
			'patient.semaforo' => function ($query){
				$query->orderBy('registro', 'desc');
				}
			])
			->get()
			->sortBy(
				['date', 'desc'],
				['schedule.check_time', 'desc']
			)
			->values()->all();

			foreach($citas as $cita){
				//Ver si tiene falta
				if($cita->status == '3'){
					$cita->faltas = DB::table('faltas')->where('idCita', $cita->id)->get();
				}
				if($cita->status == '4'){
					$cita->faltas = Reschedule::where('appointment_id', $cita->id)->get();
				}
			}

			return $citas;
		} catch (\Throwable $th) {
			echo $th;
		}
		
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
		 $profesionales = Professional::with('schedules')
		 	->where('activo', '=', 1)
			->orderBy('idProfesion', 'asc')
			->orderBy('name', 'asc')
			->get();

			$profesionales = $profesionales->map(function ($item) {
				$item['horarios'] = [];
				return $item;
			});

			return response()->json( $profesionales );
	}

	public function getschedules(){
		return Schedule::get();
	}
	public function store(Request $request)
	{
		try {
			//code...
		
			//var_dump($request->all());die();

			$paciente_prueba = Patient::where('dni',$request->get('dni'))->first();


		/* $condition = Patient::where('dni', '=', $request->dni)
		->with('medical_evolutions')
		->with([ 'appointments' => function ($query) use ($clas) {
			$query->where('clasification', $clas );
		}])
		->first(); */
		
		
	

		/* if ($condition && count($condition->medical_evolutions) !== 0) {
			$patient_condition = "2";
		} else {
			$patient_condition = "1";
		} */

		if(!$paciente_prueba){
			$patient_condition = "1";

			if($request->get('name') != null){
				$patient = Patient::create([
					'name' => trim(str_replace('  ', ' ' , $request->get('name'))),
					'nombres' => trim(str_replace('  ', ' ' , $request->get('nombres'))),
					'email'=>$request->get('email') ?? '',
					'dni' => $request->get('dni'),
					'phone' => $request->get('phone')?? '',
					'birth_date'=>$request->get('birth_date'),
					'occupation'=>$request->get('occupation'),
					'instruction_degree'=>$request->get('instruction_degree'),
					'marital_status'=> $request->get('marital_status')  ?? 0,
					'type_dni'=>$request->get('type_dni'),
					'etiqueta'=>$request->get('etiqueta'),
					'new_status'=>$request->get('new_status'),
					'recomendation'=>$request->get('recomendation'),
					'recomendacion_comentario'=>$request->get('recomendacion_comentario'),
				]);
				$paciente_prueba = $patient;
			}
			
			$relative = Relative::create([
				'name'=> $request->input('contacto') ?? '',
				'phone'=> $request->input('contacto_celular') ?? '',
				'kinship'=> $request->input('parentezco') ?? '',
			  'patient_id' => $patient->id
			]);

			if($request->input('contacto2') ){
				Relative::create([
					'name'=> $request->input('contacto2'),
					'phone'=> $request->input('contacto_celular2'),
					'kinship'=> $request->input('parentezco2'),
					'patient_id' => $patient->id
				]);
			}

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
				'patient_id' => $patient->id,
				'formato_nuevo' => $request->get('formato_nuevo'),
				'recomendacion_comentario' => $request->get('recomendacion_comentario')
			]);

			$payment = Payment::create([
				'observation' => '',
				'bank' => '',
				'voucher' => $request->get('comprobante'),
				'pay_status' => 1,
				'price' => $request->get('price'),
				'appointment_id' => $appointment->id,
				'continuo' => $patient_condition,
				'user_id' => $request->get('user_id'),
				'rebaja' => $request->get('rebaja'),
				'motivoRebaja' => $request->get('motivoRebaja'),
				'descuento' => $request->get('descuento'),
				'motivoDescuento' => $request->get('motivoDescuento'),
				'adelanto' => $request->get('adelanto'),
				'razonAdelanto' => $request->get('razonAdelanto'),
				'idSede' => $request->get('idSede')
			]);

			if( $request->get('adelanto') > 0 ){
				$pagoExtra = new Extra_payment;
				$pagoExtra->customer = $patient->name .' '.$patient->nombres;
				$pagoExtra->price = $request->get('adelanto');
				$pagoExtra->moneda = $request->get('monedaAdelanto');
				$pagoExtra->voucher = '';
				$pagoExtra->appointment_id = $appointment->id;
				$pagoExtra->type = 8;
				$pagoExtra->observation = '';
				$pagoExtra->continuo = $patient_condition;
				$pagoExtra->user_id = $request->get('user_id');
				$pagoExtra->idSede = $request->get('idSede');
				$pagoExtra->save();
			}

		}else{

			$citas = DB::table('appointments as a')
			->join('medical_evolutions as m', 'm.patient_id', '=', 'a.patient_id')
			->where('a.patient_id' , '=', $paciente_prueba->id)
			->where('m.activo', '=', 1)
			->where('a.clasification' , '=', $request->get('clasification'))->get();

				/* $citas = Appointment::where('patient_id', '=', $paciente_prueba->id)
				->where('clasification', '=', $request->get('clasification'))
				->with('medical_evolutions')->first(); */
			$patient_condition = ( count($citas) !==0 ) ? '2': '1';
			//var_dump( 'conteo '. count($citas) ); die();

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
				'patient_id' => $paciente_prueba->id,
				'formato_nuevo' => $request->get('formato_nuevo'),
				'recomendacion_comentario' => $request->get('recomendacion_comentario')
			]);

			$payment = Payment::create([
				'observation'=>'',
				'bank'=>'',
				'voucher' => $request->get('comprobante'),
				'pay_status'=> 1,
				'price' => $request->get('price'),
				'appointment_id' => $appointment->id,
				'continuo' => $patient_condition,
				'user_id' => $request->get('user_id'),
				'rebaja' => $request->get('rebaja'),
				'motivoRebaja' => $request->get('motivoRebaja'),
				'descuento' => $request->get('descuento'),
				'motivoDescuento' => $request->get('motivoDescuento'),
				'adelanto' => $request->get('adelanto'),
				'razonAdelanto' => $request->get('razonAdelanto'),
				'idSede' => $request->get('idSede')
			]);
			
			//var_dump($request->input());die();
			//$paciente_actualizar = Patient::where('id', $paciente_prueba->id)
			$paciente_actualizar = Patient::find($paciente_prueba->id)
			->update([
				'phone' => $request->get('phone') ?? '',
				'email'=>$request->get('email') ?? '',
				'name'=> trim(str_replace('  ', ' ' , $request->get('name'))),
				'nombres'=> trim(str_replace('  ', ' ' , $request->get('nombres'))),
				'instruction_degree'=> $request->get('instruction_degree') ?? 6,
				'gender'=> $request->get('gender') ?? 2,
				'birth_date'=> $request->input('birth_date') =='null' ? null: $request->input('birth_date'),
				'occupation'=> $request->input('occupation') =='null' ? null: $request->input('occupation'),
				'marital_status'=> $request->get('marital_status')  == 'null' ? 0 : $request->get('marital_status'),
				'phone'=>$request->get('phone'),
				'etiqueta'=>$request->get('etiqueta'),
				'new_status'=>$request->get('new_status'),
				'recomendation'=>$request->get('recomendation'),
				'recomendacion_comentario'=>$request->get('recomendacion_comentario'),
			]);
			/* $paciente_actualizar->name = ;
			$paciente_actualizar->instruction_degree= $request->get('instruction_degree') ?? 6;
			$genero = $request->get('gender') ?? 2;
			$paciente_actualizar->gender= $genero;
			$paciente_actualizar->birth_date= $request->get('birth_date') ;
			$paciente_actualizar->occupation= $request->get('occupation') !=='' ? $request->get('occupation') : null;
			$paciente_actualizar->marital_status= $request->get('marital_status') ?? 1;
			$paciente_actualizar->save(); */

			Relative::where('patient_id',$paciente_prueba->id)->delete();

			if($request->input('contacto')){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('contacto')),
					'phone'=> str_replace('null', '', $request->input('contacto_celular')),
					'kinship'=> str_replace('null', '', $request->input('parentezco')),
					'patient_id' => $paciente_prueba->id
				]);
			}

			if($request->input('contacto2') ){
				Relative::create([
					'name'=> str_replace('null', '', $request->input('contacto2')),
					'phone'=> str_replace('null', '', $request->input('contacto_celular2')),
					'kinship'=> str_replace('null', '', $request->input('parentezco2')),
					'patient_id' => $paciente_prueba->id
				]);
			}
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

			if( $request->get('adelanto') > 0 ){
				$pagoExtra = new Extra_payment;
				$pagoExtra->customer = trim($paciente_prueba->name . ' '. $paciente_prueba->nombres);
				$pagoExtra->price = $request->get('adelanto');
				$pagoExtra->moneda = $request->get('monedaAdelanto');
				$pagoExtra->voucher = '';
				$pagoExtra->appointment_id = $appointment->id;
				$pagoExtra->type = 8;
				$pagoExtra->observation = trim($request->get('motivoRebaja') . ' ' . $request->get('motivoDescuento'). ' ' .$request->get('razonAdelanto'));
				$pagoExtra->continuo = $patient_condition;
				$pagoExtra->user_id = $request->get('user_id');
				$pagoExtra->idSede = $request->get('idSede');
				$pagoExtra->save();
			}
			

		}

		if($request->get('price') == 0){
			$appointment->update([
				'status' => 2, //confirmado
			]);
			$payment->update([
				'pay_status' => 2 //pagado
			]);
			Medical_evolution::create([
				'type' => $request->input('type'),
				'date' => $request->input('date'),
				'auth' => 0,
				'patient_id'=> $paciente_prueba->id,
				'professional_id'=> $request->input('professional_id'),
				'schedule' => $request->get('check_time'),
			]);

			//Agregar por defecto a extra payments
			$pagoExtra = new Extra_payment;
			$pagoExtra->customer =  $request->get('name') .' '. $request->get('nombres');
			$pagoExtra->price =0;
			$pagoExtra->moneda = 1;
			$pagoExtra->voucher = null;
			$pagoExtra->appointment_id = $appointment->id;
			$pagoExtra->type = 16;
			$pagoExtra->observation = '';
			$pagoExtra->continuo = $patient_condition;
			$pagoExtra->user_id = $request->get('user_id');
			$pagoExtra->save();
		}

		$contarCitas = Medical_evolution::where('activo', 1)
		->where('patient_id', $paciente_prueba->id );
		if ($contarCitas->count() == 1) {
			// Obtener el único registro
			$cita = $contarCitas->first();
			$cita->update([ 'content' => 'Primera evolución' ]);
		}

				//echo 'nombre: '. trim(str_replace('  ', ' ' , $request->get('name')));
		return response()->json([ 'cita'=>$appointment, 'estado' => $request->get('price') ]);
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
			->with('patient', 'professional', 'schedule', 'payment', 'precio')
			->with('patient.initial_psychiatric_history')
			->with('patient.initial_psychological_history')
			->orderBy(function ($query) {
				$query->selectRaw('check_time')
					->from('schedules')
					->whereColumn('schedules.id', 'appointments.schedule_id');
    	})->get();
				
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

		/* $citas  = Appointment::with('professional',)
		->with([ 'patient'  => function($query) use($nombre, $dni) {
			$query->where('name', 'like', $nombre.'%')
			->orWhere('dni', '=', $dni);
		}])
		->orderBy('date', 'desc')
		->orderBy('professional_id')
		->get(); */
		
		$appointments =Appointment::
		join('patients as p', 'p.id', '=', 'appointments.patient_id')
		->select('appointments.*', 'appointments.id as idCita') // Agregar la columna raw aquí
		->with('professional','patient', 'payment', 'schedule','patient.address','patient.relative')
		->where(DB::raw("CONCAT(p.name, ' ', p.nombres)"), 'like', '%'. $nombre . '%')
		->orWhere(DB::raw("CONCAT(p.nombres, ' ', p.name)"), 'like', $nombre . '%')
		->orWhere('p.dni', $dni)
		/* ->orderBy('appointments.date', 'desc')
		->orderBy('appointments.professional_id') */
		->get()
		->sortBy([
			['date', 'desc'],
			['schedule.check_time', 'desc']
		])
		->values()->all();
		

		foreach($appointments as $cita){
			//Ver si tiene falta
			if($cita->status == '3' || $cita->status == '5' ){
				$cita->faltas = DB::table('faltas')->where('idCita', $cita->idCita)->get();
			}
			if($cita->status == '4'){
				$cita->faltas = Reschedule::where('appointment_id', $cita->idCita)->get();
			}
		}
		/*->orderBy(function ($query) {
			$query->selectRaw('check_time')
				->from('schedules')
				->whereColumn('schedules.id', 'appointments.schedule_id');
		})*/
		
		return $appointments; die(); //Codigo de abajo inservible para lo que solicita la busqueda por nombres

		foreach($appointments as $appointment) {
			/* if (preg_match("/$nombre/i", $appointment->patient->name)) {
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
			} */
			//comparar si el DNI coincide
			if( preg_match("/$dni/", $appointment->patient->dni ) ){
				array_push($queryAppointments, $appointment);
			}

			//Ver si tiene falta
			if($appointment->status == '3' || $appointment->status == '5' ){
				$faltas = DB::table('faltas')->where('idCita', $appointment->id)->get();
			}elseif($appointment->status == '4'){
				$appointment->faltas = Reschedule::where('appointment_id', $appointment->id)->get();
			}else{
				$faltas = [];
			}
			array_push($queryAppointments, array( 'faltas' => $faltas) );
		}

		return response()->json($queryAppointments);
	}

	public function searchByDateAppointment($date){
		$appointments = Appointment::where('date', $date)
			->with('professional','patient', 'payment', 'schedule','patient.address','patient.relative', 'patient.semaforo')
			/* ->orderBy('professional_id')
			->orderBy('check_time', 'desc') */
			->get()
			->sortBy([
				['date', 'desc'],
				['schedule.check_time', 'desc']
			])
			->values()->all();

		foreach($appointments as $appointment){
			//Ver si tiene falta
			if($appointment->status == '3' || $appointment->status == '5' ){
				$appointment->faltas = DB::table('faltas')->where('idCita', $appointment->id)->get();
			}
			if($appointment->status == '4'){
				$appointment->faltas = Reschedule::where('appointment_id', $appointment->id)->get();
			}
		}
		return response()->json($appointments);                
	}

	public function showConsult ($idUser, $idDate)
	{
		$professional = Professional::where('user_id', $idUser)->first();

		$consults = Appointment::where('professional_id', $professional->id)
			->where('date', '=', $idDate)
			->with('patient', 'professional', 'schedule', 'payment', 'precio')
			->with('patient.initial_psychiatric_history')
			->with('patient.initial_psychological_history')
			->orderBy(function ($query) {
				$query->selectRaw('check_time')
					->from('schedules')
					->whereColumn('schedules.id', 'appointments.schedule_id');
    	}, 'desc')->get();

		return response()->json($consults);
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
		//var_dump($request->input('dataCita')); die();
		try {

		//$appointment->update($request->all());
		$appointment->where('patient_id', $request->input('dataCita.id'));

		$appointment->payment->update([
			'price' => $request->input('dataCita.payment.price'),
			'voucher_issued' => $request->input('dataCita.payment.voucher_issued'),
			'pay_status' => $request->input('caso.pago'),
			'payment_method' => $request->input('caso.moneda'),
			'voucher' => $request->input('caso.comprobante'),
			'bank' => $request->input('dataCita.payment.bank'),
			'observation' => $request->input('dataCita.payment.observation'),
			'user_id'=>$request->input('caso.user_id')
		]);

		if($request->input('caso.pago') == '2'){
			$pagoExtra = new Extra_payment;
			$pagoExtra->customer = $request->input('dataCita.patient.name');
			$pagoExtra->price = $request->input('dataCita.payment.price');
			$pagoExtra->moneda = $request->input('caso.moneda');
			$pagoExtra->voucher = $request->input('caso.comprobante');
			$pagoExtra->appointment_id = $request->input('dataCita.id');
			$pagoExtra->type =5;
			$pagoExtra->observation = $request->input('dataCita.payment.observation');
			$pagoExtra->continuo = $request->input('caso.continuo');
			$pagoExtra->user_id = $request->input('caso.user_id');
			$pagoExtra->save();
		}else{
			$pagoExtra = Extra_payment::where('appointment_id', $request->input('dataCita.id') );
			$pagoExtra->update([
				'moneda' => $request->input('caso.moneda'),
				'voucher' => $request->input('caso.comprobante'),
				'observation' => $request->input('dataCita.payment.observation'),
				'user_id'=> $request->input('caso.user_id')
			]);
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

	public function pagarCita(Request $request, Appointment $appointment){
		//var_dump($request->all()); die();
		
		//$appointment->update($request->all());
		$appointment = Appointment::find($request->input('dataCita.id'));

		$appointment->payment->update([
			/* 'price' => $request->input('dataCita.payment.price'),
			'voucher_issued' => $request->input('dataCita.payment.voucher_issued'), */
			'pay_status' => $request->input('caso.pago'),
			//'payment_method' => $request->input('caso.moneda'),
			'voucher_issued' => $request->input('caso.comprobante'),
			'bank' => $request->input('dataCita.payment.bank'),
			'observation' => $request->input('dataCita.payment.observation'),
			'user_id'=>$request->input('caso.user_id'),
			/* 'rebaja' => $request->input('caso.rebaja'),
			'motivoRebaja' => $request->input('caso.motivoRebaja'), */
			'descuento' => $request->input('caso.rebaja'),
			'motivoDescuento' => $request->input('caso.motivoDescuento'),
			'payment_method'=> $request->input('caso.moneda')
		]);

		if( $request->input('caso.pago') == '3' ){ //en caso de adelantos por vista cuaderno

			$appointment->payment->update([
				'pay_status' => 1, //solo se paga el adelanto, por eso queda pendiente de completar el pago
				'adelanto' => $appointment->payment->adelanto + $request->input('caso.monto_adelanto'),
				'price' => $appointment->payment->price - $request->input('caso.monto_adelanto')
			]);

			/*$pago = Payment::find($request->input('dataCita.payment.id'));
			$pago->update([
				'adelanto' => $pago->adelanto + $request->input('caso.monto_adelanto')				
			]);*/

			$pagoExtra = new Extra_payment;
			$pagoExtra->customer = $request->input('dataCita.patient.name') .' ' . $request->input('dataCita.patient.nombres');
			$pagoExtra->price = $request->input('caso.monto_adelanto'); //el precio de adelanto
			$pagoExtra->moneda = $request->input('caso.moneda');
			$pagoExtra->voucher_issued = $request->input('caso.comprobante');
			$pagoExtra->appointment_id = $request->input('dataCita.id');
			$pagoExtra->type = 8;
			$pagoExtra->observation = $request->input('dataCita.payment.observation');
			$pagoExtra->continuo = $request->input('caso.continuo');
			$pagoExtra->user_id = $request->input('caso.user_id');
			$pagoExtra->rebaja = $request->input('dataCita.payment.rebaja');
			$pagoExtra->motivoRebaja = $request->input('dataCita.payment.motivoRebaja');
			$pagoExtra->descuento = $request->input('dataCita.descuento');
			$pagoExtra->motivoDescuento = $request->input('dataCita.motivoDescuento');
			$pagoExtra->idSede = $request->input('idSede');
			$pagoExtra->save();
		}		
		else if($request->input('caso.pago') == '2'){ // Modo pagado y adelanto
			
				$pagoExtra = new Extra_payment;
				$pagoExtra->customer = $request->input('dataCita.patient.name') .' ' . $request->input('dataCita.patient.nombres');
				$pagoExtra->price = $request->input('dataCita.payment.price');
				$pagoExtra->moneda = $request->input('caso.moneda');
				$pagoExtra->voucher_issued = $request->input('caso.comprobante');
				$pagoExtra->appointment_id = $request->input('dataCita.id');
				$pagoExtra->type =5;
				$pagoExtra->observation = $request->input('dataCita.payment.observation');
				$pagoExtra->continuo = $request->input('caso.continuo');
				$pagoExtra->user_id = $request->input('caso.user_id');
				$pagoExtra->rebaja = $request->input('dataCita.payment.rebaja');
				$pagoExtra->motivoRebaja = $request->input('dataCita.payment.motivoRebaja');
				$pagoExtra->descuento = $request->input('dataCita.descuento');
				$pagoExtra->motivoDescuento = $request->input('dataCita.motivoDescuento');
				$pagoExtra->idSede = $request->input('idSede');
				$pagoExtra->save();
				if($request->input('caso.rebaja')>0){
					$pagoExtra -> rebaja = $request->input('caso.rebaja');
					$pagoExtra -> motivoRebaja = $request->input('caso.motivoRebaja');
					$pagoExtra->save();
				}
			
				//print_r($appointment->status );

				//Debemos de confirmar si esta confirmado para habilitar al profesional
				if( $appointment->status == '2'){
					$medicalEvolutionExistents = Medical_evolution::where('patient_id', $request->input('dataCita.patient.id'))
					->where('activo', 1)
					/* ->where('professional_id', $request->input('dataCita.professional.id'))
					->where('date',$request->input('dataCita.date')) */
					->get();

					$primera = count($medicalEvolutionExistents) == 0 ? 'Primera evolución -Sist-': '';
					$tpago = Precio::where('id', $request->input('dataCita.type'))->first();

					Medical_evolution::create([
						'type' => $tpago->idClasificacion,
						'date' => $request->input('dataCita.date'),
						'auth' => 0,
						'patient_id'=> $request->input('dataCita.patient.id'),
						'professional_id'=> $request->input('dataCita.professional.id'),
						'schedule' => $request->input('dataCita.schedule.check_time'),
						'content' =>  $primera
					]);
					
				}

		}else{ // No se cancela
			$pagoExtra = Extra_payment::where('appointment_id', $request->input('dataCita.id') );
			$pagoExtra->update([
				'moneda' => $request->input('caso.moneda'),
				'voucher_issued' => $request->input('caso.comprobante'),
				'observation' => $request->input('dataCita.payment.observation'),
				'user_id'=> $request->input('caso.user_id'),
				'activo' => 0
			]);
			Medical_evolution::where('patient_id', $request->input('dataCita.patient_id'))
			->where('professional_id', $request->input('dataCita.professional_id'))
			->where('date', $request->input('dataCita.date') )
			->where('schedule', $request->input('dataCita.schedule.check_time') )
			->update([
				'activo' => 0
			]);
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
	}

	public function reprogramado(Request $request, Appointment $appointment)
	{
		//var_dump($request->all()); die();
		try {
		//$appointment->update($request->all());
		$limbo = Limbo::where('appointment_id', $request->get('id') );
		if($limbo) $limbo->delete();

		$cita = Appointment::find($request->get('id'));
		$cita->update([
			'status' => 4
		]);
		//return var_dump( $cita->id ); die();

		$nuevaCita = Appointment::create([
			'professional_id' => $request->get('professional_id'),
			'date' => $request->get('date'),
			'schedule_id' => $request->get('schedule_id'),
			'clasification'=>$request->get('clasification'),
			'type'=>$request->get('type'),
			'patient_condition'=>$request->get('patient_condition'),
			'recomendation'=>$request->get('recomendation'),
			'mode'=>$request->get('mode'),
			'link'=>$request->get('link'),
			'status'=> 1,
			'patient_id' =>$request->get('patient_id'),
			'formato_nuevo' => 1
		]);

		$fechado = Carbon::create($cita->payment->created_at);
		if( $cita->payment->pay_status == '2'):
			$precioNuevo = 0;
		else:
			$precioNuevo =  floatval($request->input('payment.price'));
		endif;
		/* $payment = Payment::create([
			'observation'=>'Viene de reprogramación, el pago se encuentra en el día: ' . $fechado->format('d/m/Y'),
			'bank'=>'',
			'voucher' => $request->input('payment.voucher'),
			'pay_status'=> $cita->payment->pay_status,
			'price' => $precioNuevo,
			'appointment_id' => $nuevaCita->id,
			'continuo' => $request->input('payment.continuo'),
			'user_id' => $request->get('user_id'),
			'adelanto' => $request->input('payment.adelanto')
		]); */

		//Consultar
		$pagoExtra = Extra_payment::where('appointment_id', $request->get('id') );
		$pagoExtra->update([
			'appointment_id'=> $nuevaCita->id,
			'observation'=>'Viene de reprogramación, el pago se encuentra en el día: ' . $fechado->format('d/m/Y')
		]);

		$pagoAnterior = Payment::where('appointment_id', $request->get('id') );
		$pagoAnterior->update([
			'appointment_id'=> $nuevaCita->id,
			'observation' =>$request->input('payment.observation'),
			//'activo' => 0,
		]);

		/* $cita->payment->update([
			'pay_status' => 3, //nuevo status para el pago: reprogramado/anulado
			'observation' => 'reprogramado'
		]); */

		if ($request->reschedule) {
			$reschedule = Reschedule::create([
				'reason' => $request->reschedule,
				'appointment_id' => $request->id,
				'fechaProxima' => $request->get('date')
			]);
		}
		

		return response()->json(['mensaje' => 'se actualizó la cita']);
	} catch (\Throwable $th) {
		echo $th;
	}
	}
	public function mandarVacio(Request $request, Appointment $appointment)
	{
		//var_dump($request->all()); die();
		try {
		
		$cita = Appointment::find($request->get('id'));
		//return var_dump( $cita->id ); die();
		$cita->update($request->all() );

		return response()->json(['mensaje' => 'se actualizó la cita']);
	} catch (\Throwable $th) {
		echo $th;
	}
	}

	public function updateStatus($idAppointment,$valueStatus, Request $request){
		$appointment = Appointment::find($idAppointment);
		//print_r( $request->all() ); die();


		function updateFieldStatus($appointment, $valueStatus){
			$appointment->update([
				'status' => $valueStatus
			]);
		}

		if($valueStatus == 1 || $valueStatus==3){ //status cita sin confirmar, debe eliminar HC
			$hc = Medical_evolution::latest()->first();
			$hc->activo=0;
			$hc->save();
		}

		if($valueStatus == 3){ //status cita anulada
		
			$patient = Patient::find( $appointment->patient_id );
			$patient->increment('faults');
			$patient->save();
			
			DB::table('faltas')->insert([
				'idPaciente' => $appointment->patient_id,
				'idProfesional' => $appointment->professional_id,
				'idHorario' => $appointment->schedule_id,
				'fecha' => $appointment->date,
				'observaciones' => 'Anulado por '. $request->input('motivo'),
				'idCita' => $idAppointment,
			]);
			
			DB::table('interesados')->insert([
				'nombre' => $patient->name,
				'celular' => $patient->phone,
				'motivo' => 'Anuló la cita. Indicó: ' . $request->get('motivo'),
				'referencia' => 7, //Ninguno
				'origen' => $request->input('origen'),
				'idProfesional' => $appointment->professional_id, //Ninguno
				'idPaciente' => $patient->id,
				'idUsuario' => $request->input('idUsuario')
			]);

			/* $appointment->update([
				'schedule_id' => null
			]); */
			updateFieldStatus($appointment, $valueStatus);
			
		}
		if($valueStatus == 2){ //status = confirmado
			
			$pago = Payment::where('appointment_id', $request->input('dataCit.id') )->get();
			//print_r('pago es ');
			//var_dump($pago[0]->pay_status); die();
			if($pago[0]->pay_status  == 2){ //estado = pagado Debe estar pagado y confirmado para que saque la cita con el doctor

				$medicalEvolutionExistents = Medical_evolution::where('patient_id', $request->input('dataCit.patient.id'))
				->where('activo', 1)
				/* ->where('professional_id', $request->input('dataCit.professional.id'))
				->where('date',$request->input('dataCit.date')) */
				->get();
				//echo 'contado ' .count($medicalEvolutionExistents);

				$primera = count($medicalEvolutionExistents) == 0 ? 'Primera evolución -Sist-': '';
				$tpago = Precio::where('id', $request->input('dataCit.type'))->first();

				//if(count($medicalEvolutionExistents) == 0){
				Medical_evolution::create([
					'type' => $tpago->idClasificacion,
					'date' => $request->input('dataCit.date'),
					'auth' => 0,
					'patient_id'=> $request->input('dataCit.patient.id'),
					'professional_id'=> $request->input('dataCit.professional.id'),
					'schedule' => $request->input('dataCit.schedule.check_time'),
					'content' =>  $primera
				]);
				//}
				
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
	public function destroy(Request $request, Appointment $appointment )
	{
		$appointment->delete();
	}

	public function eliminarCita($id, Request $request){
		//var_dump($request->all()); die();
		try {
			$cita = Appointment::find($id);
		DB::table('faltas')->insert([
			'idPaciente' => $cita->patient_id,
			'idCita' => $cita->id,
			'idProfesional' => $cita->professional_id,
			'fecha' => $cita->date,
			'idHorario' => $cita->schedule_id,
			'observaciones' => 'Eliminado desde vista cuaderno por '. $request->get('usuario') . ' motivo '. $request->get('razon') ,
			'esFalta' => 2
		]); //2 para indicar que viene de eliminado

		//$cita->delete();
		$cita->update([
			'status' => 5 //indicando que se elimina
		]);
		} catch (\Throwable $th) {
			echo $th;
		}
	}

	/**
	 *  Return professionals and appointments
	 * @param id professional
	 * 
	 */

	public function getProfessionalSummaryAppointment($id, $date){
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
		->where('date', 'like', $date .'%')
		->where('professional_id', $id)->get()->count();
		$confirmed = Appointment::where('status',2)
		->where('date', 'like', $date .'%')
		->where('professional_id', $id)->get()->count();
		$cancelled = Appointment::where('status',3)
		->where('date', 'like', $date .'%')
		->where('professional_id', $id)->get()->count();
		$reprogramed = Appointment::where('status',4)
		->where('date', 'like', $date .'%')
		->where('professional_id', $id)->get()->count();
		

		return response()->json([
			'patients' => [],
			'no_confirmed' => $no_confirmed,
			'confirmed' => $confirmed,
			'cancelled' => $cancelled,
			'reprogramed' => $reprogramed
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
			->with('patient', 'precio', 'professional')
			->with(['patient.medical_evolutions'=> function($query) use($id, $date){
				$query->where('professional_id','=', $id)
					  ->where('date','like',$date.'%');
			}])
			->with(['payment'=> function($query) {
				$query->where('payments.price','!=', null);
			}])
			->orderBy('date')
			->get();
		$extra_payments = Extra_payment::where('date','=',$date.'%')
		->with(['appointment' => function($query) use($id) {
			$query -> where('professional_id', '=', $id);
		}])
		->get();
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
			->with('patient', 'precio', 'professional')
			->with(['patient.medical_evolutions'=> function($query) use($id, $date){
				$query->where('professional_id','like', $id)
					  ->where('date','=',$date);
			}])
			->with(['payment'=> function($query) {
				$query->where('payments.price','!=', null);
			}])
			->get();
		//->where('appointment_id', '!=', 0)

		$extra_payments = [] ;
		foreach ($appointments as $cita) {
			$temp = Extra_payment::where('appointment_id', $cita->id)
			->with(['appointment' => function($query) use($id) {
				$query -> where('professional_id', '=', $id);
			}])
			->get();
			if ($temp->isNotEmpty()) {
				$extra_payments[] = $temp;
			}
		}

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
			$appointment = $appointment->precios;
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
		$usuario = DB::table('users')->where('id', '=', $payment[0]->user_id)->get();
		$pdf = PDF::loadView('recepcion.cupon', compact('appointment','patient','payment','schedule', 'usuario'));
		$pdf->setPaper('a7');
		return $pdf->stream('cupon.pdf');
	}
	public function cuponMismaSerie($id){ //Viene el appointment_id
		$extra_payment = Extra_payment::where('appointment_id', $id)
		->with('appointment', 'method_payment', 'appointment.professional')
		->with('appointment.schedule')
		->orderBy('created_at', 'desc')
		->first();
		//return $extra_payment; die();
		$pdf = PDF::loadView('recepcion.cupon_extra', compact('extra_payment'));
		$pdf->setPaper('a7');
		return $pdf->stream('cupon_extra.pdf');
	}

	public function pdfExtraCupon($id){
		$extra_payment = Extra_payment::
		with('method_payment', 'tipo_pagos')
		->find($id);
		$cita = array();
		if( $extra_payment->appointment_id !=0){
			$cita = Appointment::where('id', $extra_payment->appointment_id)->with('precio')->first();
		}
		//return $cita; die();
		$pdf = PDF::loadView('recepcion.cupon_extra', compact('extra_payment', 'cita'));
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

	public function intercambiar(Request $request){
		$horaTemporal = $request->input('horaPrimero');

		$cita = Appointment::find($request->input('idPrimero'));
		$cita->update([ 'schedule_id' =>  $request->input('horaElegido') ]);

		$cita2 = Appointment::find($request->input('idElegido'));
		$cita2->update([ 'schedule_id' => $horaTemporal ]);

		return response()->json(['mensaje' => 'se actualizó la cita']);

	}

	public function cuponMembresia ($id, $monto){
		$membresia = DB::table('membresias as m')
		->join('patients as p', 'p.id', '=', 'm.patient_id')
		->where('m.id', $id)
		->select('m.id as idMembresia', 'm.*', 'p.*')
		->first();

		$pago = Extra_payment::where('idMembresia', $id)
		->where('price', $monto)->first();
		//return  compact('membresia', 'monto' ); die();
		$pdf = PDF::loadView('recepcion.cupon_membresia', compact('membresia', 'monto', 'pago' )); //, 'monto'
		

		$pdf->setPaper('a7');
		return $pdf->stream('cupon_membresia.pdf');
	}

}


function object_sorter($clave,$orden=null) {
	return function ($a, $b) use ($clave,$orden) {
		  $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
		  return $result;
	};
}
