<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class state extends Model
{
    use HasFactory;
    protected $table = "order_";
    protected $fillable = ['user_id','foto','title','description','price','stock','state_id'];
}
