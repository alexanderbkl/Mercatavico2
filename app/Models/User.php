<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\Console\Output\ConsoleOutput;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = "users";

    protected $fillable = ['name','email','credits','rol','password', 'address_id'];

    public function addressUser(){
        $output = new ConsoleOutput();
        $output->writeln("entered addressUser");
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function productos(){
        return $this->hasMany(Product::class,'user_id','id');
    }

    public function getRolAttribute($value)
{
    return (object) ['name' => $value];
}

    public function orders(){
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function buyer(){
        return $this->hasOne(Buyer::class, 'user_id', 'id');
    }

    public function seller(){
        return $this->hasOne(Seller::class, 'user_id', 'id');
    }

    public function orderDetails($orders) {
        $orderDetails = [];
        foreach ($orders as $order) {
            $orderDetails[] = $order->orderDetails;
        }
        return $this->hasMany(OrderDetail::class, 'user_id', 'id');
    }


}