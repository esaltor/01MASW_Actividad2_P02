<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historial extends Model
{
    use SoftDeletes;

    protected $table = 'HISTORIAL';
    protected $primaryKey = 'idHistorial';

    public $timestamps = true;

    protected $fillable = [
        'fecha',
        'horaInicio',
        'horaFin',
        'idUsuario',
        'idRecurso',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
    
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'idRecurso', 'idRecurso');
    }
}

