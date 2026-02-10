<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AdjuntoIncidencia;
use App\Models\AdjuntoElemento;

class Adjunto extends Model
{
    use SoftDeletes;

    protected $table = 'ADJUNTO';
    protected $primaryKey = 'idAdjunto';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'mimeType',
        'tamBytes',
        'url',
    ];

    /*
     * Relaciones con las tablas hijas
     */

     public function adjuntoIncidencia()
     {
        return $this->hasOne(AdjuntoIncidencia::class, 'idAdjunto', 'idAdjunto');
     }
 
     public function adjuntoElemento()
     {
        return $this->hasOne(AdjuntoElemento::class, 'idAdjunto', 'idAdjunto');
     }
}
