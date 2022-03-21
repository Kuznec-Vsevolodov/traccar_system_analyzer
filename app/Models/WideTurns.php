<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WideTurns extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'lesson_id',
        'longitude',
        'latitude',
    ];
}
