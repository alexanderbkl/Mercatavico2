<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = ['user_id','foto','title','description','price','stock','status'];
    public function materiales(){
        return $this->hasMany(MaterialIntermediate::class,'product_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
