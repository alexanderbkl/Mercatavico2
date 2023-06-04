<?php

namespace App\Http\Controllers;

use App\Mail\MailContact;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function enviarCorreo(Request $request)
    {

        if ($request->agree == "false") {
            return response()->json(['status' => 'error_validación', 'message' => 'Debes aceptar los terminos y condiciones'], 500);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required',
            'msg' => 'required',
            'agree' => 'required',
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);
        $datos = $request->except('agree');
        Mail::to('mercatavico.com@gmail.com')->send(new MailContact($datos));
        return response()->json(['status' => 'ok', 'message' => 'Se ha enviado el mensaje correctamente.'], 200);


    }

}
