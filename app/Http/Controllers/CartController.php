<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Exception;

class CartController extends Controller
{

    public function index()
    {
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

}
