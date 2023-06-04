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
        Schema::create('products_materials', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('products_id');
          $table->unsignedBigInteger('materials_id');
          $table->timestamps();

          $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
          $table->foreign('materials_id')->references('id')->on('materials')->onDelete('cascade');
        });

        //create products_materials with products_id 1, materials_id 1, etc

        $products_materials1 = DB::table('products_materials')->insert([
            'products_id'=>1,
            'materials_id'=>1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_materials');
    }
};
