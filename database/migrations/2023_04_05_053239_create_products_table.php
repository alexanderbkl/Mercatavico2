<?php

use App\Models\Product;
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
        Schema::create('products', function (Blueprint $table) {
          $table->id();
          $table->decimal('price', 8, 2);
          $table->string('title', 32);
          $table->integer('stock');
          $table->string('description', 128);
          $table->string('foto', 63);
          $table->enum('status', ['Nuevo', 'Usado', 'Estropeado']);
          $table->unsignedBigInteger('seller_id');
          $table->foreign('seller_id')->references('user_id')->on('sellers');
          $table->timestamps();
        });

        //create product with price 10, title product1, stock 10, description description1, etc

        $product1 = Product::create([
            'price'=>10,
            'title'=>'product1',
            'stock'=>10,
            'description'=>'description1',
            'foto'=>'Z2PIAt3yqKtm3fs1678866576jpg',
            'status'=>'Nuevo',
            'seller_id'=>1,
        ]);

        $product2 = Product::create([
            'price'=>20,
            'title'=>'product2',
            'stock'=>20,
            'description'=>'description2',
            'foto'=>'Z2PIAt3yqKtm3fs1678866576jpg',
            'status'=>'Usado',
            'seller_id'=>2,
        ]);

        $product3 = Product::create([
            'price'=>30,
            'title'=>'product3',
            'stock'=>30,
            'description'=>'description3',
            'foto'=>'Z2PIAt3yqKtm3fs1678866576jpg',
            'status'=>'Estropeado',
            'seller_id'=>3,
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};