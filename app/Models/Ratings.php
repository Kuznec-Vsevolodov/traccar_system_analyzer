<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'author_id',
        'lesson_id',
        'rating_score',
        'category_name'
    ];
}
