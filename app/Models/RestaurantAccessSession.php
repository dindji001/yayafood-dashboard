<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantAccessSession extends Model
{
    protected $fillable = ['user_id', 'restaurant_id', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }
}
