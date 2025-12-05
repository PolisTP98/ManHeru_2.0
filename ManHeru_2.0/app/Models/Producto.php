<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'Productos';
    protected $primaryKey = 'ID_Producto';
    
    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Precio',
        'Imagen',
        'Stock',
        'Estatus',
        'ID_Tipo',
    ];

    protected $casts = [
        'Precio' => 'decimal:2',
        'Stock' => 'integer',
        'Estatus' => 'boolean',
    ];

    /**
     * Relación: Un producto pertenece a un tipo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'ID_Tipo', 'ID_Tipo');
    }

    /**
     * Scope para obtener solo productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('Estatus', 1);
    }

    /**
     * Scope para obtener productos disponibles (con stock)
     */
    public function scopeDisponibles($query)
    {
        return $query->where('Estatus', 1)->where('Stock', '>', 0);
    }

    /**
     * Accesor para la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->Imagen && file_exists(public_path('images/productos/' . $this->Imagen))) {
            return asset('images/productos/' . $this->Imagen);
        }
        return asset('images/producto-default.jpg');
    }
}