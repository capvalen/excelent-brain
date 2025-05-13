<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Limbo;
use Illuminate\Http\Request;

class LimboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limbos = Limbo::with(['appointment.patient', 'appointment.professional', 'appointment.precio', 'appointment.schedule', 'appointment.payment'])
        ->get();
        return $limbos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $limbo = Limbo::create($request->all());

        $cita = Appointment::find($request->get('appointment_id'))->update([
            'status' => 6
        ]);
        return $limbo;
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
}
