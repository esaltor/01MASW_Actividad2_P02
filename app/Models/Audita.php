<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audita extends Model
{
    use SoftDeletes;

    protected $table = 'AUDITA';
    protected $primaryKey = 'idAudita';

    public $timestamps = true;

    protected $fillable = [
        'fechaHora',
        'accion',
        'idReserva',
        'idUsuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'idReserva', 'idReserva');
    }
}
