<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $roles = ['administrador','usuario_registrado'];
        $rolesExist = Rol::all();
        if($rolesExist){
            foreach($rolesExist as $rol){
                $rol->delete();
            }
        }
        foreach($roles as $rol){
            Rol::create([
                'name'=>$rol
            ]);
        }
        echo "Roles creados\n";
        $rolAdmin = Rol::where('name','administrador')->first();
        $admins = User::where('rol_id',$rolAdmin->id)->get();
        if(count($admins)==0 && $rolAdmin){
            User::create([
                'name' => 'Adminsitrador',
                'email' => 'admin@admin.es',
                'password' => Hash::make('123456789'),
                'rol_id'=>$rolAdmin->id
            ]);
            echo "Usuario administrador creado\n";
        }

        $materiales = Material::all();
        if(count($materiales)==0){
            $materiales = ['Madera','Caucho','Metal','Acero','Zinc'];
            foreach($materiales as $material){
                Material::create([
                    'name'=>$material
                ]);
            }
            echo "Materiales creados\n";
        }

        //Añadir materiales: Madera, caucho, metal, acero, zinc

        return Command::SUCCESS;
    }
}
