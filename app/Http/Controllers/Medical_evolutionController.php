<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medical_evolution;
use App\Models\Patient;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade as PDF;


class Medical_evolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evolution = Medical_evolution::create($request->all());
        return response()->json($evolution);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $medical_evolution = Medical_evolution::where('id', $id)->update($request->all());
        return response()->json($medical_evolution);
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

    /**
     * Generar pdf de paciente
     * a trves de su id
     */

    public function pdfEvolution ($id)
    {
        $historia = Patient::where('id', $id)
        ->with('initial_psychiatric_history', 'initial_psychological_history', 'medical_evolutions')
        ->first();
        
        
        $age = date("Y",strtotime(date('d-m-Y'))) - date("Y",strtotime($historia->birth_date));
        if(date("m",strtotime(date('d-m-Y'))) < date("m",strtotime($historia->birth_date))){
            $age--;
        }else if(date("m",strtotime(date('d-m-Y'))) == date("m",strtotime($historia->birth_date))){
            if(date("d",strtotime(date('d-m-Y'))) <= date("d",strtotime($historia->birth_date))){
                $age--;
            }
        }

        $pdf = PDF::loadView('profesional.pdf_paciente', compact('historia', 'age'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('mi-archivo.pdf');
    }

    /**
     * Update auth for medical evolution after one day
     */

    public function authEvol(Request $request){
        $medical_evolution =  Medical_evolution::where('id', $request->medical_evolution)
            ->update([
                'auth' => $request->auth
            ]);

        return response()->json(['Actualizado']);
    }

    public function editEvolution(Request $request){
    $medical_evolution =  Medical_evolution::where('id', $request->get('id'));
       
    $medical_evolution->update([
        'content' => $request->get('content')
    ]);

    return response()->json([
        'msg' => 'Contenido de Evolución actualizado correctamente'
    ]);

    }

    public function getPatientsWhitEvolution($date){
        $medical_evolution = Medical_evolution::where('next', $date)
                                                ->where('content','!=',null)
                                                ->where('professional_id','!=',5)
                                                ->with('patient')
                                                ->with('professional')
                                                ->get();
        return response()->json($medical_evolution);
    }

    public function getPatientsPsiq($date){
        $prescriptions = Prescription::where('effective_date', $date)
                                        ->with('patient')
                                        ->get();
                                
        return response()->json($prescriptions);
    }
}
