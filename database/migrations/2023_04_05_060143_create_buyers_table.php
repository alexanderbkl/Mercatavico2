<?php

use App\Models\Buyer;
use App\Models\User;
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
        Schema::create('buyers', function (Blueprint $table) {
          $table->id();
		  $table->enum('shipping_preferences', ['Mañana', 'Tarde']);
          $table->enum('fav_pay', ['paypal', 'tarjeta']);
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users');
          $table->timestamps();
        });



                //create buyer with shipping_preferences, fav_pay, user_id

                //TODO add shipping_preferences and fav_pay to users section in
                $buyer1 = Buyer::create([
                    'shipping_preferences'=>'Mañana',
                    'fav_pay'=>'paypal',
                    'user_id'=>1,
                ]);

                $buyer2 = Buyer::create([
                    'shipping_preferences'=>'Tarde',
                    'fav_pay'=>'tarjeta',
                    'user_id'=>2,
                ]);

                $buyer3 = Buyer::create([
                    'shipping_preferences'=>'Mañana',
                    'fav_pay'=>'paypal',
                    'user_id'=>3,
                ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyers');
    }
};
