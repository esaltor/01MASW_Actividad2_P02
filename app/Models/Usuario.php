<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
 
class Usuario extends Authenticatable implements OAuthenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected $table = 'USUARIO';
    protected $primaryKey = 'idUsuario';

    
    public $timestamps = true;
    
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'password',
        'role'
    ];
    
    protected $hidden = ['password'];

    // Relaciones
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
