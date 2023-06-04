<?php

use App\Models\City;
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
        Schema::create('cities', function (Blueprint $table) {
          $table->id();
          $table->string('province', 64);
          $table->timestamps();
        });

        //create Spain data for City model (A coruña, Alava, ...)
        $cities = [
            'A coruña',
            'Alava',
            'Albacete',
            'Alicante',
            'Almeria',
            'Asturias',
            'Avila',
            'Badajoz',
            'Barcelona',
            'Burgos',
            'Caceres',
            'Cadiz',
            'Cantabria',
            'Castellon',
            'Ceuta',
            'Ciudad real',
            'Cordoba',
            'Cuenca',
            'Girona',
            'Granada',
            'Guadalajara',
            'Guipuzcoa',
            'Huelva',
            'Huesca',
            'Islas baleares',
            'Jaen',
            'La rioja',
            'Las palmas',
            'Leon',
            'Lleida',
            'Lugo',
            'Madrid',
            'Malaga',
            'Melilla',
            'Murcia',
            'Navarra',
            'Ourense',
            'Palencia',
            'Pontevedra',
            'Salamanca',
            'Segovia',
            'Sevilla',
            'Soria',
            'Tarragona',
            'Tenerife',
            'Teruel',
            'Toledo',
            'Valencia',
            'Valladolid',
            'Vizcaya',
            'Zamora',
            'Zaragoza',
        ];

        foreach ($cities as $city) {
            City::create([
                'province' => $city,
            ]);
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
