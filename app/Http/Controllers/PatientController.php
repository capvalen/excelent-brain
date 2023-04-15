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

    public function getPatient ()
    {
        $patient = Patient::with('relative', 'address', 'prescriptions')
        ->get();

        return response()->json($patient);
    }
    public function getLast10Patients (){
        $patient = Patient::latest('created_at')->take(20)
				->with('relative', 'address', 'prescriptions')
        ->get();

        return response()->json($patient);
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
      return response()->json([
        'patient'=>$paciente
      ]);
    }

    public function showEvolution ($idPaciente) {
        $evoluciones = Patient::where('id',$idPaciente)
        ->with('cies', 'initial_psychiatric_history', 'initial_psychological_history', 'relative', 'medical_evolutions', 'appointments', 'prescriptions')
        ->with('medical_evolutions.professional')
        ->first();

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


			/* $triaje = new Triaje();
			$triaje->patient_id = $request[1]['patient_id'];
			$triaje->motivo = $request[1]['motivo'];
			$triaje->sintomatologia = $request[1]['sintomatologia'];
			$triaje->antecedentes = $request[1]['antecedentes'];
			$triaje->prioridad = $request[1]['prioridad'];
			$triaje->especialista = $request[1]['especialista']; 
			$triaje->save(); */

			$triaje = DB::insert('INSERT INTO `triaje`(`patient_id`, `motivo`, `sintomatologia`, `antecedentes`, `prioridad`, `especialista`) VALUES (?,?,?,?,?,?)',
			[ $request[1]['patient_id'],$request[1]['motivo'],$request[1]['sintomatologia'],$request[1]['antecedentes'],$request[1]['prioridad'], $request[1]['especialista'] ]);
		
			$ultimoID = DB::getPdo()->lastInsertId();
			return response()->json([
				'mensaje' => $ultimoID
			]);

		}
}
