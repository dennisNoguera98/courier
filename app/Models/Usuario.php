<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'usuario_id';
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
        return $this->belongsToMany(Perfil::class, 'Perfiles_has_Usuarios', 'Usuarios_usuario_id', 'Perfiles_idPerfiles');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'gestor_id');
    }

    public function extractos()
    {
        return $this->hasMany(Extracto::class, 'gestor_id');
    }
    
}