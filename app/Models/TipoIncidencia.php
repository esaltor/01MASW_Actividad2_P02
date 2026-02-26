<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIncidencia extends Model
{
    use SoftDeletes;

    protected $table = 'TIPOINCIDENCIA';
    protected $primaryKey = 'idTipoIncidencia';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relaciones
    public function incidencias()
    {
        return $this->hasMany(
            Incidencia::class,
            'idTipoIncidencia',
            'idTipoIncidencia'
        );
    }
}