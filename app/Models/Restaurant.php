<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'description', 'address', 'phone', 'logo', 'banner', 'qr_code', 
        'is_active', 'is_featured', 'has_daily_menu', 'allow_pay_on_spot', 'allow_online_payment'
    ];

    protected $appends = ['logo_url', 'banner_url', 'qr_code_url', 'rating'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_daily_menu' => 'boolean',
        'allow_pay_on_spot' => 'boolean',
        'allow_online_payment' => 'boolean',
    ];

    public function getRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?: 0, 1);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/' . $this->banner) : null;
    }

    public function generateQrCode()
    {
        $url = config('app.url') . '/r/' . $this->id;
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($url);

        $dir = 'restaurants/qrcodes';
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($dir)) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dir);
        }

        $path = $dir . '/qr_' . $this->id . '.svg';
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $qrCode);

        $this->update(['qr_code' => $path]);
        
        return $path;
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code ? asset('storage/' . $this->qr_code) : null;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function accessSessions()
    {
        return $this->hasMany(RestaurantAccessSession::class);
    }

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class)->orderBy('day_of_week');
    }

    public function menuSchedules()
    {
        return $this->hasMany(MenuSchedule::class);
    }
}
