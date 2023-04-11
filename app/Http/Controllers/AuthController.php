<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        return response()->json([
            'token' =>  $user->createToken($request->device_name)->plainTextToken,
            'rol' => $user->rol
        ]);

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'msg'=>'Logout successfull'
        ]);
    }

    public function deleteUser($id){
        User::where('id',$id)->delete();
        return response()->json([
            'mensaje'=>'Usuario eliminado'
        ]);
    }

    public function updateUser($id, Request $request){
        User::find($id)->update([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);
        return response()->json([
            'mensaje'=>'Accesos actualizados'
        ]);
    }
}
