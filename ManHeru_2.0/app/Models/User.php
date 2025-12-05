<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Configuraciones específicas para SQL Server y tu estructura
    protected $table = 'Usuarios';
    protected $primaryKey = 'ID_Usuario';
    public $timestamps = false; // Si no tienes created_at y updated_at

    /**
     * Atributos que se pueden asignar masivamente (necesarios para el registro).
     */
    protected $fillable = [
        'Nombre',
        'Gmail',
        'Contrasena',
        'Telefono',
        'Estatus',
        'ID_Rol',
        'ID_Direccion',
    ];

    /**
     * Atributos que deben ocultarse para la serialización (seguridad).
     */
    protected $hidden = [
        'Contrasena',
    ];

    /**
     * Sobreescribir el método para que el login sepa dónde buscar la contraseña
     * y el email, utilizando los nombres de tus columnas (Contrasena y Gmail).
     */
    public function getAuthPassword()
    {
        return $this->Contrasena;
    }
    
    // Aunque no uses el sistema de Auth completo de Laravel, esto ayuda a mantener la convención
    public function getEmailForPasswordReset()
    {
        return $this->Gmail;
    }
}