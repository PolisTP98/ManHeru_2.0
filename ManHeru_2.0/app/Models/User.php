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
    public $timestamps = true; // Cambiado a true para usar created_at y updated_at

    /**
     * Atributos que se pueden asignar masivamente
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
     * Atributos que deben ocultarse para la serialización
     */
    protected $hidden = [
        'Contrasena',
        'remember_token',
    ];

    /**
     * Atributos que deben ser casteados
     */
    protected $casts = [
        'Estatus' => 'integer',
        'ID_Rol' => 'integer',
    ];

    /**
     * Sobreescribir el método para que Laravel sepa dónde buscar la contraseña
     */
    public function getAuthPassword()
    {
        return $this->Contrasena;
    }
    
    /**
     * Método para obtener el email
     */
    public function getEmailForPasswordReset()
    {
        return $this->Gmail;
    }

    /**
     * Relación con Rol (opcional, si tienes tabla de roles)
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'ID_Rol', 'ID_Rol');
    }

    /**
     * Relación con Dirección (opcional)
     */
    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'ID_Direccion', 'ID_Direccion');
    }
}