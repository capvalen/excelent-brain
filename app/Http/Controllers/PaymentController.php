<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Professional;
use App\Models\Payment;
use App\Models\Precio;
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
					$appointment = Appointment::with('precio')->find($payment->appointment_id);
					if( isset($appointment->professional_id) ){
						$servicio = Precio::find($appointment->type)->first();
						$payment->servicio = $servicio->descripcion;
						$profesional = Professional::find($appointment->professional_id);
						$payment->professional_id= $profesional->professional_id ?? 0;
						$payment->profesional_name= $profesional->nombre ?? '';
						if( $appointment->schedule_id ):
							$payment->horario = \DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('h:i a');
							$payment->horar = intval(\DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('H'));
							$payment->detalle= $appointment->precio->descripcion;
						else:
							$payment->horario = '';
							$payment->horar = '';
							$payment->detalle= '';
						endif;
					}
				}else{
					$payment->professional_id= 0;
					$payment->profesional_name= '';
					$payment->horar = 0;
					$payment->horario = '';
					$payment->servicio='';
				}
			}
			$salidas = Extra_payment::whereMonth('date', '=', date('m'))
				->whereYear('date', '=', date('Y'))
				->whereDay('date', '=', date('d'))
				->where('activo', 1)
				->where('type', '=', 6)
				->with('usuario')
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
				->with('usuario')
				->get();
			
			$ordenados =  $payments->sortBy([
				fn ($a, $b) => $a['profesional_name'] <=> $b['profesional_name'],
				fn ($a, $b) => $a['horar'] <=> $b['horar'],
			]);
		
			return response()->json(['activos'=>$ordenados->values()->all(), 'eliminados'=>$noactivo, 'salidas'=> $salidas]);
    }

    public function getExtraPaymentsByDay($date){
        $payments = Extra_payment::where('created_at', 'like', $date.'%')
				->where('activo', 1)
				->where('type', '!=', 6)
				->get();
				foreach ($payments as $payment) {
					if($payment->appointment_id!=0){
						$appointment = Appointment::with('precio')->find($payment->appointment_id);
						if(isset($appointment->professional_id)){
								$servicio = Precio::find($appointment->type)->first();
								$payment->servicio = $servicio->descripcion;
								$profesional = Professional::find($appointment->professional_id);
								$payment->professional_id= $profesional->professional_id ?? 0;
								$payment->profesional_name= $profesional->nombre ?? '';
								
								if( $appointment->schedule_id ):
									$payment->horario = \DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('h:i a');
									$payment->horar = intval(\DateTime::createFromFormat('H:i:s', Schedule::find($appointment->schedule_id)->check_time)->format('H'));
									$payment->detalle= $appointment->precio->descripcion;
								else:
									$payment->horario = '';
									$payment->horar = '';
									$payment->detalle= '';
								endif;
							}else{
									$profesional= [];
							}
					}else{
						$payment->professional_id= 0;
						$payment->profesional_name= '';
						$payment->horar = 0;
						$payment->horario = '';
						$payment->servicio='';
					}
				}
				$noactivo = Extra_payment::where('created_at', 'like', $date.'%')
				->where('activo', 0)
				->where('type', '=', 6)
				->get();
				$ordenados =  $payments->sortBy([
					fn ($a, $b) => $a['profesional_name'] <=> $b['profesional_name'],
					fn ($a, $b) => $a['horar'] <=> $b['horar'],
				]);
				//$ordenados2 = $ordenados->sortBy('horar');
        return response()->json(['activos'=>$ordenados->values()->all(), 'eliminados'=>$noactivo]);
    }

		public function editarPagoExtra(Request $request){
			$pago = Extra_payment::find($request->get('id'));
			$pago->update([
				'moneda' => $request->get('moneda'),
				'voucher' => $request->get('boleta'),
				'voucher_issued' => $request->get('comprobante'),
				'observation' => $request->get('observacion'),
				'tipo' => $request->get('tipo'),
			]);
			return response()->json(['msg'=> 'success']);
		}
}
