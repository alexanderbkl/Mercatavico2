<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rol;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changeRol(Request $request){
        if($request->user_id == Auth::id()){
            return response()->json(['message'=>'No puedes cambiarte el rol a ti mismo.'],403);
        }
        if(Auth::user()->rol->name=="administrador"){
            $user = User::find($request->user_id);
            if($user){
                if($user->rol->name=="administrador"){
                    $newRol = Rol::where('name','usuario_registrado')->first()->id;
                    $user->rol_id = $newRol;
                    $user->save();
                }else{
                    $newRol = Rol::where('name','administrador')->first()->id;
                    $user->rol_id = $newRol;
                    $user->save();
                }
                $usuarios = User::all();
                $html = view('profile._partial_usuarios',compact('usuarios'))->render();
                return response()->json(['message'=>'Rol cambiado correctamente','view'=>$html],201);
            }else{
                return response()->json(['status'=>'error'],404);
            }

        }else{
            return response()->json(['status'=>'error'],403);
        }
    }

    public function update(Request $request){
        $request->validate([
            'email'=>'required|email|max:250',
            'city'=>'required',
            'cp'=>'required',
            'address'=>'required',
        ],[
            'address'=>'Dirección necesaria',
            'city'=>'Ciudad necesaria',
            'cp'=>'Código postal necesario',
            'email'=>'Correo electrónico necesario',
        ]);
        $user = User::find($request->user_id);
        if($user){
            $user->name= $request->name;
            $user->email= $request->email;
            if(!$user->addressUser){
                UserAddress::create([
                    'user_id'=>$user->id,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'cp'=>$request->cp,
                ]);
            }else{
                $user->addressUser->address = $request->address;
                $user->addressUser->city = $request->city;
                $user->addressUser->cp = $request->cp;
                $user->addressUser->save();
            }
            $user->save();
            $usuarios = User::all();
            $html = view('profile._partial_usuarios',compact('usuarios'))->render();
            return response()->json(['status'=>'ok','message'=>'Datos actualizados correctamente.','view'=>$html]);
        }else{
            return response()->json(['status'=>'error','message'=>'Usuario no encontrado.'],404);
        }

    }

    public function destroy(Request $request){
        $user = User::find($request->user_id);
        if($user && Auth::user()->rol->name=="administrador"){
            $user->delete();
            $usuarios = User::all();
            $html = view('profile._partial_usuarios',compact('usuarios'))->render();
            return response()->json(['status'=>'ok','message'=>'Usuario eliminado correctamente.','view'=>$html]);
        }
    }
}
