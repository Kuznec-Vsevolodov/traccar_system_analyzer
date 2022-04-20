<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripMaster extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'device_id',
        'start_time',
        'end_time',
        'instructor_id',
        'speed',
        'student_id',
        'booking_id'
    ];
}
