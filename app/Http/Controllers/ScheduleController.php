<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Professional;
use Illuminate\Support\Facades\DB;
use stdClass;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    public function getschedules($id){   
        
        $time = date('H:i:s', time());
        $date = date('Y-m-d');

        $appointment = Appointment::where('professional_id', $id)
            ->where('appointments.date', '>=', date('Y-m-d'))
						->where('appointments.status', '<>', 4)
            ->with('schedule')
            ->get();
        
				$schedules = Schedule::where('professional_id', $id)
				->orderBy('check_time', 'asc')
            ->with('appointments')
            ->get();
				
        // ->with(['schedule' => function ($q1) { $q1->where('schedules.check_time', '>', date('H:i:s', time())); }])
        // return $appointment; 
        // return $appointment;
        return response()->json([
            'schedulesInvalid' => $appointment,
            'schedules' => $schedules
        ]);
    }
    public function horarioOcupado($id, $fecha){
			//var_dump($fecha);die();
        
      $appointment = Appointment::where('professional_id', $id)
			->whereDate('appointments.date', '=', $fecha)
			->where('appointments.status', '<>', 4)
			->where('appointments.status', '<>', 3)
			->with('schedule')
			->get();
        
			$solos = Schedule::where('professional_id', $id)
			->orderBy('check_time', 'asc')
			->get();
        
			return response()->json([
				'invalidos' => $appointment,
				'solos' => $solos,
			]);
    }

    public function verifHours($check_time, $departure_date, $professional_id, $dia){
        $schedule = Schedule::where('check_time',$check_time)
                            ->where('departure_date', $departure_date)
                            ->where('professional_id', $professional_id)
                            ->where('day', $dia)
                            ->get();
        if($schedule->isNotEmpty()){
            return false;
        }else{
            return true;
        }
    }

    public function verifEntre($check_time, $departure_date, $professional_id, $dia){
        $schedule = Schedule::where('professional_id', $professional_id)
                            ->where('day', $dia)
                            ->where(function($q) use ($check_time,$departure_date){
                                $q->where('check_time','<',$check_time)
                                    ->where('departure_date','>', $check_time)
                                    ->orWhere(function($query) use ($departure_date){
                                        $query->where('check_time','>',$departure_date)
                                                ->where('departure_date','<', $departure_date);
                                    });
                            })
                            ->get();

        if($schedule->isNotEmpty()){
            return false;
        }else{
            return true;
        }
    }

    public function validateSchedule($check_time, $departure_date, $professional_id, $dia){

        $salida_max = DB::table('schedules')->where('day',$dia)->where('professional_id',$professional_id)->max('departure_date');
        $entrada_min = DB::table('schedules')->where('day',$dia)->where('professional_id',$professional_id)->min('check_time');

        if($check_time<$entrada_min){
            if($departure_date>$entrada_min){
                $authTime = false;
            }else{
                if($this->verifHours($check_time, $departure_date, $professional_id, $dia)){
                    $authTime = true;
                }else{
                    $authTime = false;
                }
            }
        }else if($entrada_min <= $check_time && $check_time < $salida_max){
            if($this->verifHours($check_time, $departure_date, $professional_id, $dia) && $this->verifEntre($check_time, $departure_date, $professional_id, $dia)){
                $authTime = true;
            }else{
                $authTime = false;
            }
        }else if($salida_max <= $departure_date){
            $authTime = true;
        }

        return $authTime;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check_time = $request->get('check_time');
        $departure_date = $request->get('departure_date');
        $professional_id = $request->get('professional_id');
        $dias = $request->get('daysSelected');


        foreach ($dias as $dia)
        {
            if($this->validateSchedule($check_time, $departure_date, $professional_id, $dia)){
                Schedule::create([
                    'check_time' => $check_time,
                    'departure_date' => $departure_date,
                    'day' => $dia,
                    'professional_id' => $professional_id
                ]);

                $res = 'Exito';
            }else{
                $res = 'No Success';
            }

        }

        return response()->json([
            'mensaje' => $res
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedules = Schedule::where('professional_id', $id)->orderBy('check_time',)->get();
        return response()->json($schedules);
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
        $check_time = $request->get('check_time');
        $departure_date = $request->get('departure_date');
        $day = $request->get('daySelected');

        if($this->validateSchedule($check_time, $departure_date, $id, $day)){
            Schedule::where('id',$id)->update([
                'check_time' => $check_time,
                'departure_date' => $departure_date
            ]);
            return response()->json([
                'mensaje' => 'success'
            ]);
        }else{
            return response()->json([
                'mensaje' => 'failed'
            ]);
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Schedule::where('id', $id)->delete();
        return response()->json([
            'mensaje'=>'Profesional eliminado'
        ]);
    }

    public function getProfesionalSchedules($id){
        Schedule::where('professional_id', $id)->get();
        return response()->json([
            'mensaje' => 'Horarios de prof'
        ]);
    }
}
