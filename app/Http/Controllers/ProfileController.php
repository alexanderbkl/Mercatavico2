<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\BoughtProducts;
use App\Models\Buyer;
use App\Models\City;
use App\Models\Material;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserAddress;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        if(Auth::user()->rol->name=='administrador'){
            $userProducts = Product::all();
            $pedidos = Order::all();
        }else{
            $userProducts = Auth::user()->productos;
            $pedidos = Auth::user()->orders;
            $boughtProducts = Auth::user()->boughtProducts;
        }
        $usuarios =User::all();
        $materiales =Material::all();
        return view('profile.edit', [
            'user' => Auth::user(),
            'userProducts' =>$userProducts,
            'usuarios' =>$usuarios,
            'materials' =>$materiales,
            'pedidos' =>$pedidos,
            'ciudades' =>City::all(),
            'boughtProducts' => $boughtProducts ?? '',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email'=>'required|email|max:250',
            'ciudad'=>'required|max:250',
            'cp'=>'required|max:5',
        ],[
            'email'=>'Email requerido',
            'address'=>'Dirección requerida',
            'ciudad'=>'Ciudad requerida',
            'cp'=>'Código postal requerida',
            'required'=>'Campo requerido, no puede estar vacío',
            'cp.max'=>'El código postal no puede tener más de 5 caracteres',
            'email.max'=>'El email no puede tener más de 250 caracteres',
            'ciudad.max'=>'La ciudad no puede tener más de 250 caracteres',
        ]);
        $user = Auth::user();
        $user->name= $request->name;
        $user->email= $request->email;
        if($request->password!=null){
            $user->password = Hash::make($request->password);
        }
        if($user->addressUser){
            $user->addressUser->address = $request->address;
            $output = new ConsoleOutput();
            $user->addressUser->city_id = $request->ciudad;
            $user->addressUser->cp = $request->cp;
            $user->buyer->fav_pay = $request->fav_pay;
            $user->buyer->shipping_preferences = $request->shipping_preferences;
            $user->seller->payback = $request->payback;
            $user->addressUser->save();
            $user->buyer->save();
            $user->seller->save();
        }else{
            //create Address
            Address::create([
                'address'=>$request->address,
                'city_id'=>$request->ciudad,
                'cp'=>$request->cp,
            ]);

            Buyer::create([
                'user_id'=>$user->id,
                'fav_pay'=>$request->fav_pay,
            ]);

            Seller::create([
                'user_id'=>$user->id,
                'payback'=>$request->payback,
            ]);
        }
        $user->save();


        return response()->json(['status'=>'ok','message'=>'Datos actualizados correctamente.']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function addCalification(Request $request) {
        $output = new ConsoleOutput();

        try {
            $validatedData = $request->validate([
                'bought_product_id' => 'required|integer',
                'calification' => 'required|integer|min:1|max:5',
                'seller_id' => 'required|integer',
            ], [
                'required' => 'The :attribute field is required',
                'integer' => 'The :attribute field must be an integer',
                'min' => 'The :attribute field must be at least :min',
                'max' => 'The :attribute field must be at most :max',
            ]);


            if ($validatedData) {
                DB::transaction(function () use ($validatedData, $output) {
                    // First update the BoughtProduct
                    $boughtProduct = BoughtProducts::findOrFail($validatedData['bought_product_id']);
                    $boughtProduct->calification = $validatedData['calification'];
                    $boughtProduct->save();

                    // Then calculate the average calification of the seller and update the seller
                    $seller = Seller::where('user_id', $validatedData['seller_id'])->first();
                    $averageCalification = $seller->averageCalification();
                    $seller->calificate = $averageCalification;
                    $seller->save();
                });

                return response()->json(['message' => 'Calification added successfully'], 200);
            } else {
                $output->writeln("error: failed to add calification (no validated data)");
                return response()->json(['error' => 'Failed to add calification, no validated data'], 500);
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return response()->json(['error' => 'Failed to add calification'], 500);
        }
    }

    public function showSellerRating($seller_id) {
        $seller = Seller::where('user_id', $seller_id)->first();

        if ($seller) {
            $averageCalification = $seller->averageCalification();

            return response()->json(['average_calification' => $averageCalification], 200);
        } else {
            return response()->json(['error' => 'Seller not found'], 404);
        }
    }
}
