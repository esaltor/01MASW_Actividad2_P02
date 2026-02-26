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
}
