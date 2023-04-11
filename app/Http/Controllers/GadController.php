<?php

namespace App\Http\Controllers;
use App\Models\Gad;


use Illuminate\Http\Request;

class GadController extends Controller
{
    public function index()
    {
        $data = Gad::all();
        return response()->json($data);
    }

    public function store(Request $request) 
    {
        Gad::create([
            'patient_id' => $request->patient_id,
            'result' => $request->result,
        ]);

        return response()->json('success');
    }
}
