<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class ExtrapaymentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

		/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		Extra_payment::create($request->all());
		return response()->json('Se registro con éxito');
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
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

	public function egresoExtra(Request $request){
		try {
			$pagoExtra = new Extra_payment;
			$pagoExtra->price = $request->input('price');
			$pagoExtra->type = 6;
			$pagoExtra->observation = $request->input('observation');
			$pagoExtra->voucher = $request->input('boleta');
			$pagoExtra->voucher_issued = $request->input('serie');
			$pagoExtra->user_id = $request->input('idUsuario');
			$pagoExtra->idSede = $request->input('idSede');

			$pagoExtra->save();
	return response()->json('Se registro con éxito');
		
		} catch (\Throwable $th) {
			echo $th;
		}
		
	}

	public function borrarPagoExtra(Request $request){
		//print($request->get('id')); die();

		try {
			$pagoExtra = Extra_payment::where('id', $request->get('id'))->first();
			
			$pagoExtra->update([
			'razon' => $request->get('razon'),
			'activo'=>0
		]);
		if( $pagoExtra->appointment_id <>0){
			Payment::where('appointment_id', $pagoExtra->appointment_id )
			->update([
				'pay_status'=>3 //anulado
			]);
		
		}
		return response()->json(['mensaje' => 'Se borró con éxito']);
		} catch (\Throwable $th) {
			echo $th;
		}
		
	}

	public function ticketCierreCaja($fecha, $usuario, $idSede)
        {
            $fecha = Carbon::now();
            $hoy = $fecha->format('d/m/Y h:i A');
        
            // Filtrar ingresos por sede
            $pagos = Extra_payment::whereDate('created_at', $fecha)
                ->where('activo', 1)
                ->where('type', '!=', 6)
                ->where('idSede', $idSede) // Filtro por sede
                ->with('method_payment')
                ->get();
        
            $agrupados = $pagos->groupBy(function ($item) {
                return $item->method_payment->tipo ?? 'Ninguno';
            });
        
            $sumas = $agrupados->map(function ($items) {
                return $items->sum('price');
            });
        
            $total = $sumas->sum();
            $totalEfectivo = isset($sumas['Efectivo']) ? $sumas['Efectivo'] : 0;
        
            // Filtrar egresos por sede
            $salidas = Extra_payment::whereDate('created_at', $fecha)
                ->where('activo', 1)
                ->where('type', '=', 6)
                ->where('idSede', $idSede) // Filtro por sede
                ->with('method_payment')
                ->get();
        
            $agrupadosSalidas = $salidas->groupBy(function ($item) {
                return $item->method_payment->tipo ?? 'Ninguno';
            });
        
            $sumasSalidas = $agrupadosSalidas->map(function ($items) {
                return $items->sum('price');
            });
        
            $totalSalidas = $sumasSalidas->sum();
        
            // Filtrar anulados por sede
            $anulados = Extra_payment::whereDate('created_at', $fecha)
                ->where('activo', 0)
                ->where('idSede', $idSede) // Filtro por sede
                ->with('method_payment')
                ->get();
        
            $agrupadosAnulados = $anulados->groupBy(function ($item) {
                return $item->method_payment->tipo ?? 'Ninguno';
            });
        
            $sumasAnulados = $agrupadosAnulados->map(function ($items) {
                return $items->sum('price');
            });
        
            $totalAnulados = $sumasAnulados->sum();
        
            // Generar PDF filtrado por sede
            $pdf = PDF::loadView('recepcion.cupon_cierre', compact(
                'sumas', 'total', 'hoy', 'usuario', 'totalEfectivo', 
                'totalSalidas', 'sumasSalidas', 'totalAnulados', 'sumasAnulados'
            ));
            
            $pdf->setPaper('a7');
            return $pdf->stream('cupon_cierre.pdf');
        }
}
