<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sesion extends Model
{
    use SoftDeletes;

    protected $table = 'SESION';

    protected $primaryKey = 'idSesion';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'horaInicio',
        'horaFin'
    ];

    public $timestamps = true;
}