<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacion extends Model
{
    use HasFactory, SoftDeletes;

    // Si quieres mantener el nombre de tabla en mayúsculas
    protected $table = 'NOTIFICACION';

    // Si tu primary key no es 'id' por defecto
    protected $primaryKey = 'idNotificacion';

    // Campos que se pueden llenar mediante create()
    protected $fillable = [
        'asunto',
        'cuerpo',
        'canal',
        'enviadaEn',
        'idUsuario',
    ];

    // Tipo de dato de cada columna (opcional, pero útil)
    protected $casts = [
        'enviadaEn' => 'datetime',
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}