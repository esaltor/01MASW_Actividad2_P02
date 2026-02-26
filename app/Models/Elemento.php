<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AdjuntoElemento;

class Elemento extends Model
{
    use SoftDeletes;

    protected $table = 'ELEMENTO';
    protected $primaryKey = 'idElemento';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'idRecurso',
    ];

    // Relaciones
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'idRecurso', 'idRecurso');
    }

    public function adjuntosElemento()
    {
        return $this->hasMany(AdjuntoElemento::class, 'idElemento', 'idElemento');
    }
}
