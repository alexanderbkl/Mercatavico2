<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $table = "sellers";

    protected $fillable = ['cred_total', 'payback', 'calificate', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function averageCalification() {
        $califications = $this->user->sellerCalifications;

        $totalCalification = 0;
        $totalRatings = $califications->count();

        if($totalRatings == 0) {
            return 'Not yet rated';
        } else {
            foreach ($califications as $calification) {
                $totalCalification += $calification->calification;
            }

            $average = round($totalCalification / $totalRatings);

            switch ($average) {
                case 1:
                    return 'muy malo';
                case 2:
                    return 'malo';
                case 3:
                    return 'regular';
                case 4:
                    return 'bueno';
                case 5:
                    return 'muy bueno';
                default:
                    return 'Not yet rated';
            }
        }
    }
}
