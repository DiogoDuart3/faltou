<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutageComment extends Model
{
    protected $fillable = [
        'type',
        'text',
    ];
}
