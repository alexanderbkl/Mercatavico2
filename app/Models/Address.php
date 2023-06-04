<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Output\ConsoleOutput;

class Address extends Model
{
    protected $fillable = [
        'cp',
        'address',
        'city_id',
    ];
    use HasFactory;

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    public function user()
    {
    return $this->hasOne(User::class);
    }
}
