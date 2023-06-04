<?php

use App\Models\SellerCalifications;
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
        Schema::create('seller_califications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('users');
            $table->unsignedBigInteger('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            //calification start 1 to 5, do as you want
            $table->integer('calification');
            $table->timestamps();
        });

        //create dummy data

        SellerCalifications::create([
            'seller_id' => 2,
            'buyer_id' => 1,
            'order_id' => 1,
            'calification' => 5,
        ]);
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_califications');
    }
};
