<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'device_id',
        'lesson_start',
        'lesson_end',
        'lesson_driver',
        'max_speed',
        'harsh_braking_count',
        'rapid_acceleration_count',
        'wide_turn_count',
        'lesson_student',
        'grade'
    ];
}
