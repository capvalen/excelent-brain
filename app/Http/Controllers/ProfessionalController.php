<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
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
                        ->where('profession','Psicólogo')
                        ->orWhere('profession','Psiquiatra')
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
            $prof = Professional::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
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
            $urlPhoto = $request->file('photo')->store('img_profesionales','public');
            $urlFirma = $request->file('signing')->store('firmas','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> $urlPhoto,
                'signing'=> $urlFirma
            ]);
        }
        else if($request->file('photo')){
            $urlPhoto = $request->file('photo')->store('img_profesionales','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'photo'=> $urlPhoto
            ]);
        }else if($request->file('signing')){
            $urlFirma = $request->file('signing')->store('firmas','public');
            Professional::where('id',$id)->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'profession' => $request->get('profession'),
                'cv_description' => $request->get('cv_description'),
                'signing'=> $urlFirma
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

    public function dataTableProf(){

    }
}
