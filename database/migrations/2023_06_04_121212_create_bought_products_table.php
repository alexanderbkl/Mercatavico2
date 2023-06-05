<?php

use App\Models\BoughtProducts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bought_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('seller_id')->constrained('users');
            //create nullable calification number
            $table->integer('calification')->nullable();
            $table->float('price')->nullable();
            $table->timestamps();
        });

        //create dummy data
        BoughtProducts::create([
            'user_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'order_id' => 1,
            'seller_id' => 2,
            'price' => 10,
            'calification' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bought_products');
    }
};
