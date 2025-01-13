<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'description',
        'image_path',
        'price_range',
        'rating',
        'cuisine_type',
        'operating_hours'
    ];
}