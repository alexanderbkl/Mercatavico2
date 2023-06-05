<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;

class MaterialController extends Controller
{
    public function add(Request $request){
        if(Auth::user()->rol->name=="administrador"){
            $request->validate([
                'material_name'=>'required|max:250'
            ]);
            Material::create([
               'name'=>$request->material_name,
            ]);
            $materials = Material::all();
            $html = view('profile._partial_materials',compact('materials'))->render();
            return response()->json(['status'=>'ok','message'=>'Material añadido correctamente.','view'=>$html]);
        }
    }
    public function update(Request $request){
        $output = new ConsoleOutput();
        $output->writeln("ha entrao");
        try {
            $output->writeln("ha entrado");


        if(Auth::user()->rol->name=="administrador"){
            $material = Material::find($request->material_id);
            if($material){
                $material->name = $request->material_name;
                $material->save();
                $materials = Material::all();
                $html = view('profile._partial_materials',compact('materials'))->render();
                return response()->json(['status'=>'ok','message'=>'Material editado correctamente.','view'=>$html]);
            }
        }
    } catch (\Throwable $th) {
        $output->writeln($th->getMessage());
        return response()->json(['status'=>'error','message'=>'Error al editar el material.']);
    }

    }
    public function destroy(Request $request){
        if(Auth::user()->rol->name=="administrador"){
            $material = Material::find($request->material_id);
            if($material){
                $material->delete();
                $materials = Material::all();
                $html = view('profile._partial_materials',compact('materials'))->render();
                return response()->json(['status'=>'ok','message'=>'Material eliminado correctamente.','view'=>$html]);
            }
        }

    }
}
