<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'image', 'is_available'];

    protected $appends = ['image_url'];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'integer',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
