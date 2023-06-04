<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartHelper
{

    public static function getItemsCart()
    {
        if (Auth::check()) {
            $ItemsCart = Auth::user()->cartItems->pluck('quantity');
            $numItems = 0;
            foreach ($ItemsCart as $item) {
                $numItems += $item;
            }
            return $numItems;
        } else {
            return 0;
        }

    }

    public static function calcTotalAmount($cartItems)
    {
        $total = 0;
        //get product price and quantity and multiply them
        foreach($cartItems as $item){
            //get price of product from Product model
            $product_price = Product::find($item['id'])->price;
            $total += $item['quantity']*$product_price;
        }
        return $total;

    }
}
