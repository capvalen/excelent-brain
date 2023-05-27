<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Kairo;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Professional;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prescription = Prescription::all();
        // return $prescription;
        return response()->json($prescription);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->get('patient_id') == 0){
            $patient = Patient::create([
                'name' => $request->get('patient_name'),
								'activo' => 0
            ]);
            $request->merge(['patient_id' => $patient->id]);
        }

        if($request->get('signature') == true){
            $request->merge(['signature' => 1]);    
        }

        $prescription = Prescription::create($request->all());
        $medicines = $request->get('medicines');

        foreach($medicines as $medicine){

        $kairo = Kairo::where('id', $medicine['id'])->first();
        $kairo->prescriptions()->attach($prescription->id,[
            'amount' => $medicine['amount'],
            'dose' => null,
            'way' => $medicine['way'],
            'indications' => $medicine['indications']
        ]);

        }
        return response()->json([
            'id_receta' => $prescription->id
        ]);
    }

    public function allPrescriptionsConsult ($id)
    {
        $prescription = Appointment::where('patient_id', $id)
        ->with('prescription')
        ->get();

        return $prescription;

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

    public function createPDF($id){
        $receta = Prescription::where('id',$id)
        ->with('patient')
        ->with(['patient.cies', 'kairos' => function($q2) { $q2->withPivot('amount','way','indications');}])
        ->get();

        $id_paciente = $receta[0]->patient->id;

        $appointment = Appointment::where('patient_id', $id_paciente)->where('date', $receta[0]->attention_date)->where('clasification',1)->get();

        if(count($appointment) > 0){
            $professional = Professional::find($appointment[0]->professional_id);
        }else{
            $professional = null;
        }

        $pdf = PDF::loadView('profesional.pdf', compact('receta', 'professional'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('mi-archivo.pdf');
    }


    /**
     * Return count data for medicine for report
     * @param any
     */

    public function kairoPrescription ($date) {
        $kairo_prescriptions = Prescription::where('attention_date', 'like', $date.'%')->get();

        // $kairo_prescriptions = Prescription::whereYear('attention_date', '=', date('Y'))
        //     ->whereMonth('attention_date', '=', date('m'))
        //     ->get();
        
        
        foreach ($kairo_prescriptions as $kairo_prescription) {
            $kairo_prescription = $kairo_prescription->kairos;
        } 
        
        

        $dataKairos = [];

        foreach ($kairo_prescriptions as $kairo_prescription) {
            if (count($kairo_prescription->kairos) != 0) {
                foreach ($kairo_prescription->kairos as $kairo) {
                    array_push($dataKairos, $kairo);
                }
            }
        }
        
        asort($dataKairos);

        
        $objectCountKairo = [];
        $objectSumKairo = [];
        $count = 0;
        $sum = 0;
        $idInitial = null;
        $positionArr = 0;

        foreach ($dataKairos as $key => $data) {
            if ($data->id == $idInitial) {
                if ($data->cant) {
                    $count = $data->cant;
                } else {
                    $count++;
                    $sum = $sum + (int)$data->pivot->amount;
                }

                $data->cant = $count;
                $data->sum = $sum;

                foreach ($objectCountKairo as $kairo) {
                    if ($kairo->id == $data->id) {
                        $kairo->cant = $data->cant;
                        $kairo->sum = $data->sum;
                    }
                }
            } else {
                $positionArr = $key;
                $idInitial = $data->id;
                $count = 1;
                $sum = (int)$data->pivot->amount;
                $data->cant = $count;
                $data->sum = $sum;

                if ($count == 1) {
                    array_push($objectCountKairo, $data);
                }
            }
        }


        usort($objectCountKairo, object_sorter('sum', 'DESC'));
        return response()->json($objectCountKairo);
    }

    public function kairoDelete($id){
        $kairo = Kairo::where('id',$id);
        $kairo->delete();
        return response()->json([
            'msg' => 'success'
        ]);
    }
}

function object_sorter($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
          $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
          return $result;
    };
}

