<?php

namespace App\Console\Commands;

use App\Models\Material;
use Illuminate\Console\Command;

class startm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'startm';

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
        $materiales = Material::all();
        foreach($materiales as $material){
            $material->delete();
        }
        $materiales = ['Madera','Caucho','Metal','Acero','Zinc'];
        foreach($materiales as $material){

            Material::create([
                'name'=>$material
            ]);
        }

        return true;
    }
}
