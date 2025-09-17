<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Medical_exam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use stdClass;

class Medical_examController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Medical_exam::all();
        return response()->json($exams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

		 
		public function newExam(Request $request){
			//var_dump($request->all());die();
			try {
				$examen = new Medical_exam;
				$examen->fill([
					'name' => $request->get('nombre'),
					'type' => $request->get('tipo'),
				]);
				$examen->save();
			
				return $examen;
			} catch (\Throwable $th) {
				echo $th;
			}
			
		}

    public function store(Request $request)
    {
        $appointment = Appointment::find($request->get('appointment'));

        $exams = $request->get('medical_exams');

        return $request;

        foreach($exams as $exam){
            $appointment->medical_exams()->attach($exam['id'],[
                'attention_date' => $request->get('attention_date')
            ]);
        }

        return redirect()->route('profesional.pdf_exam');

        return response()->json([
            'mensaje' => 'Examen registrado'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function createPdf($exams){
        
        $objectExam = json_decode($exams);
        $data = Appointment::with('professional')->find($objectExam->appointment); // Cargamos la relación 'professional'

        // ->with('patient')
        // ->with('patient.cies')
        // ->with('medical_exams')
                // ->where;
                
                
        $examsArr = $objectExam->medical_exams;
        $examsAll = (object) [];
        $examsAll->value = [];

        $leafLaboratory = []; 
        $leafImage = []; 
        $leafOther = []; 

        foreach ($examsArr as $exam) {
            if ($exam->type === 1) {
                array_push($leafLaboratory, $exam);
            } else if ($exam->type === 2) {
                array_push($examsAll->value, [$exam]);
            } else if ($exam->type === 3) {
                array_push($examsAll->value, [$exam]); 
            }
        }
        
        // $data[0]->examenes = [];

        array_push($examsAll->value, $leafLaboratory);
        // Se cargan los datos del profesional
        $professional = $data->professional;  
        
        // json_decode($examsAll, TRUE);
        // return $examsAll->value;

        $pdf = PDF::loadView('profesional.pdf_exam', compact('data', 'objectExam', 'examsAll', 'professional'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('mi-archivo.pdf');

        // %7B"appointment":"8","name_patient":"BARZOLA%20HUATUCO%20YOVANA%20ESTEFANI","attention_date":"2022-01-16","medical_exams":[%7B"id":1,"name":"Hemograma%20completo","type":1,"created_at":null,"updated_at":null%7D]%7D
        // %7B"appointment":"8","name_patient":"BARZOLA%20HUATUCO%20YOVANA%20ESTEFANI","attention_date":"2022-01-16","medical_exams":[%7B"id":1,"name":"Hemograma%20completo","type":1,"created_at":null,"updated_at":null%7D,%7B"id":18,"name":"Electroencefalograma","type":3,"created_at":null,"updated_at":null%7D,%7B"id":2,"name":"Glucosa","type":1,"created_at":null,"updated_at":null%7D]%7D
        // %7B"appointment":"8","name_patient":"BARZOLA%20HUATUCO%20YOVANA%20ESTEFANI","attention_date":"2022-01-16","medical_exams":[%7B"id":1,"name":"Hemograma%20completo","type":1,"created_at":null,"updated_at":null%7D,%7B"id":18,"name":"Electroencefalograma","type":3,"created_at":null,"updated_at":null%7D,%7B"id":2,"name":"Glucosa","type":1,"created_at":null,"updated_at":null%7D,%7B"id":14,"name":"Gammagrafia%20cerebral","type":2,"created_at":null,"updated_at":null%7D,%7B"id":15,"name":"TAC%20cerebral%20c/s%20Contraste","type":2,"created_at":null,"updated_at":null%7D]%7D
    }
}
