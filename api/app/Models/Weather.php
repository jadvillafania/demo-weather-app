<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weathers';

    public static $dates = ['updated_at', 'created_at'];

    protected $fillable = [
        'longitude',
        'latitude',
        'city_name',
        'visibility',
        'weather',
        'main',
        'wind',
        'clouds',
        'units',
        'location',
        'timezone_module',
        'sun_module',
        'forecast',
        'updated_at'
    ];

    protected $casts = [
        'weather' => AsArrayObject::class,
        'main' => AsArrayObject::class,
        'wind' => AsArrayObject::class,
        'clouds' => AsArrayObject::class,
        'location' => AsArrayObject::class,
        'timezone_module' => AsArrayObject::class,
        'sun_module' => AsArrayObject::class,
        'forecast' => AsArrayObject::class,
    ];
}
