<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Initial_psychiatric_history;
use App\Models\Cie;
use App\Models\Patient;

class Initial_psychiatric_historyController extends Controller
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
        $initialPsychiatric = Initial_psychiatric_history::create($request->post());
        $patient = Patient::find($request->patient_id);
        
        foreach ($request->diagnosticArray as $cie) {
            $patient->cies()->attach($cie);
        }

        return response()->json([
            'initialPsychiatric' => $initialPsychiatric,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idPaciente)
    {
        // $initialPsychiatric = Initial_psychiatric_history::where('initial_psychiatric_histories.id', $idPaciente)
        //             ->join('patients','initial_psychiatric_histories.patient_id', '=', 'patients.id')->first();
        $initialPsychiatric = Initial_psychiatric_history::where('initial_psychiatric_histories.id', $idPaciente)
        ->with('patient')->first();
        return response()->json($initialPsychiatric);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Initial_psychiatric_history $initialPsychiatric)
    {
        $initialPsychiatric->update($request->all());
        $patient = Patient::find($request->patient_id);
        $patient->cies()->sync($request->diagnostic);    

        return response()->json($initialPsychiatric);
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
}
