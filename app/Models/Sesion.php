<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sesion extends Model
{
    use SoftDeletes;

    protected $table = 'SESION';
    protected $primaryKey = 'idSesion';

    public $timestamps = true;

    protected $fillable = [
        'horaInicio',
        'horaFin',
    ];

    protected $casts = [
        'horaInicio' => 'datetime:H:i:s',
        'horaFin' => 'datetime:H:i:s',
    ];
}