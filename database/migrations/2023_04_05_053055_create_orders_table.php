<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
          $table->id();
          $table->string('transaction', 512)->nullable();
          $table->decimal('total',8,2)->nullable(false);
          $table->boolean('pay')->default(false);
          $table->unsignedBigInteger('buyer_id');
          $table->foreign('buyer_id')->references('user_id')->on('buyers')->onDelete('restrict')->onUpdate('cascade');
          $table->unsignedInteger('payment_id')->nullable(); //removed second parameter from unsignedInteger
          $table->foreign('payment_id')->references('id')->on('payments')->onDelete('restrict')->onUpdate('cascade');
          $table->enum('currency', ['EUR', 'USD']);
          $table->dateTime('date');
          $table->timestamps();
          $table->engine ='innoDB';
        });

        //create order with curency euro, transaction transaction1, date 2021-04-05 05:32:55, etc

        $order1 = Order::create([
            'transaction'=>'transaction1',
            'total'=>10,
            'pay'=>true,
            'buyer_id'=>1,
            'payment_id'=>1,
            'currency'=>'EUR',
            'date'=>'2021-04-05 05:32:55',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
