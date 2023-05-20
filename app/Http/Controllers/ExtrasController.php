<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtrasController extends Controller
{
	//
	public function nuevoAviso(Request $request){
		try {
			DB::insert("INSERT INTO `recordatorios`(`fecha`, `actividad`, `responsable`, `creador`, `actualizador`) VALUES 
			(?,?,?,?,?)",
			[
				$request->input('fecha'), $request->input('actividad'), $request->input('responsable'), $request->input('creador'), $request->input('creador')
			]);
			$ultimoID = DB::getPdo()->lastInsertId();
			return response()->json([
				'mensaje' => $ultimoID
			]);
		} catch (\Throwable $th) {
			echo $th;
		}
		

	}
}
