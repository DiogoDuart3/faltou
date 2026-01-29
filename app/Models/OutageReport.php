<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutageReport extends Model
{
    protected $fillable = [
        'type',
        'lat',
        'lng',
        'locality',
        'note',
        'impact',
        'method',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];
}
