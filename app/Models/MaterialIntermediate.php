<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIntermediate extends Model
{
    use HasFactory;
    protected $table = "materials_products_intermediate";
    protected $fillable = ['product_id','material_id'];
    public function material(){
        return $this->belongsTo(Material::class,'material_id','id');
    }
    public function producto(){
        return $this->belongsTo(Product::class,'product','id');
    }
}
