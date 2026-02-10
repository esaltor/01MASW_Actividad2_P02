<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AdjuntoIncidencia;

class Incidencia extends Model
{
    use SoftDeletes;

    protected $table = 'INCIDENCIA';
    protected $primaryKey = 'idIncidencia';

    public $timestamps = true;

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'idTipoIncidencia',
        'idElemento',
        'idUsuario',
    ];

    public function adjuntosIncidencia()
    {
        return $this->hasMany(AdjuntoIncidencia::class, 'idIncidencia', 'idIncidencia');
    }
}
