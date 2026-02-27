<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurso extends Model
{
    use SoftDeletes;

    protected $table = 'RECURSO';
    protected $primaryKey = 'idRecurso';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'ubicacion',
        'estado',
        'caracteristicas',
        'idTipoRecurso',
    ];

    public function historiales()
    {
        return $this->hasMany(Historial::class, 'idRecurso', 'idRecurso');
    }
}
