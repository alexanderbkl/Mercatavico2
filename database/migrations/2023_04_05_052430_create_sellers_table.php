<?php

use App\Models\Seller;
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
        Schema::create('sellers', function (Blueprint $table) {
          $table->float('cred_total', 8, 2);
          $table->boolean('payback');
          $table->enum('calificate', ['muy malo', 'malo', 'bueno', 'muy bueno'])->default('bueno');
          $table->unsignedBigInteger('user_id')->primary();
          $table->foreign('user_id')->references('id')->on('users');
          $table->timestamps();
        });

        //create seller with cred_total 10, payback true, calificate muy bueno, etc

        $seller1 = Seller::create([
            'cred_total'=>10,
            'payback'=>true,
            'calificate'=>'muy bueno',
            'user_id'=>1,
        ]);

        $seller2 = Seller::create([
            'cred_total'=>10,
            'payback'=>false,
            'calificate'=>'bueno',
            'user_id'=>2,
        ]);

        $seller3 = Seller::create([
            'cred_total'=>0,
            'payback'=>true,
            'calificate'=>'muy malo',
            'user_id'=>3,
        ]);

        //TODO when someone buys from the seller, the cred_total is increased by the amount of the credits
        //in the PayPalController
        //TODO add option to set payback on user's profile edit
        //TODO on user's bought products, add option to calificate the seller
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
};