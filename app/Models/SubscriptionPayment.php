<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'subscription_plan_id',
        'user_id',
        'subscription_price',
        'payment_id',
        'status',
        'subscribed_at',
    ];

    /**
     * Get the user associated with the subscription payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription plan associated with the subscription payment.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
