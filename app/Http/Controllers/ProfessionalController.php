<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesionales = Professional::select('professionals.*','users.email')
				->where('profession','<>','Ninguno')
				/*->where('profession','Psicólogo')
				->orWhere('profession','Psiquiatra')
				->orWhere('profession','Nutricionista')*/
				->join('users','users.id','=','professionals.user_id')
				->get();

        return response()->json($profesionales);
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
        $user = User::create([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'rol' => 'profesional'
        ]);
        if($request->file('photo') && $request->file('signing')){
            $urlPhoto = $request->file('photo')->store('img_profesionales','public');
            $urlFirma = $request->file('signing')->store('firmas','public');
            $prof = Professional::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> $urlPhoto,
                'signing' => $urlFirma,
                'user_id' => $user->id
            ]);
        }else if($request->file('photo')){
            $urlPhoto = $request->file('photo')->store('img_profesionales','public');
            $prof = Professional::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> $urlPhoto,
                'signing' => '-',
                'user_id' => $user->id
            ]);
        }else if($request->file('signing')){
            $urlFirma = $request->file('signing')->store('firmas','public');
            $prof = Professional::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> '-',
                'signing' => $urlFirma,
                'user_id' => $user->id
            ]);
        }else{
            switch ($request->get('profession')) {
                case 'Psiquiatra': $idProfesion = 1; break;
                case 'Psicólogo': $idProfesion = 2; break;
                case 'Nutricionista': $idProfesion = 6; break;
                case 'Terapista': $idProfesion = 7; break;
                case 'Tecnólogo': $idProfesion = 8; break;
                default: break;
            }
            $prof = Professional::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'idProfesion' => $idProfesion,
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> '-',
                'signing' => '-',
                'user_id' => $user->id
            ]);
        }


        return response()->json([
            'user'=>$user,
            'prof'=>$prof
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $profesional = Professional::where('user_id', $user_id)->first();
        return response()->json($profesional);
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
    public function update($id,Request $request)
    {
        if($request->file('photo') && $request->file('signing')){
					$foto = $request->file('photo');
					$fotoNombre = uniqid() . '.' . $foto->getClientOriginalExtension();
					$firma = $request->file('signing');
					$firmaNombre = uniqid() . '.' . $firma->getClientOriginalExtension();
						$urlPhoto = $foto->move(public_path('img/img_profesionales'), $fotoNombre);
						$urlFirma = $firma->move(public_path('img/firmas'), $firmaNombre);
            //$urlPhoto = $request->file('photo')->store('img_profesionales','public');
            //$urlFirma = $request->file('signing')->store('firmas','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> 'img_profesionales/'. $fotoNombre,
                'signing'=> 'firmas/'.$firmaNombre
            ]);
        }
        else if($request->file('photo')){
					$foto = $request->file('photo');
					$fotoNombre = uniqid() . '.' . $foto->getClientOriginalExtension();
					$foto->move(public_path('img/img_profesionales'), $fotoNombre);
					//$urlPhoto = $request->file('photo')->store('img_profesionales','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> 'img_profesionales'. $fotoNombre
            ]);
        }else if($request->file('signing')){
					$firma = $request->file('signing');
					$firmaNombre = uniqid() . '.' . $firma->getClientOriginalExtension();
					$urlFirma = $firma->move(public_path('img/firmas'), $firmaNombre);
            //$urlFirma = $request->file('signing')->store('firmas','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'signing'=> 'firmas'.$firmaNombre
            ]);
        }
        else{
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description')
            ]);
        }



        return response()->json([
            'mensaje'=>'Usuario actualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professional $professional)
    {
        $professional->delete();
        return response()->json([
            'mensaje'=>'Profesional eliminado'
        ]);
    }

    public function horasLibres($id, $fecha){
			$profesional = Professional::find($id);

			$dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
			$carbonFecha = Carbon::parse(	$fecha);
			$indice = $carbonFecha->dayOfWeek; // Retorna 0=domingo, 1=lunes...
			$dia = $dias[$indice];
			
			//Solución 1: llamando a todos los índices, pero hay error
			/* 
			$ocupados = $profesional->schedulesNoUsados();
			$ocupado = [];
			foreach ($ocupados as $ocupa) {
				if( strtolower($ocupa->day) == $dia)
					$ocupado[] = $ocupa;
			}
			return $ocupado; */
			
			//Solución 2: pide todos los horarios, luego filtra por el día, luego filtra los horarios que esten libres de la tabla appointments en la fecha seleccionada.
			$ocupado =  $profesional->schedules()
			->where('day', $dia) //que no estén relacionados
			->whereDoesntHave('appointments', function ($query) use ($fecha) {
					$query->whereDate('date', $fecha);
			})->get();
			return $ocupado;
				
			
		
    }
}
