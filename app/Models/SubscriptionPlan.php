<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_months',
        'badge',
        'includes', // Add this line

    ];


    public function getIncludesArrAttribute(){
        if ( ! $this->includes){
            return null;
        }
        $newArr = array();
        if ($this->includes){
            $newArr = explode("\n", $this->includes);
        }
        $Arr = array_filter(array_map('trim', $newArr));
        return $Arr;
    }



        public function users(): HasMany
        {
            return $this->hasMany(User::class, 'subscription_plan');
        }

        public function price($originalPriceOnRight = false, $showOff = false){
            $current_price = '';
            if ($this->paid && $this->price > 0){
                $current_price = $this->sale_price > 0 ?  price_format($this->sale_price) : price_format($this->price);
            }else{
                $current_price = __t('free');
            }
            return $current_price;
        }




        }
