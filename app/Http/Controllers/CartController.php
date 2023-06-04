<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class CartController extends Controller
{
    public function add($productId)
    {
        $producto = Product::find($productId);
        if ($producto) {
            if ($producto->stock > 0) {
                $cart = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
                if ($cart) {
                    $cart->quantity += 1;
                    $cart->save();
                } else {
                    Cart::create([
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                        'quantity' => 1,
                    ]);
                }
                $producto->stock -= 1;
                $producto->save();
                $ItemsCart = Auth::user()->cartItems->pluck('quantity');
                $numItems = 0;
                foreach ($ItemsCart as $item) {
                    $numItems += $item;
                }
                return response()->json(['status' => '', 'message' => 'Producto añadido al carrito.', 'numItems' => $numItems], 201);

            }else{
                return response()->json(['status' => '', 'message' => 'No quedan existencias de este producto.'], 404);
            }
        } else {
            return response()->json(['status' => '', 'message' => 'No quedan existencias de este producto.'], 404);
        }


    }

    public function index()
    {
        //$cartItems = Auth::user()->cartItems;
        //$totalAmount = CartHelper::calcTotalAmount();
        return view('cart.buy');
    }

    public function getProduct($product_id)
    {

        try {
            // Find the product by its id. If it doesn't exist, return a 404 response.
            $product = Product::findOrFail($product_id);
            return response()->json($product);
        } catch (Exception $e) {
            // Log the error
            Log::error($e);
            // Respond with an error message
            return response()->json(['error' => 'An error occurred while getting the product: ', $e], 500);
        }

    }

    public function destroy(Request $request)
    {
        $cartItem = Cart::find($request->cart_id);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->product->stock += $cartItem->quantity;
            $cartItem->product->save();
            $cartItem->delete();
            $cartItems = Auth::user()->cartItems;

            $totalAmount = CartHelper::calcTotalAmount();
            $html = view('cart._partial_cart', compact('cartItems', 'totalAmount'))->render();
            return response()->json(['message' => 'Elemento eliminado correctamente.', 'view' => $html]);

        }
    }
}
