<?php

namespace App\Http\Controllers;

use App\Models\Adittional;
use Illuminate\Http\Request;

class AdittionalController extends Controller
{
    public function saveAditional(Request $request){
        $adittional = Adittional::create([
            'name' => $request->get('name'),
            'number' => $request->get('number'),
            'professional_id' => $request->get('professional_id'),
            'schedule' => $request->get('schedule')
        ]);

        return response()->json([
            'msg' => 'Se agregó adicional'
        ]);
    }

    public function allAditionals(){
        $adittionals = Adittional::orderBy('id','desc')
                                ->with('professional')
                                ->get();
        return response()->json($adittionals);
    }
}
