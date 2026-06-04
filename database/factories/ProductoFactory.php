<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'precio' => fake()->randomFloat(2, 10, 5000),
            'stock' => fake()->numberBetween(0, 100),
            'imagen' => null,
            'categoria_id' => Categoria::factory(),
        ];
    }
}