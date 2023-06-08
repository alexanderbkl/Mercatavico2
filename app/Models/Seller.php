<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Output\ConsoleOutput;

class Seller extends Model
{
    use HasFactory;

    protected $table = "sellers";

    protected $primaryKey = 'user_id';

    protected $fillable = ['cred_total', 'payback', 'calificate', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function productos(){
        return $this->hasMany(Product::class,'seller_id','user_id');
    }


    //orderdetails has product id
    public function califications($boughtProductId) {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id')->where('product_id', $boughtProductId)->whereNotNull('calification');
    }

    public function averageCalification($boughtProductId) {
        $output = new ConsoleOutput();

        $califications = $this->califications($boughtProductId);

        $totalCalification = 0;
        $totalRatings = $califications->count();

        if($totalRatings == 0) {
            $output->writeln('Not yet rated');
            return 'Not yet rated';
        } else {
            foreach ($califications as $calification) {
                $output->writeln("Calification: ");
                $output->writeln($calification);
                $totalCalification += $calification->calification;
            }

            $average = round($totalCalification / $totalRatings);

            switch ($average) {
                case 1:
                    return 'muy malo';
                case 2:
                    return 'malo';
                case 3:
                    return 'bueno';
                default:
                    return 'muy bueno';
            }

        }
    }



}