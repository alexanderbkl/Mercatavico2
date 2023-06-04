<?php

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
        Schema::create('products_orders', function (Blueprint $table) {
          $table->unsignedBigInteger('products_id');
          $table->unsignedBigInteger('orders_id');
          $table->integer('amount');
          $table->timestamps();

          $table->foreign('products_id')->references('id')->on('products');
          $table->foreign('orders_id')->references('id')->on('orders');
        });

        //create product order with product_id 1, order_id 1, amount 1, etc

        $product_order1 = DB::table('products_orders')->insert([
            'products_id'=>1,
            'orders_id'=>1,
            'amount'=>1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_orders');
    }
};
