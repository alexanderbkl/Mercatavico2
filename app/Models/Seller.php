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

    public function califications() {
        return $this->hasManyThrough(
            OrderDetail::class, // Target model
            Product::class, // Intermediate model
            'seller_id', // Foreign key on intermediate model (Product)
            'product_id', // Foreign key on target model (OrderDetail)
            'user_id', // Local key on current model (Seller)
            'id' // Local key on intermediate model (Product)
        )->whereNotNull('calification');
    }

    //orderdetails has product id


    public function averageCalification() {
        $output = new ConsoleOutput();

        $califications = $this->califications();

        $totalCalification = 0;
        $totalRatings = $califications->count();

        $output->writeln("Total ratings: ");
        $output->writeln($totalRatings);

        if($totalRatings == 0) {
            $output->writeln('Not yet rated');
            return 3;
        } else {
            //print califications as string to see if they are correct (transform to string)
            $output->writeln("Califications: ");
            //transform $califications to string
            $califications = $califications->get()->pluck('calification')->toArray();
            
            foreach ($califications as $calification) {
                $output->writeln("Calification: ");
                $output->writeln($calification);
                $totalCalification += $calification;
            }

            $average = round($totalCalification / $totalRatings);

            $output->writeln("Average: ");
            $output->writeln($average);

            //if average is 5, return 4. if average is 1, return 0

            if($average == 5) {
                return 4;
            } else {
                return $average;
            }

        }
    }



}