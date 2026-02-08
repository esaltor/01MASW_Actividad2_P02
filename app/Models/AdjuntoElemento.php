<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adjunto;
use App\Models\Elemento;

class AdjuntoElemento extends Model
{
    protected $table = 'ADJUNTOELEMENTO';
    protected $primaryKey = 'idAdjunto';

    public $timestamps = false;

    protected $fillable = [
        'idAdjunto',
        'idElemento',
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
         return $this->belongsTo(Elemento::class, 'idElemento', 'idElemento');
     }
}
