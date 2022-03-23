<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueImages extends Model
{
    use HasFactory;

    protected $fillable = [
        "venue_id",
        "image",
        "is_featured",
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
