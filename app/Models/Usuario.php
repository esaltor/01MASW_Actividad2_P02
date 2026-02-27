<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $table = 'USUARIO';
    protected $primaryKey = 'idUsuario';

    
    public $timestamps = true;
    
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'password',
        'idRol',
    ];
    
    protected $hidden = ['password'];

    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'idUsuario', 'idUsuario');
    }

    public function historiales()
    {
        return $this->hasMany(Historial::class, 'idUsuario', 'idUsuario');
    }

    public function auditorias()
    {
        return $this->hasMany(Audita::class, 'idUsuario', 'idUsuario');
    }
}
