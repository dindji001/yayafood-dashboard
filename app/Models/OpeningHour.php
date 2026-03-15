<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
        'is_24h'
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'is_24h' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
