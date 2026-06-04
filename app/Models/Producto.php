<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'categoria_id'
    ];

    // RELACIÓN
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // 🔎 BUSCAR
    public function scopeBuscar($query, $termino)
    {
        return $query->when($termino, function ($q) use ($termino) {
            $q->where('nombre', 'LIKE', "%{$termino}%")
              ->orWhere('descripcion', 'LIKE', "%{$termino}%");
        });
    }

    // 📂 CATEGORÍA
    public function scopeDeCategoria($query, $categoriaId)
    {
        return $query->when($categoriaId, function ($q) use ($categoriaId) {
            $q->where('categoria_id', $categoriaId);
        });
    }

    // 💲 PRECIO
    public function scopeRangoPrecio($query, $min, $max)
    {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max));
    }
}