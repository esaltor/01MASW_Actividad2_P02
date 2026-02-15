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

    // Relaciones
    public function tipoIncidencia()
    {
        return $this->belongsTo(TipoIncidencia::class, 'idTipoIncidencia', 'idTipoIncidencia');
    }

    public function elemento()
    {
        return $this->belongsTo(Elemento::class, 'idElemento', 'idElemento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function adjuntosIncidencia()
    {
        return $this->hasMany(AdjuntoIncidencia::class, 'idIncidencia', 'idIncidencia');
    }
}
