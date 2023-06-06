<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\City;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserAddress;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Address;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'cities' => City::all(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cp' => ['required', 'string', 'max:5'],
            //'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ], [
            'required' => 'El campo :attribute es obligatorio',
            'string' => 'El campo :attribute debe ser un string',
            'max' => 'El campo :attribute debe tener como máximo :max caracteres',
            'email' => 'El campo :attribute debe ser un email',
            'unique' => 'El campo :attribute ya existe',
            'confirmed' => 'El campo :attribute no coincide',
            'password' => 'El campo :attribute debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número',
            'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot',
            'g-recaptcha-response.recaptchav3' => 'Por favor, verifica que no eres un robot',
        ]);



        $userAddress = Address::create([
            'address'=>$request->address,
            'city_id'=>$request->city_id,
            'cp'=>$request->cp,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'miembro',
            'address_id'=>$userAddress->id,
        ]);


        Buyer::create([
            'shipping_preferences'=>'Mañana',
            'fav_pay'=>'paypal',
            'user_id'=>$user->id,
        ]);


        Seller::create([
            'cred_total'=>0,
            'payback'=>false,
            'calificate'=>'bueno',
            'user_id'=>$user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function getCities(Request $request){
        $cities = City::all();
        return response()->json($cities);
    }
}
