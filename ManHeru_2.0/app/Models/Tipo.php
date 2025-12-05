<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;

    protected $table = 'Tipos';
    protected $primaryKey = 'ID_Tipo';
    
    protected $fillable = [
        'Nombre',
        'Estatus',
    ];

    protected $casts = [
        'Estatus' => 'boolean',
    ];

    /**
     * Relación: Un tipo tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'ID_Tipo', 'ID_Tipo');
    }

    /**
     * Scope para obtener solo tipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('Estatus', 1);
    }
}