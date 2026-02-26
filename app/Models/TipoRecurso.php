<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoRecurso extends Model
{
    use SoftDeletes;

    protected $table = 'TIPORECURSO';
    protected $primaryKey = 'idTipoRecurso';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
