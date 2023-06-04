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
        Schema::create('materials_products_intermediate', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->engine ='innoDB';
        });



        DB::table('materials_products_intermediate')->insert([
            'product_id'=>1,
            'material_id'=>1,
        ]);


        DB::table('materials_products_intermediate')->insert([
            'product_id'=>2,
            'material_id'=>2,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials_products_intermediate');
    }
};
