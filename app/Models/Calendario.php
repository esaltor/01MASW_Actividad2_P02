<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendario extends Model
{
    use SoftDeletes;

    protected $table = 'CALENDARIO';
    protected $primaryKey = 'fecha';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['fecha', 'lectivo'];

    protected $casts = [
        'fecha' => 'date',
        'lectivo' => 'boolean',
    ];
}