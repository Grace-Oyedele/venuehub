<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'email',
        'phone_number',
        'password',
        'verified',
    ];
}
