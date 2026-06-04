<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        $nombre = fake()->unique()->randomElement([
            'Electrónica',
            'Computación',
            'Gaming',
            'Audio',
            'Telefonía',
            'Oficina',
            'Accesorios'
        ]);

        return [
            'nombre' => $nombre,
            'slug' => Str::slug($nombre)
        ];
    }
}