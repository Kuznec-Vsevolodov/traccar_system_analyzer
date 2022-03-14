<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicesPositions extends Model
{
    use HasFactory;

    protected $fillable = [
        'deviceid',
        'speed',
        'longitude',
        'latitude',
        'course',
        'distance'
    ];
}
