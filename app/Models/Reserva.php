<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use SoftDeletes;

    protected $table = 'RESERVA';
    protected $primaryKey = 'idReserva';

    protected $fillable = [
        'estado',
        'fecha',
        'idSesion',
        'idUsuario',
        'idRecurso'
    ];

    public $timestamps = true;

    // Relaciones

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'idRecurso', 'idRecurso');
    }

    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'idSesion', 'idSesion');
    }
}
