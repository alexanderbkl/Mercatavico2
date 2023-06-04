<?php

use App\Models\Payment;
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
        Schema::create('payments', function (Blueprint $table) {
          $table->integerIncrements('id');
          $table->string('payment_id');
          $table->string('payer_id');
          $table->string('payer_email');
          $table->float('amount', 8, 2);
          $table->enum('currency', ['EUR', 'USD']);
          $table->string('payment_status');
          $table->string('payment_method');
          $table->timestamps();
          $table->engine ='innoDB';
        });

        //create payment with total 10, orders_id 1, etc

        $payment1 = Payment::create([
            'payment_id'=>'payment1',
            'payer_id'=>'payer1',
            'payer_email'=>'user1@mail.com',
            'amount'=>10,
            'currency'=>'EUR',
            'payment_status'=>'approved',
            'payment_method'=>'PayPal',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
