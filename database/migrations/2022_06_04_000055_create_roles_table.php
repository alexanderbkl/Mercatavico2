<?php

use App\Models\Rol;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name',250)->nullable($value = false);
            $table->timestamps();
            $table->engine ='innoDB';
        });

        Rol::create([
            'name'=>'miembro',
        ]);

        Rol::create([
            'name'=>'administrador',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
