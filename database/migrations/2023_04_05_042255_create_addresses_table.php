<?php

use App\Models\Address;
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
        Schema::create('addresses', function (Blueprint $table) {
          $table->id();
          $table->string('cp', 5);
          $table->string('address', 64);
          $table->unsignedBigInteger('city_id');
		  $table->foreign('city_id')->references('id')->on('cities');
          $table->timestamps();
        });

        //create three addresses

        $address1 = Address::create([
            'cp'=>'15001',
            'address'=>'Calle de la Torre, 1',
            'city_id'=>1,
        ]);

        $address2 = Address::create([
            'cp'=>'01001',
            'address'=>'Calle de la Torre, 2',
            'city_id'=>2,
        ]);

        $address3 = Address::create([
            'cp'=>'02001',
            'address'=>'Calle de la Torre, 3',
            'city_id'=>3,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};