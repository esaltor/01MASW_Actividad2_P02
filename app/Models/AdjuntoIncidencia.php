<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adjunto;
use App\Models\Incidencia;

class AdjuntoIncidencia extends Model
{
    protected $table = 'ADJUNTOINCIDENCIA';
    protected $primaryKey = 'idAdjunto';

    public $timestamps = false;

    protected $fillable = [
        'idAdjunto',
        'idIncidencia',
    ];

    /*
     * Relaciones
     */

     public function adjunto()
     {
         return $this->belongsTo(Adjunto::class, 'idAdjunto', 'idAdjunto');
     }
 
     public function elemento()
     {
         return $this->belongsTo(Incidencia::class, 'idIncidencia', 'idIncidencia');
     }
}
