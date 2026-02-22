<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendario extends Model
{
    use SoftDeletes;

    protected $table = 'CALENDARIO';

    // La PK es fecha
    protected $primaryKey = 'fecha';

    // No es auto incremental
    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'fecha',
        'lectivo',
    ];

    protected $casts = [
        'fecha' => 'date:Y-m-d',
        'lectivo' => 'boolean',
    ];
}