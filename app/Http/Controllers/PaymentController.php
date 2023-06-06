<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Extra_payment;
use App\Models\Professional;
use App\Models\Payment;
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
				->get();
				foreach ($payments as $payment) {
					if($payment->appointment_id!=0){
						$appointment = Appointment::find($payment->appointment_id);
						$profesional = Professional::find($appointment->professional_id);
						$payment->profesional_name= $profesional->nombre ?? '';
					}else{
						$payment->profesional_name= '';
					}
				}
        return response()->json($payments);
       
    }

    public function getExtraPaymentsByDay($date){
        $payments = Extra_payment::where('created_at', 'like', $date.'%')
				->where('activo', 1)
				->get();
				foreach ($payments as $payment) {
					if($payment->appointment_id!=0){
						$appointment = Appointment::find($payment->appointment_id);
						$profesional = Professional::find($appointment->professional_id);
						$payment->profesional_name= $profesional->nombre ?? '';
					}else{
						$payment->profesional_name= '';
					}
				}
        return response()->json($payments);
    }
}
