<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('email', 64);
            $table->float('credits', 8, 2)->default(0);
            $table->string('password', 128);
            //create an enum for rol (miembro, administrador)
            $table->enum('rol', ['miembro', 'administrador'])->default('miembro');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->timestamps();
          });


        //create user with name user1, email user1@mail.com, password 12345678, etc
        $user1 = User::create([
            'name'=>'user1',
            'email'=>'user1@mail.com',
            'password'=>Hash::make('12345678'),
            'rol'=>1,
            'address_id'=>1,
        ]);

        $user2 = User::create([
            'name'=>'user2',
            'email'=>'user2@mail.com',
            'password'=>Hash::make('12345678'),
            'rol'=>1,
            'address_id'=>2,
        ]);

        $user3 = User::create([
            'name'=>'admin',
            'email'=>'admin@mail.com',
            'password'=>Hash::make('12345678'),
            'rol'=>2,
            'address_id'=>3,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
