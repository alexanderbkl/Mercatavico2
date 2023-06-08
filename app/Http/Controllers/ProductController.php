<?php

namespace App\Http\Controllers;


use App\Models\MaterialIntermediate;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProductController extends Controller
{

    public function index(){
        $productos = Product::all();
        //for each product, filter the ones that have 0 or less stock
        foreach($productos as $key=>$product){
            if($product->stock <= 0){
                $output = new ConsoleOutput();
                $output->writeln($product->stock);
                unset($productos[$key]);
            }
        }
        return view('products.index',compact('productos'));
    }
    public function store(Request $request){

        $path = null;
        if($request->file('foto')){
            $path=Str::random(15).time().$request->file('foto')->getClientOriginalExtension();
            Storage::putFileAs('public/productsImages', $request->file('foto'),$path);
        }

        $output = new ConsoleOutput();

        $output->writeln("materiales:");
        $output->writeln($request->materiales);
        $output->writeln("title:");
        $output->writeln($request->title);
        $output->writeln("descripcion:");
        $output->writeln($request->descripcion);
        $output->writeln($request->stock);
        $output->writeln($request->status);

        $product = Product::create([
            'seller_id'=>Auth::id(),
            'foto'=>$path,
            'title'=>$request->title,
            'description'=>$request->descripcion,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'status'=>$request->status,
        ]);
        if($request->materiales){
            $materiales =  explode(',',$request->materiales);
            foreach($materiales as $material_id){
                MaterialIntermediate::create([
                    'product_id'=>$product->id,
                    'material_id'=>$material_id,
                ]);
            }
        }

        $userProducts = User::find(Auth::id())->seller->productos;
        $html = view('profile._partial_mis_productos',compact('userProducts'))->render();

        return response()->json(['message'=>'Producto creado correctamente.','view'=>$html]);
    }

    public function update(Request $request){



        $output = new ConsoleOutput();
        $output->writeln("product_id:");
        $output->writeln($request->product_id);
        $output->writeln("materiales:");
        $output->writeln($request->materiales);
        $output->writeln("title:");
        $output->writeln($request->title);
        $output->writeln("descripcion:");
        $output->writeln($request->descripcion);
        $output->writeln($request->stock);
        $output->writeln($request->status);
        //if request status is 1, 'Nuevo', if 2, 'Usado', if 3, 'Estropeado'
        if ($request->status == 'Nuevo') {
            $status = 1;
        } elseif ($request->status == 'Usado') {
            $status = 2;
        } elseif ($request->status == 'Estropeado') {
            $status = 3;
        } else {
            $status = 1;
        }

        $product = Product::find($request->product_id);
        if($product->seller_id==Auth::id() || Auth::user()->rol->name=='administrador'){
            if($request->file('foto')){
                $path=Str::random(15).time().$request->file('foto')->getClientOriginalExtension();
                Storage::putFileAs('public/productsImages', $request->file('foto'),$path);
                $product->foto = $path;

            }
            if($request->materiales){
                $materiales =  explode(',',$request->materiales);
                foreach($product->materiales as $old){
                    $old->delete();
                }
                try {
                foreach($materiales as $material_id){
                    if(!MaterialIntermediate::where('product_id',$product->id)
                        ->where('material_id',$material_id)->first()){
                            MaterialIntermediate::create([
                            'product_id'=>$product->id,
                            'material_id'=>$material_id,
                        ]);
                    }

                }
            }
            catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
            }
            $output->writeln($request->title);

            $product->title = $request->title;
            $product->description = $request->descripcion;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = $status;
            $product->seller_id = Auth::id();
            $output->writeln(Auth::id());
            try {
                $product->save();
            } catch (\Exception $e) {
                $output->writeln("Error saving product: " . $e->getMessage());
            }
            if(Auth::user()->rol->name=='administrador'){
                $userProducts = Product::all();
            }else{
                $userProducts = Seller::where('user_id', Auth::id())->first()->productos;
            }
            $html = view('profile._partial_mis_productos',compact('userProducts'))->render();
            return response()->json(['message'=>'Producto actualizado correctamente.','view'=>$html]);
        }else{
            return response()->json(['message'=>'No tienes permisos.'],403);
        }

    }

    public function destroy(Request $request){
        $product = Product::find($request->product_id);
        if($product->seller_id==Auth::id() || Auth::user()->rol->name=='administrador'){
            $product->delete();
            if(Auth::user()->rol->name=='administrador'){
                $userProducts = Product::all();
            }else{
                $userProducts = Seller::find(Auth::id())->productos;
            }
            $html = view('profile._partial_mis_productos',compact('userProducts'))->render();

            return response()->json(['message'=>'Producto eliminado correctamente.','view'=>$html]);
        }

    }

    public function show($productId){
        $producto = Product::find($productId);
        if($productId){
            return view('products.single',compact('producto'));
        }else{
            return redirect()->back();
        }

    }

    public function filter(Request $request){

        if(!$request->estado){
            $productos = Product::where('price','>',$request->pmin)
                ->where('price','<',$request->pmax)
                ->get();
            $html = view('products._partial_productos',compact('productos'))->render();
            return response()->json(['view'=>$html]);

        }
        $productos = Product::where('status',$request->estado)
            ->where('price','>',$request->pmin)
            ->where('price','<',$request->pmax)
            ->get();
        $html = view('products._partial_productos',compact('productos'))->render();

        return response()->json(['view'=>$html]);

    }

    public function getSeller($sellerId){
        $seller = User::find($sellerId);
        return response()->json(['seller'=>$seller]);
    }
}