<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'menu_content', // Keep for compatibility with legacy DB schema
    ];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_menu_schedule');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
