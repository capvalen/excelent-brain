<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Professional;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
        //
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

    public function getAllExtraPayments(){
			//Por defecto obtiene el día actual
			$payments = Extra_payment::whereMonth('date', '=', date('m'))
				->whereYear('date', '=', date('Y'))
				->whereDay('date', '=', date('d'))
				->where('activo', 1)
				->where('type', '!=', 6)
				->get();
			foreach ($payments as $payment) {
				if($payment->appointment_id!=0){
					$appointment = Appointment::find($payment->appointment_id);
					if( isset($appointment->professional_id) ){
						$profesional = Professional::find($appointment->professional_id);
						$payment->profesional_name= $profesional->nombre ?? '';
						$payment->horario = \DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('h:i a');
						$payment->horar = \DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('H');
					}
				}else{
					$payment->profesional_name= '';
					$payment->horario = '';
				}
			}
			$salidas = Extra_payment::whereMonth('date', '=', date('m'))
				->whereYear('date', '=', date('Y'))
				->whereDay('date', '=', date('d'))
				->where('activo', 1)
				->where('type', '=', 6)
				->get();
			foreach ($salidas as $salida) {
				if($salida->appointment_id!=0){
					$appointment = Appointment::find($salida->appointment_id);
					if( isset($appointment->professional_id) ){
						$profesional = Professional::find($appointment->professional_id);
						$salida->profesional_name= $profesional->nombre ?? '';
					}
				}else{
					$salida->profesional_name= '';
				}
			}

			$noactivo = Extra_payment::where('date', 'like', date('Y-m-d').'%')
				->where('activo', 0)
				->get();
			
			$ordenados = $payments->sortBy('horar');
			
			return response()->json(['activos'=>$ordenados->values()->all(), 'eliminados'=>$noactivo, 'salidas'=> $salidas]);
    }

    public function getExtraPaymentsByDay($date){
        $payments = Extra_payment::where('created_at', 'like', $date.'%')
				->where('activo', 1)
				->get();
				foreach ($payments as $payment) {
					if($payment->appointment_id!=0){
						$appointment = Appointment::find($payment->appointment_id);
							if(isset($appointment->professional_id)){
									$profesional = Professional::find($appointment->professional_id);
									$payment->profesional_name= $profesional->nombre ?? '';
							}else{
									$profesional= [];
							}
					}else{
						$payment->profesional_name= '';
					}
				}
				$noactivo = Extra_payment::where('created_at', 'like', $date.'%')
				->where('activo', 0)
				->get();
        return response()->json(['activos'=>$payments, 'eliminados'=>$noactivo]);
    }

		public function editarPagoExtra(Request $request){
			$pago = Extra_payment::find($request->get('id'));
			$pago->update([
				'moneda' => $request->get('moneda'),
				'voucher' => $request->get('boleta'),
				'voucher_issued' => $request->get('comprobante'),
				'observation' => $request->get('observacion'),
			]);
			return response()->json(['msg'=> 'success']);
		}
}
