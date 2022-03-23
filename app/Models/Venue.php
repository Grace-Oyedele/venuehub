<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    const PENDING = "pending";
    const APPROVED = "approved";
    const AVAILABLE = "available";
    const BOOKED = "booked";

    protected $fillable = [
        'user_id',
        'name',
        'capacity',
        'address',
        'location',
        'state',
        'verified',
        'status',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(VenueImages::class);
    }

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class);
    }
}
