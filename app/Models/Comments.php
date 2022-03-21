<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'author_id',
        'lesson_id',
        'longitude',
        'latitude',
        'text'
    ];
}
