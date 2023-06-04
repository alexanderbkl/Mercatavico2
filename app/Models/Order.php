<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = ['total','user_id','pay','transaction','payment_id', 'date'];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
