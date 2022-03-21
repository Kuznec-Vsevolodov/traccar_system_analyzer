<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'first_name',
        'last_name',
    ];
}
