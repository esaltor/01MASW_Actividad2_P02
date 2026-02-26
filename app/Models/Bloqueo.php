<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bloqueo extends Model
{
    use SoftDeletes;

    protected $table = 'BLOQUEO';

    // Laravel no soporta claves primarias compuestas automáticamente, 
    // por lo que se deben configurar manualmente
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'idRecurso',
        'diaSemana',
        'idSesion',
        'motivoBloqueo'
    ];

    protected $keyType = 'string';

    // Relaciones

    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'idRecurso', 'idRecurso');
    }

    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'idSesion', 'idSesion');
    }
}
