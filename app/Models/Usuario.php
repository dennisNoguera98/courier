<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'idtable1';
    public $timestamps = false;

    protected $fillable = [
        'Usuarios_usuario',
        'Usuarios_contrasena',
        'Personas_idPersonas',
    ];

    protected $hidden = [
        'Usuarios_contrasena',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Personas_idPersonas', 'idPersonas');
    }

    public function getAuthPassword()
    {
        return $this->Usuarios_contrasena;
    }

    public function getAuthIdentifierName()
    {
        return 'Usuarios_usuario';
    }

        public function perfiles()
    {
        return $this->belongsToMany(Perfil::class, 'perfiles_has_usuarios', 'Usuarios_idtable1', 'Perfiles_idPerfiles');
    }
    
}