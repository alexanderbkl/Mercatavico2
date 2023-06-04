<?php

use App\Models\Material;
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
        Schema::create('materials', function (Blueprint $table) {
          $table->id();
          $table->string('name', 32);
          $table->timestamps();
        });

        $materiales = ['Madera','Caucho','Metal','Acero','Zinc'];
        foreach($materiales as $material){
            Material::create([
                'name'=>$material
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
        Schema::dropIfExists('materials');
    }
};
